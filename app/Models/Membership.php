<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    protected $fillable = [
        'user_id',
        'uscreen_subscription_id',
        'stripe_subscription_id',
        'stripe_price_id',
        'plan',
        'status',
        'is_gift',
        'gift_months',
        'gift_session_id',
        'started_at',
        'expires_at',
        'canceled_at',
    ];

    protected $casts = [
        'is_gift'     => 'boolean',
        'gift_months' => 'integer',
        'started_at'  => 'datetime',
        'expires_at'  => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}