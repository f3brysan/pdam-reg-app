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
        Schema::create('permohonan_billings', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('no_va', 18)->nullable();
            $table->decimal('price', 25, 0)->nullable();
            $table->string('path')->nullable();
            $table->integer('is_valid')->nullable()->default(0);
            $table->dateTime('valid_at')->nullable();
            $table->integer('valid_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_billings');
    }
};
