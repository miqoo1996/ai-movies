<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowStreamingSource extends Model
{
    protected $fillable = ['show_id', 'external_id', 'hashid', 'type', 'lang', 'url', 'source_name', 'source_image', 'source_slug', 'source_premium'];

    protected $casts = ['lang' => 'array', 'source_premium' => 'boolean'];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }
}
