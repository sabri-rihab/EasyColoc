# EasyColoc - System Flow Documentation

## Table of Contents
1. [Creating a Colocation](#1-creating-a-colocation)
2. [Inviting a Member](#2-inviting-a-member)
3. [Removing a Member](#3-removing-a-member)
4. [Member Leaves Colocation](#4-member-leaves-colocation)
5. [Creating Expenses](#5-creating-expenses)
6. [Banning a User](#6-banning-a-user)
7. [Middleware System](#7-middleware-system)
8. [Database Relationships](#8-database-relationships)

---

## 1. Creating a Colocation

### User Flow
1. User clicks "Nouvelle colocation" button
2. Modal opens with form
3. User fills name and description
4. Submits form

### Code Flow

**Route:** `POST /colocations` → `UserDashboardController@storeColocation`

**Controller:** `app/Http/Controllers/UserDashboardController.php`
```php
public function storeColocation(Request $request)
{
    // 1. Validate input
    $request->validate(['name' => 'required|string|max:255']);
    
    // 2. Create colocation record in 'colocations' table
    $colocation = \App\Models\Colocation::create([
        'name' => $request->name,
        'description' => $request->description,
        'owner_id' => auth()->id(),
        'is_active' => true,
    ]);
    
    // 3. Attach creator as member in 'colocations_user' pivot table
    $colocation->members()->attach(auth()->id(), [
        'is_owner' => true,
        'joined_at' => now()
    ]);
    
    // 4. Redirect to colocation page
    return redirect()->route('colocation')->with('success', 'Colocation créée avec succès !');
}
```

**Model Hook:** `app/Models/Colocation.php`
```php
protected static function boot()
{
    parent::boot();
    
    // Automatically generate invitation code when creating
    static::creating(function ($colocation) {
        $colocation->invitation_code = strtoupper(Str::random(8));
    });
}
```

**Database Changes:**
- **colocations table:** New row with name, description, owner_id, invitation_code, is_active=1
- **colocations_user table:** New row linking user to colocation with is_owner=1

**View:** Redirects to `resources/views/colocation.blade.php` which shows active colocation management

---

## 2. Inviting a Member

### User Flow
1. Owner clicks "Inviter un membre" button
2. Modal shows invitation code
3. Owner copies and shares code
4. New user enters code (not implemented in current code)

### Code Flow

**Current Implementation:**
- Invitation code is generated automatically when colocation is created
- Code is displayed in modal: `resources/views/partials/dashboard-modals.blade.php`
- Actual invitation acceptance logic is not yet implemented

**Expected Flow (when implemented):**
1. New user receives invitation code
2. Enters code in join form
3. System validates code exists and colocation is active
4. Adds user to `colocations_user` pivot table
5. Sets `is_owner = false` and `joined_at = now()`

---

## 3. Removing a Member

### User Flow
1. Owner views member list
2. Clicks remove button on member
3. System checks for unpaid debts
4. Transfers debts to owner
5. Removes member from colocation

### Code Flow

**Expected Implementation (not in current code):**
```php
public function removeMember(Colocation $colocation, User $user)
{
    DB::transaction(function() use ($colocation, $user) {
        // 1. Calculate member's unpaid debts
        $totalDebt = $this->calculateDebt($colocation, $user);
        
        // 2. Transfer unpaid debts to owner
        $this->transferDebt($colocation, $user, $colocation->owner_id);
        
        // 3. Adjust reputation
        if ($totalDebt > 0) {
            $user->decrement('reputation'); // Left with debt
        } else {
            $user->increment('reputation'); // Left clean
        }
        
        // 4. Remove from pivot table
        $colocation->members()->detach($user->id);
    });
}
```

**Trait Used:** `app/Traits/DebtTransferable.php`

**transferDebt() method:**
```php
protected function transferDebt(Colocation $colocation, User $fromUser, int $toUserId)
{
    // 1. Find all unpaid debts of the leaving user
    $unpaidDebts = DB::table('expense_user')
        ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
        ->where('expenses.colocation_id', $colocation->id)
        ->where('expense_user.user_id', $fromUser->id)
        ->where('expense_user.is_paid', false)
        ->get();
    
    foreach ($unpaidDebts as $debt) {
        // 2. Check if target user already has a row for this expense
        $toUserRow = DB::table('expense_user')
            ->where('expense_id', $debt->expense_id)
            ->where('user_id', $toUserId)
            ->first();
        
        if ($toUserRow) {
            // 3a. Add debt amount to existing row
            DB::table('expense_user')
                ->where('expense_id', $debt->expense_id)
                ->where('user_id', $toUserId)
                ->update([
                    'amount_owed' => $toUserRow->amount_owed + $debt->amount_owed
                ]);
            // Delete old row
            DB::table('expense_user')->where('id', $debt->id)->delete();
        } else {
            // 3b. Transfer the row to new user
            DB::table('expense_user')
                ->where('id', $debt->id)
                ->update(['user_id' => $toUserId]);
        }
    }
}
```

**calculateDebt() method:**
```php
protected function calculateDebt(Colocation $colocation, User $user)
{
    // Sum all unpaid amounts where:
    // - Expense belongs to this colocation
    // - User owes the money (not the payer)
    // - Not yet paid
    return DB::table('expense_user')
        ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
        ->where('expenses.colocation_id', $colocation->id)
        ->where('expense_user.user_id', $user->id)
        ->where('expenses.payer_id', '!=', $user->id)
        ->where('expense_user.is_paid', false)
        ->sum('amount_owed');
}
```

---

## 4. Member Leaves Colocation

### User Flow
1. Member clicks "Quitter" button
2. Confirmation dialog appears
3. Member confirms
4. System processes departure

### Code Flow

**Route:** `POST /colocations/{colocation}/leave` → `UserDashboardController@leaveColocation`

**Controller:** `app/Http/Controllers/UserDashboardController.php`
```php
public function leaveColocation(\App\Models\Colocation $colocation)
{
    // 1. Remove user from colocation
    $colocation->members()->detach(auth()->id());
    
    // 2. Check if colocation is now empty
    if ($colocation->members()->count() === 0) {
        // 3. Deactivate colocation if no members left
        $colocation->update(['is_active' => false]);
    }
    
    // 4. Redirect back
    return redirect()->route('colocation')->with('success', 'Vous avez quitté la colocation.');
}
```

**Note:** Current implementation doesn't handle debt transfer. Should use same logic as removing member.

**Database Changes:**
- **colocations_user table:** Row deleted for this user
- **colocations table:** If no members left, is_active set to 0

---

## 5. Creating Expenses

### User Flow
1. User clicks "Nouvelle dépense" button
2. Modal opens with form
3. User fills: title, category, amount, payer, date
4. Submits form
5. Expense is split equally among all members

### Code Flow

**Route:** `POST /expenses` → `UserDashboardController@storeExpense`

**Controller:** `app/Http/Controllers/UserDashboardController.php`
```php
public function storeExpense(Request $request)
{
    // 1. Validate input
    $request->validate([
        'title' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'expense_date' => 'required|date',
        'category' => 'required|string',
    ]);
    
    // 2. Get user's active colocation
    $colocation = auth()->user()->currentColocation();
    
    if (!$colocation) {
        return back()->with('error', 'Aucune colocation active.');
    }
    
    // 3. Create expense record
    $expense = $colocation->expenses()->create([
        'payer_id' => auth()->id(),
        'title' => $request->title,
        'amount' => $request->amount,
        'expense_date' => $request->expense_date,
        'category' => $request->category,
    ]);
    
    // 4. Split expense among members (expected logic, not in current code)
    $members = $colocation->members;
    $sharePerMember = $expense->amount / $members->count();
    
    foreach ($members as $member) {
        if ($member->id !== $expense->payer_id) {
            // Create debt record for each member except payer
            DB::table('expense_user')->insert([
                'expense_id' => $expense->id,
                'user_id' => $member->id,
                'amount_owed' => $sharePerMember,
                'is_paid' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    return back()->with('success', 'Dépense ajoutée !');
}
```

**Database Changes:**
- **expenses table:** New row with title, amount, payer_id, colocation_id, category, expense_date
- **expense_user table:** One row per member (except payer) with amount_owed = total/member_count

**Balance Calculation (in view):**
```php
// For each member, calculate balance
foreach ($members as $member) {
    $balances[$member->id] = 0;
}

// For each expense
foreach ($colocation->expenses as $expense) {
    $share = $expense->amount / $n; // n = member count
    
    foreach ($members as $member) {
        if ($member->id === $expense->payer_id) {
            // Payer gets credit (paid for others)
            $balances[$member->id] += ($expense->amount - $share);
        } else {
            // Others get debt
            $balances[$member->id] -= $share;
        }
    }
}
```

**Who Owes Whom Logic:**
```php
// Separate members into debtors and creditors
$debtors = []; // negative balance
$creditors = []; // positive balance

foreach ($members as $m) {
    if ($balances[$m->id] < -0.01) {
        $debtors[] = ['name' => $m->name, 'balance' => $balances[$m->id]];
    } elseif ($balances[$m->id] > 0.01) {
        $creditors[] = ['name' => $m->name, 'balance' => $balances[$m->id]];
    }
}

// Match debtors with creditors
foreach ($debtors as $debtor) {
    foreach ($creditors as $creditor) {
        if ($debtor['balance'] >= 0) break;
        if ($creditor['balance'] <= 0) continue;
        
        $amount = min(abs($debtor['balance']), $creditor['balance']);
        $owes[] = [
            'from' => $debtor['name'],
            'to' => $creditor['name'],
            'amount' => $amount
        ];
        
        $debtor['balance'] += $amount;
        $creditor['balance'] -= $amount;
    }
}
```

---

## 6. Banning a User

### User Flow
1. Admin views user list in admin dashboard
2. Clicks "Bannir" button on user
3. System processes ban with all checks

### Code Flow

**Route:** `POST /admin/users/{user}/ban` → `Admin\UserBanController@ban`

**Middleware:** `auth` (must be logged in)

**Controller:** `app/Http/Controllers/Admin/UserBanController.php`
```php
public function ban(User $user)
{
    DB::transaction(function() use ($user) {
        // 1. Mark user as banned
        $user->update([
            'is_banned' => true,
            'banned_at' => Carbon::now()
        ]);
        
        // 2. Handle each colocation the user is in
        foreach ($user->colocations as $colocation) {
            // 3. Calculate user's total debt
            $totalDebt = $this->calculateDebt($colocation, $user);
            
            // 4. Adjust reputation based on debt
            if ($totalDebt > 0) {
                $user->decrement('reputation'); // Banned with debt = bad
            } else {
                $user->increment('reputation'); // Banned but clean = neutral
            }
            
            // 5. Check if user is owner
            if ($colocation->owner_id === $user->id) {
                // 6a. Find new owner (oldest member)
                $newOwner = $colocation->members()
                    ->where('users.id', '!=', $user->id)
                    ->orderBy('joined_at', 'asc')
                    ->first();
                
                if ($newOwner) {
                    // 7a. Transfer ownership
                    $colocation->update(['owner_id' => $newOwner->id]);
                    
                    // 8a. Transfer debts to new owner
                    $this->transferDebt($colocation, $user, $newOwner->id);
                } else {
                    // 7b. No members left, deactivate colocation
                    $colocation->update(['is_active' => false]);
                }
            } else {
                // 6b. Regular member: transfer debt to current owner
                $this->transferDebt($colocation, $user, $colocation->owner_id);
            }
            
            // 9. Remove user from colocation
            $colocation->members()->detach($user->id);
        }
    });
    
    return back()->with('success', 'Utilisateur banni et retiré de ses colocations avec succès.');
}
```

**Unban Flow:**
```php
public function unban(User $user)
{
    // Simply remove ban flags
    $user->update([
        'is_banned' => false,
        'banned_at' => null
    ]);
    
    return back()->with('success', 'Utilisateur réactivé avec succès.');
}
```

**Database Changes:**
- **users table:** is_banned=1, banned_at=timestamp, reputation adjusted
- **colocations table:** owner_id may change, is_active may change to 0
- **colocations_user table:** User removed from all colocations
- **expense_user table:** Debts transferred to new owner/existing owner

---

## 7. Middleware System

### AdminMiddleware

**File:** `app/Http/Middleware/AdminMiddleware.php`

**Purpose:** Protect admin routes from non-admin users

**Logic:**
```php
public function handle(Request $request, Closure $next): Response
{
    // 1. Check if user is logged in AND is admin
    if(Auth::check() && Auth::user()->is_global_admin) {
        // 2. Allow access
        return $next($request);
    }
    
    // 3. Redirect non-admins to dashboard
    return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
}
```

**Used On:**
- `GET /admin/dashboard`
- `POST /admin/users/{user}/ban`
- `POST /admin/users/{user}/unban`

**Registration:** `app/Http/Kernel.php` or `bootstrap/app.php`

### Auth Middleware (Laravel Built-in)

**Purpose:** Ensure user is logged in

**Used On:**
- All `/dashboard` routes
- All `/colocation` routes
- All `/expenses` routes
- All `/admin` routes

### Verified Middleware (Laravel Built-in)

**Purpose:** Ensure email is verified

**Used On:**
- `/dashboard` route
- `/colocation` route

---

## 8. Database Relationships

### belongsToMany Relationships

#### User ↔ Colocation
**Pivot Table:** `colocations_user`

**User Model:**
```php
public function colocations()
{
    return $this->belongsToMany(Colocation::class, 'colocations_user')
                ->withPivot('is_owner', 'joined_at')
                ->withTimestamps();
}
```

**Colocation Model:**
```php
public function members()
{
    return $this->belongsToMany(User::class, 'colocations_user')
                ->withPivot('is_owner', 'joined_at')
                ->withTimestamps();
}
```

**Pivot Columns:**
- `user_id` - Foreign key to users
- `colocation_id` - Foreign key to colocations
- `is_owner` - Boolean, true for colocation owner
- `joined_at` - Date when user joined
- `created_at`, `updated_at` - Timestamps

#### User ↔ Expense
**Pivot Table:** `expense_user`

**User Model:**
```php
public function expensesOwed()
{
    return $this->belongsToMany(Expense::class, 'expense_user')
                ->withPivot('amount_owed', 'is_paid', 'paid_at')
                ->withTimestamps();
}
```

**Pivot Columns:**
- `user_id` - Foreign key to users (who owes)
- `expense_id` - Foreign key to expenses
- `amount_owed` - Decimal, how much this user owes
- `is_paid` - Boolean, payment status
- `paid_at` - Timestamp when paid
- `created_at`, `updated_at` - Timestamps

### Other Relationships

#### User → Colocation (hasOne)
```php
public function ownedColocation()
{
    return $this->hasOne(Colocation::class, 'owner_id');
}
```

#### User → Expense (hasMany)
```php
public function expensesPaid()
{
    return $this->hasMany(Expense::class, 'payer_id');
}
```

#### Colocation → User (belongsTo)
```php
public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}
```

#### Colocation → Expense (hasMany)
```php
public function expenses()
{
    return $this->hasMany(Expense::class);
}
```

---

## 9. Helper Methods

### User Model

#### hasActiveColocation()
```php
public function hasActiveColocation()
{
    // Check if user is member of any active colocation
    return $this->colocations()
                ->where('is_active', true)
                ->exists();
}
```

#### currentColocation()
```php
public function currentColocation()
{
    // Get user's first active colocation
    return $this->colocations()
                ->where('is_active', true)
                ->first();
}
```

#### getColocationBalance()
```php
public function getColocationBalance($colocation)
{
    // Calculate net balance (what others owe me - what I owe)
    
    // Credits: Money others owe me
    $credits = DB::table('expense_user')
        ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
        ->where('expenses.colocation_id', $colocation->id)
        ->where('expenses.payer_id', $this->id) // I paid
        ->where('expense_user.user_id', '!=', $this->id) // Others owe
        ->where('expense_user.is_paid', false)
        ->sum('amount_owed');
    
    // Debts: Money I owe others
    $debts = DB::table('expense_user')
        ->join('expenses', 'expense_user.expense_id', '=', 'expenses.id')
        ->where('expenses.colocation_id', $colocation->id)
        ->where('expenses.payer_id', '!=', $this->id) // Someone else paid
        ->where('expense_user.user_id', $this->id) // I owe
        ->where('expense_user.is_paid', false)
        ->sum('amount_owed');
    
    return round($credits - $debts, 2);
}
```

### Colocation Model

#### hasMember()
```php
public function hasMember(User $user)
{
    return $this->members()->where('user_id', $user->id)->exists();
}
```

#### isOwner()
```php
public function isOwner(User $user)
{
    return $this->owner_id === $user->id;
}
```

---

## 10. View Routing Logic

### Dashboard Route Decision
```php
public function index(Request $request)
{
    $user = auth()->user();
    
    // Admin check
    if ($user->is_global_admin) {
        return redirect()->route('admin.dashboard');
    }
    
    // Regular user dashboard
    return view('user-dashboard', compact('stats'));
}
```

### Colocation Route Decision
```php
public function colocation(Request $request)
{
    $user = auth()->user();
    $colocation = $user->currentColocation();
    
    // No colocation check
    if (!$colocation) {
        return view('no-colocation', compact('stats'));
    }
    
    // Has colocation - show management view
    return view('colocation', compact('stats', 'colocation', 'expenses', 'members', 'owes', 'balances'));
}
```

**Views:**
- `resources/views/user-dashboard.blade.php` - Simple dashboard for regular users
- `resources/views/no-colocation.blade.php` - Create colocation page
- `resources/views/colocation.blade.php` - Active colocation management
- `resources/views/admin/dashboard.blade.php` - Admin user management

---

## Summary

The EasyColoc system manages shared living expenses through:

1. **Colocations** - Groups of users sharing expenses
2. **Expenses** - Costs split equally among members
3. **Debts** - Tracked in expense_user pivot table
4. **Ownership** - One owner per colocation with special privileges
5. **Reputation** - Adjusted based on payment behavior
6. **Admin Controls** - Ban users with automatic debt transfer

Key principles:
- All debt transfers happen in database transactions
- Debts always transfer to owner (or new owner)
- Empty colocations are deactivated
- Reputation reflects financial responsibility
- Admins can manage users but not colocations directly
