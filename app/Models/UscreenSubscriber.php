<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UscreenSubscriber extends Model
{
    protected $fillable = [
        'uscreen_user_id',
        'user_name',
        'user_email',
        'uscreen_subscription_id',
        'subscription_plan',
        'billing_interval',
        'origin',
        'is_migrated',
        'migration_status',
        'migrated_at',
        'migration_error',
        'subscription_start_date',
        'canceled_at',
        'churn_date',
        'next_invoice_at',
        'subscription_status',
        'lifetime_spend',
    ];

    protected $casts = [
        'is_migrated' => 'boolean',
        'subscription_start_date' => 'datetime',
        'canceled_at' => 'datetime',
        'churn_date' => 'datetime',
        'next_invoice_at' => 'datetime',
        'lifetime_spend' => 'decimal:2',
    ];
}