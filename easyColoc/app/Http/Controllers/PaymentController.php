<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Mark a specific debt as paid (debtor marks their own share, or owner marks for anyone).
     */
    public function markPaid(Request $request, Colocation $colocation, Expense $expense, User $debtor)
    {
        if ($expense->colocation_id !== $colocation->id) {
            abort(404);
        }

        $user = Auth::user();

        // Only the debtor themselves or the colocation owner can mark as paid
        $isOwner  = $colocation->owner_id === $user->id;
        $isSelf   = $debtor->id === $user->id;

        if (!$isOwner && !$isSelf) {
            abort(403, 'Vous ne pouvez pas marquer ce paiement.');
        }

        // Update in pivot, increment reputation, and check if expense is settled
        DB::transaction(function() use ($expense, $debtor) {
            $expense->debtors()->updateExistingPivot($debtor->id, [
                'is_paid' => true,
                'paid_at' => now(),
            ]);

            // Increment reputation for being a good payer
            $debtor->increment('reputation');

            // Check if all debtors have paid
            $unpaidCount = $expense->debtors()->wherePivot('is_paid', false)->count();
            if ($unpaidCount === 0) {
                $expense->update(['is_settled' => true]);
            }
        });

        return back()->with('success', "Paiement de {$debtor->name} marqué comme effectué (+1 Rép).");
    }

    /**
     * Mark a specific debt as unpaid (owner only, or debtor resets their own).
     */
    public function markUnpaid(Request $request, Colocation $colocation, Expense $expense, User $debtor)
    {
        if ($expense->colocation_id !== $colocation->id) {
            abort(404);
        }

        $user = Auth::user();
        $isOwner = $colocation->owner_id === $user->id;
        $isSelf  = $debtor->id === $user->id;

        if (!$isOwner && !$isSelf) {
            abort(403, 'Vous ne pouvez pas modifier ce paiement.');
        }

        // Can't unmark the payer themselves (they paid by definition)
        if ($debtor->id === $expense->payer_id) {
            return back()->with('error', 'Le payeur ne peut pas être marqué comme non-payé.');
        }

        // Update in pivot and decrement reputation in a transaction
        DB::transaction(function() use ($expense, $debtor) {
            $expense->debtors()->updateExistingPivot($debtor->id, [
                'is_paid' => false,
                'paid_at' => null,
            ]);

            // Decrement reputation if payment is cancelled
            $debtor->decrement('reputation');

            // Force expense to unsettled
            $expense->update(['is_settled' => false]);
        });

        return back()->with('success', "Paiement de {$debtor->name} réinitialisé (-1 Rép).");
    }
}
