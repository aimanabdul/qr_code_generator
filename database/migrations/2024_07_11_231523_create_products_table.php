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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_id')->constrained('colors');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('status_id')->nullable()->constrained('statuses');
            $table->string('name');
            $table->string('ean')->nullable();
            $table->string('description');
            $table->float('price')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
