<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buks', function (Blueprint $table) {
            $table->id();
            $table->string('transaksi', 100);
            $table->string('jenis', 100);
            $table->enum('jenis_dana', ['operasional', 'iventasi', 'pendanaan'])->default('operasional');
            $table->varchar('jenis_lr');
            $table->integer('nilai');
            $table->date('tanggal')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('akun', 100)->nullable();
            $table->integer('id_akun')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buks');
    }
};
