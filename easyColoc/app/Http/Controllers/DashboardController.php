<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasActiveColocation()) {
            $colocation = $user->currentColocation();

            $reputation = $user->reputation ?? 0;
            
            // New balance logic (Credits - Debts)
            $balance = $user->getColocationBalance($colocation);
            
            // For simple stat display: Total this user has paid out (lifetime in this coloc)
            $totalPaid = $colocation->expenses()->where('payer_id', $user->id)->sum('amount');
            
            // Details: People who owe ME
            $whoOwesMe = DB::table('expense_user')
                ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
                ->join('users', 'expense_user.user_id', '=', 'users.id')
                ->where('expenses.colocation_id', $colocation->id)
                ->where('expenses.payer_id', $user->id)
                ->where('expense_user.user_id', '!=', $user->id)
                ->where('expense_user.is_paid', false)
                ->select('users.name as user_name', 'expense_user.amount_owed', 'expenses.title as expense_title')
                ->get();

            // Details: To whom I owe
            $whomIOwe = DB::table('expense_user')
                ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
                ->join('users', 'expenses.payer_id', '=', 'users.id')
                ->where('expenses.colocation_id', $colocation->id)
                ->where('expenses.payer_id', '!=', $user->id)
                ->where('expense_user.user_id', $user->id)
                ->where('expense_user.is_paid', false)
                ->select('users.name as user_name', 'expense_user.amount_owed', 'expenses.title as expense_title')
                ->get();

            $globalExpenses = $colocation->expenses()->sum('amount');
            $recentExpenses = $colocation->expenses()
                ->with('payer')
                ->latest('expense_date')
                ->take(5)
                ->get();
            $members = $colocation->members()->get();

            return view('dashboard.colocation', compact(
                'colocation',
                'reputation',
                'balance',
                'totalPaid',
                'globalExpenses',
                'recentExpenses',
                'members',
                'whoOwesMe',
                'whomIOwe'
            ));
        } else {
            $invitations = $user->pendingInvitations()->with('colocation', 'inviter')->get();
            return view('dashboard.no-colocation', compact('invitations'));
        }
    }
}

