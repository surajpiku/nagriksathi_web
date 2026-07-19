<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CscCustomer extends Model

{

protected $table = 'csc_customers';
    protected $fillable = [
        'seva_mitra_id', 'user_id', 'customer_name', 'customer_phone',
        'task_type', 'task_description', 'status', 'token_number',
        'amount_charged', 'platform_commission', 'agent_earning',
        'payment_method', 'rating', 'customer_feedback',
        'visited_at', 'completed_at',
    ];

    protected $casts = [
        'visited_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(SevaMitra::class, 'seva_mitra_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function earning()
    {
        return $this->hasOne(CscEarning::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', today());
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visited_at', now()->month);
    }
}
