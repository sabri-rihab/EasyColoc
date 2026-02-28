<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'adresse',
        'owner_id',
        'invitation_code',
        'is_active'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($colocation) {
            $colocation->invitation_code = strtoupper(Str::random(8));
        });
    }

    //Get the owner of the colocation.
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    //Get all members of the colocation.
    public function members()
    {
        return $this->belongsToMany(User::class, 'colocations_user')
                    ->withPivot('is_owner', 'joined_at')
                    ->withTimestamps();
    }

    //Get all expenses for this colocation.
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    //Get all invitations for this colocation.
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    //Check if user is a member.
    public function hasMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    //Check if user is the owner.
    public function isOwner(User $user)
    {
        return $this->owner_id === $user->id;
    }
}