<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Show extends Model
{
    protected $fillable = [
        'external_id',
        'turkflix_id',
        'hashid',
        'title',
        'original_title',
        'turkish_title',
        'ai_title',
        'ai_turkish_title',
        'slug',
        'status',
        'network',
        'runtime',
        'premiered',
        'year',
        'synopsis',
        'ai_synopsis',
        'poster',
        'poster_local',
        'rating',
        'subscribers',
        'seo_title',
        'seo_description',
        'noindex',
    ];

    protected $casts = [
        'premiered'   => 'date',
        'year'        => 'integer',
        'runtime'     => 'integer',
        'rating'      => 'float',
        'subscribers' => 'integer',
        'noindex'     => 'boolean',
    ];

    // Accessors: prefer AI-generated fields when present, fall back to originals

    protected function title(): Attribute
    {
        return Attribute::get(fn () => $this->getRawOriginal('ai_title') ?: $this->getRawOriginal('title'));
    }

    protected function turkishTitle(): Attribute
    {
        return Attribute::get(fn () => $this->getRawOriginal('ai_turkish_title') ?: $this->getRawOriginal('turkish_title'));
    }

    protected function synopsis(): Attribute
    {
        return Attribute::get(fn () => $this->getRawOriginal('ai_synopsis') ?: $this->getRawOriginal('synopsis'));
    }

    protected function posterUrl(): Attribute
    {
        return Attribute::get(function () {
            $local = $this->getRawOriginal('poster_local');
            if ($local && file_exists(storage_path('app/public/' . $local))) {
                return asset('storage/' . $local);
            }
            return $this->getRawOriginal('poster');
        });
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ShowImage::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ShowVideo::class);
    }

    public function streamingSources(): HasMany
    {
        return $this->hasMany(ShowStreamingSource::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}
