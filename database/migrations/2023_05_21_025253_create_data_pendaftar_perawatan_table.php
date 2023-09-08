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
        Schema::create('data_pendaftar_perawatan', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran');
            $table->foreignId('pasien_id')->constrained('data_pasien');
            $table->foreignId('poli_id')->constrained('data_poli');
            $table->foreignId('dokter_poli_id')->constrained('data_dokter_poli');
            $table->foreignId('dokter_id')->constrained('users');
            $table->datetime('tanggal_pendaftaran');
            $table->string('keluhan');
            $table->string('riwayat_penyakit');
            $table->string('tekanan_darah');
            $table->string('berat_badan');
            $table->string('tinggi_badan');
            $table->string('suhu_badan');
            $table->string('pemeriksaan_fisik_lainnya');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pendaftar_perawatan');
    }
};
