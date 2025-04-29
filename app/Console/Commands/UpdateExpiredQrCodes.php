<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QrCodeModel;

class UpdateExpiredQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr-codes:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired QR code activation codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // is scheudeld to run in the keurnel
        QrCodeModel::updateExpiredActivationCodes();
        $this->info('Expired QR codes have been updated.');
    }
}
