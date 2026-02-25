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
            'active_colocations' => 0,
            'total_expenses' => 0,
            'banned_users' => User::where('is_banned', true)->count(),
        ];
        // latest()  take(10)
        $users = User::get();

        return view('admin.dashboard', compact('stats', 'users'));
    }
}