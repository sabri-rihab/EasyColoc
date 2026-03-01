<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserBanController extends Controller
{
    use \App\Traits\DebtTransferable;

    public function ban(User $user)
    {
        DB::transaction(function() use ($user) {
            $user->update([
                'is_banned' => true,
                'banned_at' => Carbon::now()
            ]);

            // Handle colocation removals
            foreach ($user->colocations as $colocation) {
                $totalDebt = $this->calculateDebt($colocation, $user);

                // Reputation adjustment (forced leave)
                if ($totalDebt > 0) {
                    $user->decrement('reputation');
                }

                if ($colocation->owner_id === $user->id) {
                    // Try to find a new owner (oldest member)
                    $newOwner = $colocation->members()
                        ->where('users.id', '!=', $user->id)
                        ->orderBy('joined_at', 'asc')
                        ->first();

                    if ($newOwner) {
                        // Transfer ownership
                        $colocation->update(['owner_id' => $newOwner->id]);
                        
                        // Transfer debt to new owner
                        $this->transferDebt($colocation, $user, $newOwner->id);
                    } else {
                        // No other members, deactivate coloc
                        $colocation->update(['is_active' => false]);
                    }
                } else {
                    // Regular member: transfer debt to owner
                    $this->transferDebt($colocation, $user, $colocation->owner_id);
                }

                // Remove user from the colocation
                $colocation->members()->detach($user->id);
            }
        });

        return back()->with('success', 'Utilisateur banni et retiré de ses colocations avec succès.');
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