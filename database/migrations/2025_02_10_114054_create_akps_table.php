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
        Schema::create('akps', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->bigInteger('dana')->nullable();
            $table->bigInteger('alokasi')->nullable();
            $table->float('pendapatan')->nullable();
            $table->float('pembiayaan')->nullable();
            $table->string('tematik')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akps');
    }
};
