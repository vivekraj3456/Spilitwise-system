<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');

    Route::resource('groups', GroupController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::post('/groups/{group}/members', [GroupMemberController::class, 'store'])
        ->name('groups.members.store');
    Route::delete('/groups/{group}/members/{user}', [GroupMemberController::class, 'destroy'])
        ->name('groups.members.destroy');

    Route::scopeBindings()->group(function (): void {
        Route::get('/groups/{group}/expenses/create', [ExpenseController::class, 'create'])
            ->name('groups.expenses.create');
        Route::post('/groups/{group}/expenses', [ExpenseController::class, 'store'])
            ->name('groups.expenses.store');
        Route::get('/groups/{group}/expenses/{expense}', [ExpenseController::class, 'show'])
            ->name('groups.expenses.show');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
