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
        Schema::create('data_resep_obat', function (Blueprint $table) {
            $table->id();
            $table->string('no_resep_obat');
            $table->foreignId('rekam_medis_id')->constrained('data_rekam_medis_pasien');
            $table->foreignId('dokter_id')->constrained('users');
            $table->foreignId('pasien_id')->constrained('data_pasien');
            $table->foreignId('obat_id')->constrained('data_obat');
            $table->string('jumlah_obat');
            $table->string('aturan_pakai');
            $table->string('keterangan');
            $table->string('status');
            $table->string('biaya_tambahan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_resep_obat');
    }
};
