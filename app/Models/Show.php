<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    protected $fillable = [
        'external_id',
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
    ];

    protected $casts = [
        'premiered'   => 'date',
        'year'        => 'integer',
        'runtime'     => 'integer',
        'rating'      => 'float',
        'subscribers' => 'integer',
    ];

    public function genres(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }
}
