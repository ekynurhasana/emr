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
        Schema::create('data_resep_obat_pasien_line', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resep_obat_pasien_id')->nullable()->constrained('data_resep_obat_pasien');
            $table->foreignId('obat_id')->nullable()->constrained('data_obat');
            $table->integer('qty')->nullable();
            $table->string('satuan')->nullable();
            $table->string('aturan_pakai')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_resep_obat_pasien_line');
    }
};
