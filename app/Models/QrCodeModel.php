<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCodeModel extends Model
{
    use HasFactory;

    public static function updateExpiredActivationCodes()
    {
        // Zoek alle QR-codes waarvan de activatiecode is verlopen
        // exexuted with command: UpdateExpiredQrCodes door qr-codes:update-expired uit te voeren
        $expiredQrCodes = self::where('activation_code_is_active', true)
        ->where('activation_code_expired_at', '<', now())
        ->get();

        foreach ($expiredQrCodes as $qrCode) {
            $qrCode->activation_code_is_active = false;
            $qrCode->save();
        }
    }
}
