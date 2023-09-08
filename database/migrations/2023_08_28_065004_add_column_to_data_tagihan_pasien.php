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
        Schema::table('data_tagihan_pasien', function (Blueprint $table) {
            $table->boolean('is_use_asuransi')->default(false)->after('perawatan_id');
            $table->foreignId('asuransi_pasien_id')->nullable()->constrained('data_asuransi_pasien')->after('is_use_asuransi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_tagihan_pasien', function (Blueprint $table) {
            //
        });
    }
};
