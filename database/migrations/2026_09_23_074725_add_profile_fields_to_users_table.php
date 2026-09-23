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
            $table->string('profilepic')->nullable()->after('email');
            $table->string('display_name')->nullable()->after('name');
            $table->string('location')->nullable()->after('display_name');
            $table->text('bio')->nullable()->after('location');
            $table->string('website')->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profilepic', 'display_name', 'location', 'bio', 'website']);
        });
    }
};