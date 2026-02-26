<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserBanController extends Controller
{
    public function ban(User $user)
    {
        $user->update([
            'is_banned' => true,
            'banned_at' => Carbon::now()
        ]);

        return back()->with('success', 'Utilisateur banni avec succès.');
    }

    public function unban(User $user)
    {
        $user->update([
            'is_banned' => false,
            'banned_at' => null
        ]);

        return back()->with('success', 'Utilisateur réactivé avec succès.');
    }
}