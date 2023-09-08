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
            $table->foreignId('erm_id')->constrained('data_rekam_medis')->after('no_rekam_medis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_rekam_medis_pasien', function (Blueprint $table) {
            $table->dropColumn('erm_id');
        });
    }
};
