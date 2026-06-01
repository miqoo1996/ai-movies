<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    protected $fillable = [
        'tvmaze_id',
        'name',
        'photo',
        'gender',
        'birthday',
        'country',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function shows(): BelongsToMany
    {
        return $this->belongsToMany(Show::class, 'show_person')
            ->withPivot('department', 'role', 'character_name', 'character_photo', 'sort_order')
            ->withTimestamps();
    }
}
