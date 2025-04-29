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
        Schema::table('qr_code_models', function (Blueprint $table) {
            $table->string('activation_code')->nullable()->after('is_used');
            $table->boolean('activation_code_is_active')->default(true)->after('activation_code');
            $table->dateTime('activation_code_created_at')->nullable()->after('activation_code_is_active');
            $table->dateTime('activation_code_expired_at')->nullable()->after('activation_code_created_at');
            $table->dateTime('activation_code_used_at')->nullable()->after('activation_code_expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_code_models', function (Blueprint $table) {
            $table->dropColumn('activation_code');
            $table->dropColumn('activation_code_is_active');
            $table->dropColumn('activation_code_created_at');
            $table->dropColumn('activation_code_expired_at');
            $table->dropColumn('activation_code_used_at');
        });
    }
};
