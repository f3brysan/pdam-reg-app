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
        Schema::create('permohonan_officers', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('petugas_id')->nullable();
            $table->date('tgl_pasang')->nullable();
            $table->integer('ms_meteran_id')->nullable();
            $table->string('nomor_seri', 50)->nullable();
            $table->integer('is_done')->nullable()->default(0);
            $table->dateTime('done_at')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_officers');
    }
};
