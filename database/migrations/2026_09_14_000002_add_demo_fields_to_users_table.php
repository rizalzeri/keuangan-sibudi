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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_demo')->default(false)->after('role');
            $table->string('demo_token', 100)->nullable()->index()->after('is_demo');
            $table->timestamp('demo_expires_at')->nullable()->after('demo_token');
            $table->unsignedBigInteger('portal_user_id')->nullable()->index()->after('demo_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_demo', 'demo_token', 'demo_expires_at', 'portal_user_id']);
        });
    }
};
