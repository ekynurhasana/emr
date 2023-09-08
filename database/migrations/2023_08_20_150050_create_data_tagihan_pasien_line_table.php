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
        Schema::create('data_tagihan_pasien_line', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_pasien_id')->nullable()->constrained('data_tagihan_pasien');
            $table->enum('jenis_tagihan', ['perawatan', 'obat', 'tindakan', 'administrasi', 'lainnya'])->default('perawatan');
            $table->string('nama_tagihan')->nullable();
            $table->string('keterangan')->nullable();
            $table->float('harga')->nullable();
            $table->float('qty')->nullable();
            $table->float('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_tagihan_pasien_line');
    }
};
