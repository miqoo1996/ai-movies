<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowImage extends Model
{
    protected $fillable = ['show_id', 'external_id', 'hashid', 'url', 'local_path', 'thumb', 'local_thumb', 'width', 'height', 'mime_type', 'collection'];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }
}
