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
        Schema::create('data_tagihan_pasien', function (Blueprint $table) {
            $table->id();
            $table->string('no_tagihan');
            $table->foreignId('pasien_id')->nullable()->constrained('data_pasien');
            $table->foreignId('perawatan_id')->nullable()->constrained('data_pendaftar_perawatan');
            $table->enum('jenis_diskon', ['persen', 'nominal'])->default('nominal');
            $table->float('diskon')->nullable();
            $table->float('total_tagihan')->nullable();
            $table->float('total_diskon')->nullable();
            $table->float('total_bayar')->nullable();
            $table->float('sisa_tagihan')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_tagihan_pasien');
    }
};
