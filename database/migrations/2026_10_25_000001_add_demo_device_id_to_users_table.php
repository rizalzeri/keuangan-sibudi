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
        if (!Schema::hasColumn('users', 'demo_device_id')) {
            Schema::table('users', function (Blueprint $table) {
                // ID perangkat (disimpan di localStorage browser) agar data praktikum terpisah per device
                $table->string('demo_device_id', 64)->nullable()->index()->after('portal_user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'demo_device_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('demo_device_id');
            });
        }
    }
};
