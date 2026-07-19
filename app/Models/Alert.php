<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'title', 'message',
        'data_json', 'urgency', 'read_at', 'sent_at',
    ];

    protected $casts = [
        'data_json' => 'array',
        'read_at'   => 'datetime',
        'sent_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}