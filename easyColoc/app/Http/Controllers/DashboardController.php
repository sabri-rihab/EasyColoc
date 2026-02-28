<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedMonth = $request->query('month');

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

            $globalExpensesQuery = $colocation->expenses();
            $recentExpensesQuery = $colocation->expenses()->with('payer', 'category_rel');

            if ($selectedMonth) {
                $globalExpensesQuery->whereRaw("DATE_FORMAT(expense_date, '%Y-%m') = ?", [$selectedMonth]);
                $recentExpensesQuery->whereRaw("DATE_FORMAT(expense_date, '%Y-%m') = ?", [$selectedMonth]);
            }

            $globalExpenses = $globalExpensesQuery->sum('amount');
            $recentExpenses = $recentExpensesQuery->latest('expense_date')->take(10)->get();
            $members = $colocation->members()->get();
            $categories = \App\Models\Category::whereNull('colocation_id')
                ->orWhere('colocation_id', $colocation->id)
                ->orderByRaw('colocation_id IS NULL ASC')
                ->orderBy('name')
                ->get();

            // Available months for filter
            $availableMonths = $colocation->expenses()
                ->selectRaw("DATE_FORMAT(expense_date, '%Y-%m') as month")
                ->groupByRaw("DATE_FORMAT(expense_date, '%Y-%m')")
                ->orderByRaw("DATE_FORMAT(expense_date, '%Y-%m') desc")
                ->pluck('month');

            return view('dashboard.colocation', compact(
                'colocation',
                'reputation',
                'balance',
                'totalPaid',
                'globalExpenses',
                'recentExpenses',
                'members',
                'whoOwesMe',
                'whomIOwe',
                'availableMonths',
                'selectedMonth',
                'categories'
            ));
        } else {
            $invitations = $user->pendingInvitations()
                ->where('status', 'pending')
                ->with('colocation', 'inviter')
                ->get();
            return view('dashboard.no-colocation', compact('invitations'));
        }
    }
}

