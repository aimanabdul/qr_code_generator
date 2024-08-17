<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route;

// forwarding
Route::get('/qr/{label}', [QrCodeController::class, 'forwarding'])->name('qr.forwarding');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/', function () {
        return redirect(route('qr.index'));
    });
    // dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // qr codes
    Route::get('/qr/create', [QrCodeController::class, 'create'])->name('qr.create');
    Route::post('/qr/store', [QrCodeController::class, 'store'])->name('qr.store');
    Route::get('/qr', [QrCodeController::class, 'index'])->name('qr.index');
    Route::get('/qr/download/{id}', [QrCodeController::class, 'download'])->name('qr.download');
    Route::post('/qr/update-status', [QrCodeController::class, 'updateStatus'])->name('qr.update-status');
    Route::get('/qr/update/{id}', [QrCodeController::class, 'edit'])->name('qr.edit');
    Route::post('/qr/update/{id}', [QrCodeController::class, 'update'])->name('qr.update');
});

// profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
