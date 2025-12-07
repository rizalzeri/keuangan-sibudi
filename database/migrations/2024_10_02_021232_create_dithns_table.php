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
        Schema::create('dithns', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->enum('hasil', ['untung', 'rugi']);
            $table->integer('nilai');
            $table->integer('pades');
            $table->integer('lainya');
            $table->integer('akumulasi');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dithns');
    }
};
