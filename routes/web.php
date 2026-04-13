<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route;


// forwarding => the forwarding route shoul always be qr/label/{qrlabel} otherwaise all the qr/* won't work
Route::get('/qr/{label}', [QrCodeController::class, 'forwarding'])->name('qr.forwarding'); // Do not change: This route is reserved for forwarding and should never be changed!
Route::post('/qr/activate-by-customer/{id}', [QrCodeController::class, 'activateBycustomer'])->name('qr.activateByCustomer');


// authenticated and verified user's routes
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/', function () {
        return redirect(route('qr.index'));
    });
    
    // dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // qr codes
    Route::get('/qrcode/create', [QrCodeController::class, 'create'])->name('qr.create');
    Route::post('/qrcode/store', [QrCodeController::class, 'store'])->name('qr.store');
    Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qr.index');
    Route::get('/qrcode/download/{id}', [QrCodeController::class, 'download'])->name('qr.download');
    Route::post('/qrcode/update-status', [QrCodeController::class, 'updateStatus'])->name('qr.update-status');
    Route::get('/qrcode/update/{id}', [QrCodeController::class, 'edit'])->name('qr.edit');
    Route::post('/qrcode/update/{id}', [QrCodeController::class, 'update'])->name('qr.update');
    Route::get('/qrcode/getactivationcode/{id}', [QrCodeController::class, 'getActivationCode'])->name('qr.getActivationCode');

    // set up
    Route::get('/qr/setup/{label}', [QrCodeController::class, 'setup'])->name('qr.setup');
    Route::post('/qr/setup/{label}', [QrCodeController::class, 'activate'])->name('qr.activate');


});

// profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
