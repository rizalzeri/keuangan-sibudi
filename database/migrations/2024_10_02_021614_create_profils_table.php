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
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->string('nm_bumdes', 100)->nullable();
            $table->string('desa', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('nm_direktur', 50)->nullable();
            $table->string('nm_serkertaris', 50)->nullable();
            $table->string('nm_bendahara', 50)->nullable();
            $table->string('nm_pengawas', 50)->nullable();
            $table->string('nm_penasehat', 50)->nullable();
            $table->string('unt_usaha1', 50)->nullable();
            $table->string('nm_kepyun1', 50)->nullable();
            $table->string('unt_usaha2', 50)->nullable();
            $table->string('nm_kepyun2', 50)->nullable();
            $table->string('unt_usaha3', 50)->nullable();
            $table->string('nm_kepyun3', 50)->nullable();
            $table->string('unt_usaha4', 50)->nullable();
            $table->string('nm_kepyun4', 50)->nullable();
            $table->string('no_badan', 50)->nullable();
            $table->string('no_perdes', 50)->nullable();
            $table->string('no_sk', 50)->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
