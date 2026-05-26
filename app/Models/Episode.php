<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = ['show_id', 'external_id', 'turkflix_item_id', 'hashid', 'season_number', 'episode_number', 'title', 'overview', 'shortcode', 'airs_on', 'has_aired', 'season_finale', 'thumb', 'thumb_local'];

    protected $casts = ['airs_on' => 'date', 'has_aired' => 'boolean', 'season_finale' => 'boolean'];

    protected function thumbUrl(): Attribute
    {
        return Attribute::get(function () {
            $local = $this->getRawOriginal('thumb_local');
            if ($local && file_exists(storage_path('app/public/' . $local))) {
                return asset('storage/' . $local);
            }
            return $this->getRawOriginal('thumb');
        });
    }

    public function show()
    {
        return $this->belongsTo(Show::class);
    }
}
