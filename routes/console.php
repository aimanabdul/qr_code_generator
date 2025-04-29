<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\QrCodeModel;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('qr-codes:update-expired', function () {
    QrCodeModel::updateExpiredActivationCodes();
})->purpose('update expired qr code activation code')->daily();
