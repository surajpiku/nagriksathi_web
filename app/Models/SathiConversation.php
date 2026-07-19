<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SathiConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'agent_id', 'task_id',
        'channel', 'messages_json', 'ai_tokens_used',
    ];

    protected $casts = [
        'messages_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function task()
    {
        return $this->belongsTo(SathiTask::class);
    }
}