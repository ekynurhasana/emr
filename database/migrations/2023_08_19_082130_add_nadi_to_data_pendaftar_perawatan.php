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
            $table->string('nadi')->after('tekanan_darah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftar_perawatan', function (Blueprint $table) {
            $table->dropColumn('nadi');
        });
    }
};
