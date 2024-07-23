<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qr_code_models', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('foreground_color')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_id')->nullable();
            $table->boolean('is_downloaded')->default(false);
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_code_models');
    }
};
