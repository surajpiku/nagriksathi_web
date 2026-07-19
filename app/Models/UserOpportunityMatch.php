<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserOpportunityMatch extends Model
{
    protected $fillable = [
        'user_id', 'opportunity_id', 'eligibility_status',
        'match_score', 'has_applied', 'is_saved', 'applied_on', 'matched_at',
    ];

    protected $casts = [
        'has_applied' => 'boolean',
        'is_saved'    => 'boolean',
        'applied_on'  => 'date',
        'matched_at'  => 'datetime',
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