<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();

        if($user->hasActiveColocation()){
            $colocation = $user->currentColocation();
            
            $reputation = $user->reputation;
            $globalExpenses = 0;
            $recentExpenses = collect([]); 
            $members = $colocation->members()->get();
            
            return view('dashboard.colocation', compact('colocation', 'reputation', 'globalExpenses', 'recentExpenses', 'members'));
        } else {
            return view('dashboard.no-colocation');
        }
    }
}

