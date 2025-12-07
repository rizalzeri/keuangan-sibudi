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
        Schema::table('profils', function (Blueprint $table) {
            $table->string('kabupaten')->nullable()->after('nm_bumdes'); // ubah 'nama' sesuai kolom sebelumnya
            $table->string('no_wa')->nullable()->after('kecamatan');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            $table->dropColumn(['kabupaten', 'no_wa']);
        });
    }
};
