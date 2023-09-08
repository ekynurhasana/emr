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
        Schema::create('data_resep_obat_pasien', function (Blueprint $table) {
            $table->id();
            $table->string('no_resep')->nullable();
            $table->foreignId('pendaftaran_id')->nullable()->constrained('data_pendaftar_perawatan');
            $table->foreignId('pasien_id')->nullable()->constrained('data_pasien');
            $table->foreignId('dokter_poli_id')->nullable()->constrained('data_dokter_poli');
            $table->string('resep_dokter')->nullable();
            $table->enum('status', ['draft', 'diproses', 'selesai'])->default('draft');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_resep_obat_pasien');
    }
};
