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
        Schema::create('data_asuransi_pasien_tanggungan', function (Blueprint $table) {
            $table->id();
            $table->string('slug_number');
            $table->foreignId('asuransi_pasien_id')->constrained('data_asuransi_pasien')->onDelete('cascade');
            $table->enum('jenis_tanggungan', ['all', 'perawatan', 'obat', 'tindakan', 'administrasi', 'lainnya'])->default('all');
            $table->string('nama_tanggungan')->nullable();
            $table->foreignId('poli_id')->nullable()->constrained('data_poli')->onDelete('cascade');
            $table->boolean('is_limit');
            $table->float('limit', 15, 2)->nullable();
            $table->float('sisa_limit', 15, 2)->nullable();
            $table->date('tanggal_terakhir_penggunaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_asuransi_pasien_tanggungan');
    }
};
