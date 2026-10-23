<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'uscreen_id',
        'bunny_guid',
        'title',
        'short_description',
        'description',
        'duration_seconds',
        'uploaded_at',
        'author',
        'thumbnail_url',
        'thumbnail_path',
        'section',
        'access',
        'status',
        'import_note',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'uploaded_at' => 'datetime',
    ];

    public function collections(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Collection::class)
            ->withPivot('position')
            ->withTimestamps();
    }
}