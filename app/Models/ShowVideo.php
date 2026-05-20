<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowVideo extends Model
{
    protected $fillable = ['show_id', 'external_id', 'hashid', 'url', 'poster', 'title', 'description', 'playable'];

    protected $casts = ['playable' => 'boolean'];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }
}
