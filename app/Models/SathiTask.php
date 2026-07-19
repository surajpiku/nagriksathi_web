<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SathiTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'agent_id', 'task_type', 'status',
        'description', 'resolution', 'priority', 'channel', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}