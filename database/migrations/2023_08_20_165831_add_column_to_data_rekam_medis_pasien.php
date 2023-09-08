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
        Schema::table('data_rekam_medis_pasien', function (Blueprint $table) {
            $table->string('diagnosa_icd')->nullable()->after('diagnosa');
            $table->string('resep_obat')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_rekam_medis_pasien', function (Blueprint $table) {
            $table->dropColumn('diagnosa_icd');
            $table->dropColumn('resep_obat');
        });
    }
};
