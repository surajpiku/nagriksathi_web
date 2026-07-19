<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'razorpay_order_id',
        'razorpay_payment_id', 'razorpay_signature',
        'amount', 'currency', 'billing_cycle',
        'status', 'razorpay_response', 'paid_at',
    ];

    protected $casts = [
        'razorpay_response' => 'array',
        'paid_at'           => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}