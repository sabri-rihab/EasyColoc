<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserBanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/users/{user}/ban', [UserBanController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [UserBanController::class, 'unban'])->name('users.unban');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Colocations
    Route::resource('colocations', \App\Http\Controllers\ColocationController::class);
    Route::post('colocations/{colocation}/leave', [\App\Http\Controllers\ColocationController::class, 'leave'])->name('colocations.leave');
    Route::delete('colocations/{colocation}/members/{member}', [\App\Http\Controllers\ColocationController::class, 'removeMember'])->name('colocations.members.remove');

    // Invitations
    Route::post('colocations/{colocation}/invitations', [\App\Http\Controllers\InvitationController::class, 'store'])->name('invitations.store');
    Route::post('invitations/{invitation}/accept', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('invitations/{invitation}/reject', [\App\Http\Controllers\InvitationController::class, 'reject'])->name('invitations.reject');

    // Expenses (nested under colocation)
    Route::get('colocations/{colocation}/expenses/create', [\App\Http\Controllers\ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('colocations/{colocation}/expenses', [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('colocations/{colocation}/expenses/{expense}/edit', [\App\Http\Controllers\ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('colocations/{colocation}/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('colocations/{colocation}/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Payments (mark paid / unpaid on expense_user pivot)
    Route::post('colocations/{colocation}/expenses/{expense}/pay/{debtor}', [\App\Http\Controllers\PaymentController::class, 'markPaid'])->name('payments.mark-paid');
    Route::post('colocations/{colocation}/expenses/{expense}/unpay/{debtor}', [\App\Http\Controllers\PaymentController::class, 'markUnpaid'])->name('payments.mark-unpaid');
});

require __DIR__.'/auth.php';
