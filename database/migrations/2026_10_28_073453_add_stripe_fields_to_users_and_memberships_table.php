<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users table me Stripe Customer ID
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_id')->nullable()->index()->after('remember_token');
        });

        // Memberships table me Stripe Subscription aur Price IDs
        Schema::table('memberships', function (Blueprint $table) {
            $table->string('stripe_subscription_id')->nullable()->index()->after('uscreen_subscription_id');
            $table->string('stripe_price_id')->nullable()->after('plan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('stripe_id');
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn(['stripe_subscription_id', 'stripe_price_id']);
        });
    }
};