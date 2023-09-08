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
            $table->enum('status', ['baru', 'antri', 'diperiksa', 'selesai', 'batal'])->default('baru')->after('no_pendaftaran');
            $table->tinyInteger('is_periksa_lanjutan')->default(0)->after('pemeriksaan_fisik_lainnya');
            $table->date('rencana_jadwal_selanjutnya')->nullable()->after('is_periksa_lanjutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftar_perawatan', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('is_periksa_lanjutan');
            $table->dropColumn('rencana_jadwal_selanjutnya');
        });
    }
};
