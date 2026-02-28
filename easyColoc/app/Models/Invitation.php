<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'colocation_id',
        'inviter_id',
        'email',
        'status',
        'token',
        'accepted_at',
        'expires_at'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            $invitation->token = Str::random(32);
            $invitation->expires_at = now()->addDays(3);
        });
    }

    //Get the colocation that owns the invitation.
    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    //Get the user who sent the invitation.
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    //Check if invitation is expired.
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    //Check if invitation is accepted.
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    //Check if invitation is refused.
    public function isRefused()
    {
        return $this->status === 'refused';
    }
}