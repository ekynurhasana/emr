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
        Schema::table('data_resep_obat_pasien_line', function (Blueprint $table) {
            $table->string('obat')->after('obat_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_resep_obat_pasien_line', function (Blueprint $table) {
            $table->dropColumn('obat');
        });
    }
};
