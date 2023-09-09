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
        Schema::table('data_tagihan_pasien_line', function (Blueprint $table) {
            $table->foreignId('resep_obat_line_id')->nullable()->constrained('data_resep_obat_pasien_line')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_tagihan_pasien_line', function (Blueprint $table) {
            $table->dropForeign(['resep_obat_line_id']);
            $table->dropColumn('resep_obat_line_id');
        });
    }
};
