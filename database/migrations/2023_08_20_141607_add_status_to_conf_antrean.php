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
        Schema::table('conf_antrean_rawat_jalan', function (Blueprint $table) {
            $table->enum('status', ['antri', 'selesai'])->default('antri')->after('no_antreaan');
            $table->timestamp('waktu_panggilan')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conf_antrean_rawat_jalan', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('waktu_panggilan');
        });
    }
};
