<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'uscreen_id',
        'title',
        'slug',
        'short_description',
        'description',
        'author_ids',
        'subscription_plan_ids',
        'category_ids',
        'thumbnail_url',
        'is_free',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'subscription_plan_ids' => 'array',
        'category_ids' => 'array',
    ];

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class)
            ->withPivot('position')
            ->orderBy('collection_video.position', 'asc')
            ->withTimestamps();
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}