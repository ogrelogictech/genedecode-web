<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->boolean('is_gift')->default(false)->after('status');
            $table->unsignedInteger('gift_months')->nullable()->after('is_gift');
            $table->string('gift_session_id')->nullable()->unique()->after('gift_months');
        });
    }

    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn(['is_gift', 'gift_months', 'gift_session_id']);
        });
    }
};