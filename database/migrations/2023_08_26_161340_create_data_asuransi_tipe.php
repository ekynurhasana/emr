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
        Schema::create('data_asuransi_tipe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asuransi_id')->constrained('data_asuransi')->onDelete('cascade');
            $table->string('nama');
            $table->string('deskripsi')->nullable();
            $table->json('tanggungan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_asuransi_tipe');
    }
};
