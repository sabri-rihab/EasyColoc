<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ColocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $colocation = $user->currentColocation();

        if (!$colocation) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('colocations.show', $colocation);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->hasActiveColocation()) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une colocation active.');
        }

        return view('colocations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->hasActiveColocation()) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une colocation active.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
        ]);

        $colocation = Colocation::create([
            'name' => $validated['name'],
            'adresse' => $validated['adresse'],
            'owner_id' => Auth::id(),
            'is_active' => true,
        ]);

        // Add owner to members pivot table
        $colocation->members()->attach(Auth::id(), [
            'is_owner' => true,
            'joined_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Colocation créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Colocation $colocation, \Illuminate\Http\Request $request)
    {
        if (!$colocation->hasMember(Auth::user())) {
            abort(403, 'Vous n\'êtes pas membre de cette colocation.');
        }

        $members = $colocation->members()->get();

        // Monthly filter
        $selectedMonth = $request->query('month'); // e.g. "2026-02"

        $expenseQuery = $colocation->expenses()->with('payer', 'debtors', 'category_rel');

        if ($selectedMonth) {
            $expenseQuery->whereRaw("DATE_FORMAT(expense_date, '%Y-%m') = ?", [$selectedMonth]);
        }

        $expenses = $expenseQuery->orderBy('expense_date', 'desc')->get();

        // Build available months for the dropdown (months that have expenses)
        $availableMonths = $colocation->expenses()
            ->selectRaw("DATE_FORMAT(expense_date, '%Y-%m') as month")
            ->groupByRaw("DATE_FORMAT(expense_date, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(expense_date, '%Y-%m') desc")
            ->pluck('month');

        // Total for filtered period
        $filteredTotal = $expenses->sum('amount');

        // Balances calculation for the "Soldes" tab
        $userBalances = [];
        $monthlySpending = [];
        foreach ($members as $m) {
            $userBalances[$m->id] = [
                'user' => $m,
                'balance' => $m->getColocationBalance($colocation)
            ];

            if ($selectedMonth) {
                $monthlySpending[$m->id] = $colocation->expenses()
                    ->where('payer_id', $m->id)
                    ->whereRaw("DATE_FORMAT(expense_date, '%Y-%m') = ?", [$selectedMonth])
                    ->sum('amount');
            }
        }

        $categories = \App\Models\Category::whereNull('colocation_id')
            ->orWhere('colocation_id', $colocation->id)
            ->orderByRaw('colocation_id IS NULL ASC') // Custom categories first
            ->orderBy('name')
            ->get();

        return view('colocations.show', compact(
            'colocation',
            'members',
            'expenses',
            'availableMonths',
            'selectedMonth',
            'filteredTotal',
            'userBalances',
            'monthlySpending',
            'categories'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id()) {
            abort(403, 'Seul le propriétaire peut modifier la colocation.');
        }

        return view('colocations.edit', compact('colocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
        ]);

        $colocation->update($validated);

        return redirect()->route('colocations.show', $colocation)->with('success', 'Colocation mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage (Cancel colocation).
     */
    public function destroy(Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id()) {
            abort(403);
        }

        // Check if there are other members
        if ($colocation->members()->count() > 1) {
            return back()->with('error', 'Vous ne pouvez pas annuler la colocation tant qu\'elle contient encore d\'autres membres. Retirez-les d\'abord.');
        }

        $colocation->update(['is_active' => false]);

        return redirect()->route('dashboard')->with('success', 'La colocation a été annulée.');
    }

    /**
     * Member leaves the colocation.
     */
    public function leave(Colocation $colocation)
    {
        $user = Auth::user();

        if (!$colocation->hasMember($user)) {
            abort(403);
        }

        if ($colocation->owner_id === $user->id) {
            return back()->with('error', 'Le propriétaire ne peut pas quitter la colocation. Annulez-la si besoin.');
        }

        DB::transaction(function() use ($colocation, $user) {
            // Calculate total debt only (ignore credits/money owed to the user)
            $totalDebt = DB::table('expense_user')
                ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
                ->where('expenses.colocation_id', $colocation->id)
                ->where('expense_user.user_id', $user->id)
                ->where('expenses.payer_id', '!=', $user->id) // I am debtor
                ->where('expense_user.is_paid', false)
                ->sum('amount_owed');

            // Update reputation based on debt
            if ($totalDebt > 0) {
                $user->decrement('reputation'); // Has debt: -1
            } else {
                $user->increment('reputation'); // No debt: +1
            }

            // If they have debt, it's transferred to owner
            if ($totalDebt > 0) {
                $this->transferDebtToOwner($colocation, $user);
            }

            $colocation->members()->detach($user->id);
        });

        return redirect()->route('dashboard')->with('success', 'Vous avez quitté la colocation. Votre réputation a été mise à jour.');
    }

    /**
     * Owner removes a member.
     */
    public function removeMember(Colocation $colocation, User $member)
    {
        if ($colocation->owner_id !== Auth::id()) {
            abort(403);
        }

        if ($member->id === $colocation->owner_id) {
            return back()->with('error', 'Vous ne pouvez pas vous retirer vous-même.');
        }

        if (!$colocation->hasMember($member)) {
            return back()->with('error', 'Cet utilisateur n\'est pas membre de cette colocation.');
        }

        DB::transaction(function() use ($colocation, $member) {
            // Calculate total debt only
            $totalDebt = DB::table('expense_user')
                ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
                ->where('expenses.colocation_id', $colocation->id)
                ->where('expense_user.user_id', $member->id)
                ->where('expenses.payer_id', '!=', $member->id) // Member is debtor
                ->where('expense_user.is_paid', false)
                ->sum('amount_owed');

            // Update reputation
            if ($totalDebt > 0) {
                $member->decrement('reputation');
            } else {
                $member->increment('reputation');
            }

            // Transfer debt if positive
            if ($totalDebt > 0) {
                $this->transferDebtToOwner($colocation, $member);
            }

            $colocation->members()->detach($member->id);
        });

        return back()->with('success', "Le membre {$member->name} a été retiré. Ses dettes éventuelles ont été transférées au propriétaire.");
    }

    /**
     * Helper to transfer a member's unpaid debts to the owner.
     */
    protected function transferDebtToOwner(Colocation $colocation, User $member)
    {
        $unpaidDebts = DB::table('expense_user')
            ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
            ->where('expenses.colocation_id', $colocation->id)
            ->where('expense_user.user_id', $member->id)
            ->where('expense_user.is_paid', false)
            ->select('expense_user.*')
            ->get();

        foreach ($unpaidDebts as $debt) {
            $ownerRow = DB::table('expense_user')
                ->where('expense_id', $debt->expense_id)
                ->where('user_id', $colocation->owner_id)
                ->first();

            if ($ownerRow) {
                DB::table('expense_user')
                    ->where('expense_id', $debt->expense_id)
                    ->where('user_id', $colocation->owner_id)
                    ->update([
                        'amount_owed' => $ownerRow->amount_owed + $debt->amount_owed
                    ]);
                DB::table('expense_user')->where('id', $debt->id)->delete();
            } else {
                DB::table('expense_user')->where('id', $debt->id)->update(['user_id' => $colocation->owner_id]);
            }
        }
    }
}
