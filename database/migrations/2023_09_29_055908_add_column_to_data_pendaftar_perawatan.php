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
            $table->string('riwayat_rawat_inap')->nullable()->after('riwayat_penyakit');
            $table->string('riwayat_operasi')->nullable()->after('riwayat_rawat_inap');
            $table->string('cara_berjalan_pasien')->nullable()->after('riwayat_operasi');
            $table->string('menopang_saat_akan_duduk')->nullable()->after('cara_berjalan_pasien');
            $table->string('resiko_jatuh')->nullable()->after('menopang_saat_akan_duduk');
            $table->string('tindakan_pengamanan_jatuh')->nullable()->after('resiko_jatuh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftar_perawatan', function (Blueprint $table) {
            $table->dropColumn('riwayat_rawat_inap');
            $table->dropColumn('riwayat_operasi');
            $table->dropColumn('cara_berjalan_pasien');
            $table->dropColumn('menopang_saat_akan_duduk');
            $table->dropColumn('resiko_jatuh');
            $table->dropColumn('tindakan_pengamanan_jatuh');
        });
    }
};
