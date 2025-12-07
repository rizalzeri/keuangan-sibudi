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
        Schema::create('prokers', function (Blueprint $table) {
            $table->id();
            $table->text('kualititif')->nullable();
            $table->text('strategi')->nullable();
            $table->string('unit_usaha')->nullable();
            $table->string('status_unit')->nullable();
            $table->string('jumlah')->nullable();
            $table->text('aspek_pasar')->nullable();
            $table->text('aspek_keuangan')->nullable();
            $table->text('aspek_lainya')->nullable();
            $table->text('strategi_pemasaran')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->year('tahun')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prokers');
    }
};
