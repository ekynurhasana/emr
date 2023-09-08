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
        Schema::create('data_dokter_poli', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokter_id')->constrained('users');
            $table->foreignId('poli_id')->constrained('data_poli');
            $table->string('waktu_praktek');
            $table->enum('status',
                [
                    'buka',
                    'tutup',
                ])->default('tutup');
            $table->float('biaya_tambahan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_dokter_poli');
    }
};
