<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'colocation_id',
        'payer_id',
        'title',
        'description',
        'amount',
        'category',
        'expense_date',
        'is_settled'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'is_settled' => 'boolean',
        'amount' => 'decimal:2'
    ];

    //Get the colocation that owns the expense.
    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    //Get the user who paid the expense.
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    //Get the users who owe for this expense.
    public function debtors()
    {
        return $this->belongsToMany(User::class, 'expense_user')
                    ->withPivot('amount_owed', 'is_paid', 'paid_at')
                    ->withTimestamps();
    }

    //Calculate how much each member owes.

    public function calculateSplit()
    {
        $memberCount = $this->colocation->members()->count();
        return $this->amount / $memberCount;
    }
}