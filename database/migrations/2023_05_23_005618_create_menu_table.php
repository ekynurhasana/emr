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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('icon');
            $table->integer('sequence');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_parent')->default(true);
            $table->foreignId('parent_id')->nullable()->constrained('menu')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('user_groups')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
