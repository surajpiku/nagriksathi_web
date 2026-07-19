<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AgentReview extends Model

{
    protected $table = 'agent_reviews';
    protected $fillable = [
        'seva_mitra_id', 'user_id', 'csc_customer_id',
        'rating', 'review', 'is_verified_visit',
    ];

    protected $casts = [
        'is_verified_visit' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(SevaMitra::class, 'seva_mitra_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
