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
        Schema::table('data_asuransi_pasien', function (Blueprint $table) {
            $table->foreignId('tipe_asuransi_id')->nullable()->constrained('data_asuransi_tipe')->onDelete('cascade')->after('asuransi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_asuransi_pasien', function (Blueprint $table) {
            $table->dropColumn('tipe_asuransi_id');
        });
    }
};
