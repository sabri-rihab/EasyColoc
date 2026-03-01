<?php

namespace App\Traits;

use App\Models\Colocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait DebtTransferable
{
    /**
     * Helper to transfer a member's unpaid debts to another user (usually the owner).
     */
    protected function transferDebt(Colocation $colocation, User $fromUser, int $toUserId)
    {
        $unpaidDebts = DB::table('expense_user')
            ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
            ->where('expenses.colocation_id', $colocation->id)
            ->where('expense_user.user_id', $fromUser->id)
            ->where('expense_user.is_paid', false)
            ->select('expense_user.*')
            ->get();

        foreach ($unpaidDebts as $debt) {
            $toUserRow = DB::table('expense_user')
                ->where('expense_id', $debt->expense_id)
                ->where('user_id', $toUserId)
                ->first();

            if ($toUserRow) {
                DB::table('expense_user')
                    ->where('expense_id', $debt->expense_id)
                    ->where('user_id', $toUserId)
                    ->update([
                        'amount_owed' => $toUserRow->amount_owed + $debt->amount_owed
                    ]);
                DB::table('expense_user')->where('id', $debt->id)->delete();
            } else {
                DB::table('expense_user')->where('id', $debt->id)->update(['user_id' => $toUserId]);
            }
        }
    }

    /**
     * Helper to calculate a member's total unpaid debt in a colocation.
     */
    protected function calculateDebt(Colocation $colocation, User $user)
    {
        return DB::table('expense_user')
            ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
            ->where('expenses.colocation_id', $colocation->id)
            ->where('expense_user.user_id', $user->id)
            ->where('expenses.payer_id', '!=', $user->id)
            ->where('expense_user.is_paid', false)
            ->sum('amount_owed');
    }
}
