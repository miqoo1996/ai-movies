<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = ['show_id', 'external_id', 'hashid', 'season_number', 'episode_number', 'shortcode', 'airs_on', 'has_aired', 'season_finale', 'thumb'];

    protected $casts = ['airs_on' => 'date', 'has_aired' => 'boolean', 'season_finale' => 'boolean'];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }
}
