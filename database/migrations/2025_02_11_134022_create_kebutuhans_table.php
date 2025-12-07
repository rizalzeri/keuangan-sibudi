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
        Schema::create('kebutuhans', function (Blueprint $table) {
            $table->id();
            $table->string('uraian')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('harga')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('jumlah')->nullable();
            $table->enum(
                'kategori',
                [
                    'Sewa Tanah/Bangunan',
                    'Sewa Alat',
                    'Pengadaan Alat',
                    'Sarana Prasarana',
                    'Bibit/ Benih',
                    'Bahan Pemeliharaan',
                    'Pembiayaan-pembiayaan mingguan',
                    'Pekerja',
                    'Distribusi'
                ]
            );
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebutuhans');
    }
};
