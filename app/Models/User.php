<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'phone',
        'language', 'subscription_tier', 'nagrik_score', 'fcm_token',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function schemeMatches()
    {
        return $this->hasMany(UserSchemeMatch::class);
    }

    public function sathiTasks()
    {
        return $this->hasMany(SathiTask::class);
    }

    public function conversations()
    {
        return $this->hasMany(SathiConversation::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function grievances()
    {
        return $this->hasMany(Grievance::class);
    }

    public function savedOpportunities()
{
    return $this->belongsToMany(Opportunity::class, 'saved_opportunities');
}

public function opportunityMatches()
{
    return $this->hasMany(UserOpportunityMatch::class);
}
public function guardName(): string
{
    return 'sanctum';
}
public function SevaMitra()
{
    return $this->hasOne(SevaMitra::class);
}

public function cscAgent()
    {
        return $this->hasOne(\App\Models\CscAgent::class);
    }

    public function subscription()
{
    return $this->hasOne(UserSubscription::class)
        ->where('status', 'active')
        ->where('ends_at', '>', now())
        ->latest();
}

public function subscriptions()
{
    return $this->hasMany(UserSubscription::class);
}

public function paymentOrders()
{
    return $this->hasMany(PaymentOrder::class);
}

public function getActivePlanAttribute(): string
{
    return $this->subscription?->plan?->slug ?? 'free';
}
public function services()    { return $this->hasMany(UserService::class); }
public function privacy()     { return $this->hasOne(ServicePrivacy::class); }
}


