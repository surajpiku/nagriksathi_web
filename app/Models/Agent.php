<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email', 'state', 'languages_json',
        'specialisation', 'rating', 'tasks_completed', 'is_active', 'fcm_token',
    ];

    protected $casts = [
        'languages_json' => 'array',
        'is_active'      => 'boolean',
    ];

    public function tasks()
    {
        return $this->hasMany(SathiTask::class);
    }

    public function conversations()
    {
        return $this->hasMany(SathiConversation::class);
    }
}