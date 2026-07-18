<?php

use App\Http\Controllers\DivisiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;
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

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('divisi', DivisiController::class);
    Route::post('divisi/{divisi}/bind-rfid', [DivisiController::class, 'bindRfid'])->name('divisi.bind-rfid');
    Route::delete('divisi/{divisi}/unbind-rfid', [DivisiController::class, 'unbindRfid'])->name('divisi.unbind-rfid');

    Route::resource('vendor', VendorController::class);
    Route::post('vendor/{vendor}/generate-qr', [VendorController::class, 'generateQr'])->name('vendor.generate-qr');
});

require __DIR__.'/auth.php';
