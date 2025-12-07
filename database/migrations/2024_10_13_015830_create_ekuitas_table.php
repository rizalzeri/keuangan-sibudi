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
        Schema::create('ekuits', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->nullable();
            $table->integer('pades')->nullable();
            $table->integer('lainya')->nullable();
            $table->integer('akumulasi')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekuits');
    }
};
