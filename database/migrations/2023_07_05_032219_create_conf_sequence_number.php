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
        Schema::create('conf_sequence_number', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('prefix');
            $table->integer('padding');
            $table->integer('last_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conf_sequence_number');
    }
};
