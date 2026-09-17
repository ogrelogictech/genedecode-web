<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoProgress extends Model
{
    protected $fillable = [
        'user_id',
        'video_id',
        'progress_seconds',
        'duration_seconds',
        'progress_percent',
        'last_watched_at',
    ];

    protected $casts = [
        'progress_seconds' => 'decimal:2',
        'duration_seconds' => 'decimal:2',
        'progress_percent' => 'decimal:2',
        'last_watched_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}