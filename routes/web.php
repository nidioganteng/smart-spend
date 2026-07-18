<?php

use App\Http\Controllers\BudgetPlanController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:head_division'])->group(function () {
    Route::post('budget-plan/{budgetPlan}/submit', [BudgetPlanController::class, 'submit'])->name('budget-plan.submit');
    Route::get('budget-plan/create', [BudgetPlanController::class, 'create'])->name('budget-plan.create');
    Route::post('budget-plan', [BudgetPlanController::class, 'store'])->name('budget-plan.store');
    Route::get('budget-plan/{budgetPlan}/edit', [BudgetPlanController::class, 'edit'])->name('budget-plan.edit');
    Route::put('budget-plan/{budgetPlan}', [BudgetPlanController::class, 'update'])->name('budget-plan.update');
    Route::delete('budget-plan/{budgetPlan}', [BudgetPlanController::class, 'destroy'])->name('budget-plan.destroy');
});

Route::middleware(['auth', 'role:finance_staff'])->group(function () {
    Route::post('budget-plan/{budgetPlan}/finance-review', [BudgetPlanController::class, 'financeReview'])->name('budget-plan.finance-review');
});

Route::middleware(['auth', 'role:leader'])->group(function () {
    Route::post('budget-plan/{budgetPlan}/leader-review', [BudgetPlanController::class, 'leaderReview'])->name('budget-plan.leader-review');
});

Route::middleware(['auth', 'role:admin,finance_staff,leader'])->group(function () {
    Route::get('wallet', [WalletController::class, 'index'])->name('wallet.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('budget-plan', [BudgetPlanController::class, 'index'])->name('budget-plan.index');
    Route::get('budget-plan/{budgetPlan}', [BudgetPlanController::class, 'show'])->name('budget-plan.show');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('divisi', DivisiController::class);
    Route::post('divisi/{divisi}/bind-rfid', [DivisiController::class, 'bindRfid'])->name('divisi.bind-rfid');
    Route::delete('divisi/{divisi}/unbind-rfid', [DivisiController::class, 'unbindRfid'])->name('divisi.unbind-rfid');

    Route::resource('vendor', VendorController::class);
    Route::post('vendor/{vendor}/generate-qr', [VendorController::class, 'generateQr'])->name('vendor.generate-qr');
});

require __DIR__.'/auth.php';
