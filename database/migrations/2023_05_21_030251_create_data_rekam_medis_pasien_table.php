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
        Schema::create('data_rekam_medis_pasien', function (Blueprint $table) {
            $table->id();
            $table->string('no_rekam_medis');
            $table->foreignId('pasien_id')->constrained('data_pasien');
            $table->foreignId('dokter_id')->constrained('users');
            $table->foreignId('pendaftaran_id')->constrained('data_pendaftar_perawatan');
            $table->datetime('tanggal_pemeriksaan');
            $table->string('keluhan');
            $table->string('diagnosa');
            $table->string('tindakan');
            $table->string('keterangan');
            $table->string('biaya_tambahan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rekam_medis_pasien');
    }
};
