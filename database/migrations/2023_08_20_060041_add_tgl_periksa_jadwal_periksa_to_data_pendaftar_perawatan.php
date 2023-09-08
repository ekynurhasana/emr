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
        Schema::table('data_pendaftar_perawatan', function (Blueprint $table) {
            $table->date('tgl_periksa')->nullable()->after('tanggal_pendaftaran');
            $table->foreignId('jadwal_dokter_id')->nullable()->after('tgl_periksa')->constrained('data_jadwal_praktek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftar_perawatan', function (Blueprint $table) {
            $table->dropColumn('tgl_periksa');
            $table->dropColumn('jadwal_periksa_id');
        });
    }
};
