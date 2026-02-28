<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Show the form for creating a new expense.
     */
    public function create(Colocation $colocation)
    {
        if (!$colocation->hasMember(Auth::user())) {
            abort(403);
        }

        $members = $colocation->members()->get();

        return view('expenses.create', compact('colocation', 'members'));
    }

    /**
     * Store a new expense and split among all active members.
     */
    public function store(Request $request, Colocation $colocation)
    {
        // Must be an active member
        if (!$colocation->hasMember(Auth::user())) {
            abort(403, 'Vous n\'êtes pas membre de cette colocation.');
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'amount'       => 'required|numeric|min:0.01|max:999999.99',
            'category'     => 'nullable|string|max:100',
            'expense_date' => 'required|date',
            'payer_id'     => 'required|exists:users,id',
        ]);

        // Validate payer is a member of this colocation
        $payerId = (int) $validated['payer_id'];
        $memberIds = $colocation->members()->pluck('users.id')->toArray();
        if (!in_array($payerId, $memberIds)) {
            return back()->withErrors(['payer_id' => 'Le payeur doit être membre de la colocation.'])->withInput();
        }

        // Create the expense and split in a transaction
        DB::transaction(function() use ($colocation, $payerId, $validated, $memberIds) {
            $expense = Expense::create([
                'colocation_id' => $colocation->id,
                'payer_id'      => $payerId,
                'title'         => $validated['title'],
                'description'   => $validated['description'] ?? null,
                'amount'        => $validated['amount'],
                'category'      => $validated['category'] ?? null,
                'expense_date'  => $validated['expense_date'],
                'is_settled'    => false,
            ]);

            // Split among all current members with precision handling
            $memberCount = count($memberIds);
            $baseShare   = floor(($validated['amount'] / $memberCount) * 100) / 100;
            $remainder   = round($validated['amount'] - ($baseShare * $memberCount), 2);

            foreach ($memberIds as $memberId) {
                $share = ($memberId === $payerId) ? ($baseShare + $remainder) : $baseShare;
                
                $expense->debtors()->attach($memberId, [
                    'amount_owed' => $share,
                    'is_paid'     => ($memberId === $payerId), // payer already paid their own share
                    'paid_at'     => ($memberId === $payerId) ? now() : null,
                ]);
            }

            // Reward the payer for advancing money
            User::find($payerId)->increment('reputation');
        });

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', "Dépense ajoutée et répartie. Payer récompensé (+1 Rép).");
    }

    /**
     * Show the edit form for an expense.
     */
    public function edit(Colocation $colocation, Expense $expense)
    {
        $this->authorizeEditDelete($colocation, $expense);

        $members = $colocation->members()->get();

        return view('expenses.edit', compact('colocation', 'expense', 'members'));
    }

    /**
     * Update an expense.
     */
    public function update(Request $request, Colocation $colocation, Expense $expense)
    {
        $this->authorizeEditDelete($colocation, $expense);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'amount'       => 'required|numeric|min:0.01|max:999999.99',
            'category'     => 'nullable|string|max:100',
            'expense_date' => 'required|date',
            'payer_id'     => 'required|exists:users,id',
        ]);

        // Validate new payer is still a member
        $payerId   = (int) $validated['payer_id'];
        $memberIds = $colocation->members()->pluck('users.id')->toArray();
        if (!in_array($payerId, $memberIds)) {
            return back()->withErrors(['payer_id' => 'Le payeur doit être membre de la colocation.'])->withInput();
        }

        $expense->update([
            'payer_id'     => $payerId,
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'amount'       => $validated['amount'],
            'category'     => $validated['category'] ?? null,
            'expense_date' => $validated['expense_date'],
        ]);

        // Recalculate split with precision handling
        $memberCount = count($memberIds);
        $baseShare   = floor(($validated['amount'] / $memberCount) * 100) / 100;
        $remainder   = round($validated['amount'] - ($baseShare * $memberCount), 2);

        // Sync debtors with new amounts (keep is_paid status where possible)
        $existingPivots = $expense->debtors()->get()->keyBy('id');

        $expense->debtors()->detach();
        foreach ($memberIds as $memberId) {
            $share = ($memberId === $payerId) ? ($baseShare + $remainder) : $baseShare;

            $wasPaid  = isset($existingPivots[$memberId])
                ? (bool) $existingPivots[$memberId]->pivot->is_paid
                : false;
            $paidAt   = isset($existingPivots[$memberId])
                ? $existingPivots[$memberId]->pivot->paid_at
                : null;

            // Force payer as paid for their own share
            if ($memberId === $payerId) {
                $wasPaid = true;
                $paidAt  = $paidAt ?? now();
            }

            $expense->debtors()->attach($memberId, [
                'amount_owed' => $share,
                'is_paid'     => $wasPaid,
                'paid_at'     => $paidAt,
            ]);
        }

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', "Dépense « {$expense->title} » mise à jour.");
    }

    /**
     * Delete an expense.
     */
    public function destroy(Colocation $colocation, Expense $expense)
    {
        $this->authorizeEditDelete($colocation, $expense);

        $title = $expense->title;
        $expense->delete(); // debtors pivot cascades

        return redirect()
            ->route('colocations.show', $colocation)
            ->with('success', "Dépense « {$title} » supprimée.");
    }

    /**
     * Only payer or colocation owner can edit/delete.
     */
    private function authorizeEditDelete(Colocation $colocation, Expense $expense): void
    {
        if ($expense->colocation_id !== $colocation->id) {
            abort(404);
        }

        $user = Auth::user();
        $isOwner = $colocation->owner_id === $user->id;
        $isPayer = $expense->payer_id === $user->id;

        if (!$isOwner && !$isPayer) {
            abort(403, 'Seul le payeur ou le propriétaire peut modifier cette dépense.');
        }
    }
}
