<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortalStatus extends Model
{
    protected $fillable = [
        'portal_name', 'portal_url', 'check_url',
        'status', 'response_time_ms', 'last_checked_at',
        'down_since', 'is_active',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
        'down_since'      => 'datetime',
        'is_active'       => 'boolean',
    ];

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'online'  => 'green',
            'slow'    => 'yellow',
            'down'    => 'red',
            default   => 'gray',
        };
    }

    public function getStatusEmojiAttribute(): string
    {
        return match($this->status) {
            'online'  => '🟢',
            'slow'    => '🟡',
            'down'    => '🔴',
            default   => '⚪',
        };
    }
}