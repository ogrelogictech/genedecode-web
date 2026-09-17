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
        Schema::create('uscreen_subscribers', function (Blueprint $table) {
            $table->id();

            $table->string('uscreen_user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();

            $table->string('uscreen_subscription_id')->nullable();
            $table->string('subscription_plan')->nullable();
            $table->string('billing_interval')->nullable();
            $table->string('origin')->nullable();
            $table->boolean('is_migrated')->default(false);

            $table->dateTime('subscription_start_date')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->dateTime('churn_date')->nullable();
            $table->dateTime('next_invoice_at')->nullable();

            $table->string('subscription_status')->nullable();
            $table->decimal('lifetime_spend', 12, 2)->nullable();

            $table->timestamps();

            $table->index('user_email');
            $table->index('uscreen_subscription_id');
            $table->index('subscription_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uscreen_subscribers');
    }
};
