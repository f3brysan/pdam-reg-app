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
        Schema::create('permohonan_dokumen_transactions', function (Blueprint $table) {
            $table->integer('permohonan_transaction_id');
            $table->integer('ms_jenis_dokumen_id');
            $table->string('path')->nullable();
            $table->decimal('size', 50, 0)->nullable();
            $table->timestamps();

            $table->primary(['permohonan_transaction_id', 'ms_jenis_dokumen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_dokumen_transactions');
    }
};
