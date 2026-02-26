<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'reputation',
        'is_global_admin',
        'is_banned',
        'banned_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_global_admin' => 'boolean',
            'is_banned' => 'boolean',
        ];
    }



    public function colocations()
    {
        return $this->belongsToMany(Colocation::class)
                    ->withPivot('is_owner', 'joined_at')
                    ->withTimestamps();
    }


    //Get the colocation where the user is owner.
    public function ownedColocation()
    {
        return $this->hasOne(Colocation::class, 'owner_id');
    }

    //Check if user has an active colocation.
    public function hasActiveColocation()
    {
        return $this->colocations()
                    ->where('is_active', true)
                    ->exists();
    }

    //Get user's current active colocation.
    public function currentColocation()
    {
        return $this->colocations()
                    ->where('is_active', true)
                    ->first();
    }

    //Expenses owed by this user.
    public function expensesOwed()
    {
        return $this->belongsToMany(Expense::class, 'expense_user')
                    ->withPivot('amount_owed', 'is_paid', 'paid_at')
                    ->withTimestamps();
    }

    //Expenses paid by this user.
    public function expensesPaid()
    {
        return $this->hasMany(Expense::class, 'payer_id');
    }

}
