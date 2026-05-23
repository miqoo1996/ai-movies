<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = ['name', 'email', 'subject', 'message', 'ip_address', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }
}
