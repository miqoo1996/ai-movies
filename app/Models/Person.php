<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    protected $fillable = [
        'tvmaze_id',
        'name',
        'photo',
        'photo_local',
        'gender',
        'birthday',
        'country',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    /** Prefer locally-uploaded file; fall back to remote URL. */
    protected function photoUrl(): Attribute
    {
        return Attribute::get(function () {
            $local = $this->getRawOriginal('photo_local');
            if ($local && file_exists(storage_path('app/public/' . $local))) {
                return asset('storage/' . $local);
            }
            return $this->getRawOriginal('photo');
        });
    }

    public function shows(): BelongsToMany
    {
        return $this->belongsToMany(Show::class, 'show_person')
            ->withPivot('department', 'role', 'character_name', 'character_photo', 'sort_order')
            ->withTimestamps();
    }
}
