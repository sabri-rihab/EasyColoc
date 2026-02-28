<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;

class InvitationController extends Controller
{
    /**
     * Store a newly created invitation in storage.
     */
    public function store(Request $request, Colocation $colocation)
    {
        if ($colocation->owner_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if user is already a member of this colocation
        $targetUser = User::where('email', $request->email)->first();
        if ($targetUser && $colocation->hasMember($targetUser)) {
            return back()->with('error', 'Cet utilisateur est déjà membre de cette colocation.');
        }

        // Check if user already has an active colocation
        if ($targetUser && $targetUser->hasActiveColocation()) {
            return back()->with('error', 'Cet utilisateur a déjà une colocation active.');
        }

        // Create the invitation (token and expiry handled by model boot)
        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'inviter_id' => Auth::id(),
            'email' => $request->email,
        ]);

        // Send email
        Mail::to($request->email)->send(new InvitationMail($invitation));

        return back()->with('success', "Invitation envoyée à {$request->email}.");
    }

    /**
     * Accept the invitation.
     */
    public function accept(Invitation $invitation)
    {
        $user = Auth::user();

        if ($invitation->email !== $user->email) {
            abort(403, 'Cette invitation ne vous est pas destinée.');
        }

        if ($invitation->isExpired()) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation a expiré.');
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation a déjà été acceptée.');
        }

        if ($invitation->isRefused()) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation a été refusée.');
        }

        if ($user->hasActiveColocation()) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une colocation active.');
        }

        // Mark as accepted
        $invitation->update([
            'accepted_at' => now(),
            'status' => 'accepted'
        ]);

        // Add user to colocation
        $invitation->colocation->members()->attach($user->id, [
            'is_owner' => false,
            'joined_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', "Vous avez rejoint la colocation {$invitation->colocation->name} !");
    }

    /**
     * Reject the invitation.
     */
    public function reject(Invitation $invitation)
    {
        $user = Auth::user();

        if ($invitation->email !== $user->email) {
            abort(403);
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('dashboard')->with('error', 'Impossible de refuser une invitation déjà acceptée.');
        }

        $invitation->update(['status' => 'refused']);

        return redirect()->route('dashboard')->with('success', 'Invitation refusée.');
    }
}
