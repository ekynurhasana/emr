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
        Schema::create('data_asuransi_pasien', function (Blueprint $table) {
            $table->id();
            $table->string('slug_number');
            $table->foreignId('pasien_id')->constrained('data_pasien')->onDelete('cascade');
            $table->foreignId('asuransi_id')->constrained('data_asuransi')->onDelete('cascade');
            $table->string('nomor_peserta');
            $table->boolean('is_limit');
            $table->enum('status', ['aktif', 'tidak_aktif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_asuransi_pasien');
    }
};
