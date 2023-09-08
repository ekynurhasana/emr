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
        Schema::create('conf_antrean_rawat_jalan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_antrean')->nullable();
            $table->string('no_antreaan')->nullable();
            $table->foreignId('pendaftaran_perawatan_id')->nullable()->constrained('data_pendaftar_perawatan');
            $table->foreignId('pasien_id')->nullable()->constrained('data_pasien');
            $table->foreignId('poli_id')->nullable()->constrained('data_poli');
            $table->foreignId('dokter_poli_id')->nullable()->constrained('data_dokter_poli');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conf_antrean');
    }
};
