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
        Schema::create('data_pasien', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pasien');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->string('no_telepon');
            $table->string('no_ktp');
            $table->string('agama');
            $table->string('pekerjaan');
            $table->string('status_perkawinan');
            $table->string('nama_wali');
            $table->enum('hubungan_dengan_wali', ['orang_tua', 'suami_istri', 'saudara', 'lainnya']);
            $table->string('hubungan_dengan_wali_lainnya')->nullable();
            $table->string('jenis_kelamin_wali');
            $table->string('alamat_wali');
            $table->string('no_telepon_wali');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pasien');
    }
};
