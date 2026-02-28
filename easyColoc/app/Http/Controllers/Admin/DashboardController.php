<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;

class DashboardController extends Controller
{
    // Display the admin dashboard.
    public function index()
    {
        $stats = [
            'total_users' => User::count(), 
            'active_colocations' => Colocation::where('is_active', true)->count(),
            'total_expenses' => Expense::sum('amount'),
            'banned_users' => User::where('is_banned', true)->count(),
        ];
        $users = User::latest()->take(20)->get();
        $colocations = Colocation::with('owner')->latest()->take(10)->get();

        $globalDebts = \Illuminate\Support\Facades\DB::table('expense_user')
            ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
            ->join('users as debtors', 'expense_user.user_id', '=', 'debtors.id')
            ->join('users as payers', 'expenses.payer_id', '=', 'payers.id')
            ->where('expense_user.is_paid', false)
            ->where('expense_user.user_id', '!=', 'expenses.payer_id')
            ->select('debtors.name as debtor_name', 'payers.name as payer_name', 'expense_user.amount_owed', 'expenses.title')
            ->latest('expense_user.id')
            ->take(15)
            ->get();

        return view('admin.dashboard', compact('stats', 'users', 'colocations', 'globalDebts'));
    }
}