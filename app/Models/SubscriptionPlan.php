<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'hindi_name', 'type',
        'price_monthly', 'price_yearly',
        'razorpay_plan_monthly', 'razorpay_plan_yearly',
        'features_json', 'limits_json',
        'is_active', 'is_popular', 'sort_order',
    ];

    protected $casts = [
        'features_json' => 'array',
        'limits_json'   => 'array',
        'is_active'     => 'boolean',
        'is_popular'    => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeCitizen($query)
    {
        return $query->where('type', 'citizen');
    }

    public function scopeSevaMitra($query)
    {
        return $query->where('type', 'seva_mitra');
    }
}
