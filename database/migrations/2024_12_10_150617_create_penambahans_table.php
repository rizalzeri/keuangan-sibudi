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
        Schema::create('penambahans', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('unit');
            $table->string('status_unit');
            $table->string('jumlah');
            $table->string('aspek_pasar');
            $table->string('aspek_keuangan');
            $table->string('aspek_lain');
            $table->string('strategi');
            $table->string('kesimpulan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penambahans');
    }
};
