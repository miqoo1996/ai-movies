<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'seo_title',
        'seo_description',
        'noindex',
        'excerpt',
        'content',
        'cover_image',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'noindex'      => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /** Full URL for the cover, whether it is a stored upload or an external URL. */
    public function getCoverUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }

        return asset('storage/' . ltrim($this->cover_image, '/'));
    }

    /** True when cover_image points at a file on the public disk. */
    public function hasStoredCover(): bool
    {
        return $this->cover_image
            && ! str_starts_with($this->cover_image, 'http')
            && Storage::disk('public')->exists($this->cover_image);
    }
}
