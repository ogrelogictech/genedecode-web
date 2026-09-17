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
        Schema::table('uscreen_subscribers', function (Blueprint $table) {
            $table->string('migration_status')
                ->default('pending')
                ->after('is_migrated');

            $table->timestamp('migrated_at')
                ->nullable()
                ->after('migration_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uscreen_subscribers', function (Blueprint $table) {
            $table->dropColumn([
                'migration_status',
                'migrated_at',
            ]);
        });
    }
};
