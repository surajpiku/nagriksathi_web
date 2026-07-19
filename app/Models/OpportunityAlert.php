<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OpportunityAlert extends Model
{
    protected $fillable = [
        'user_id', 'opportunity_id', 'alert_type',
        'title', 'message', 'action_url', 'read_at', 'sent_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }
}