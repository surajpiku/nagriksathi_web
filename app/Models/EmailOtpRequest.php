<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtpRequest extends Model
{
    protected $fillable = [
        'email', 'otp_hash', 'expires_at',
        'verified_at', 'ip_address',
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }
}