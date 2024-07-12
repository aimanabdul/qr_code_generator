<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/qr/create', [QrCodeController::class, 'create'])->name('qr.create');
Route::post('/qr/store', [QrCodeController::class, 'store'])->name('qr.store');
Route::get('/qr', [QrCodeController::class, 'index'])->name('qr.index');
Route::get('/qr/download/{id}/{format}', [QrCodeController::class, 'download'])->name('qr.download');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
