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
        Schema::table('conf_sequence_number', function (Blueprint $table) {
            $table->tinyInteger('is_use_date')->default(0)->after('padding');
            $table->string('date_format')->nullable()->after('is_use_date');
            $table->string('separator')->nullable()->after('date_format');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conf_sequence_number', function (Blueprint $table) {
            $table->dropColumn('is_use_date');
            $table->dropColumn('date_format');
            $table->dropColumn('separator');
        });
    }
};
