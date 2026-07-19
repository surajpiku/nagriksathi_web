<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'billing_cycle', 'status',
        'razorpay_subscription_id', 'starts_at', 'ends_at',
        'cancelled_at', 'cancellation_reason',
    ];

    protected $casts = [
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }
}