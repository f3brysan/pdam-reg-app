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
        Schema::create('permohonan_transactions', function (Blueprint $table) {
            $table->integer('id');
            $table->string('no_register')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama')->nullable();
            $table->string('alamat')->nullable();
            $table->integer('ms_pekerjaan_id')->nullable();
            $table->string('jumlah_keluarga')->nullable();
            $table->integer('ms_jenis_tempat_tinggal_id')->nullable();
            $table->decimal('jumlah_kran', 2, 0)->nullable();
            $table->decimal('sedia_bayar', 1, 0)->nullable();
            $table->string('no_pelanggan')->nullable();
            $table->integer('petugas_id')->nullable();
            $table->date('tgl_register')->nullable();
            $table->date('tgl_pasang')->nullable();
            $table->integer('ms_meteran_id')->nullable();
            $table->string('no_seri_meteran')->nullable();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_transactions');
    }
};
