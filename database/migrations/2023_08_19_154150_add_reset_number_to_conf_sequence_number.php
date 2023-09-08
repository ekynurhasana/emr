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
            $table->enum('reset_number', ['year', 'month', 'day', 'never'])->default('never')->after('last_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conf_sequence_number', function (Blueprint $table) {
            $table->dropColumn('reset_number');
        });
    }
};
