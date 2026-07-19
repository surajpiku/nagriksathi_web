<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CscAgent extends Model

{
    protected $table = 'seva_mitras';
    protected $fillable = [
        'user_id', 'centre_name', 'csc_id', 'agent_type',
        'state', 'state_code', 'district', 'block', 'village',
        'pincode', 'address', 'latitude', 'longitude',
        'services_json', 'languages_json', 'working_hours_json', 'pricing_json',
        'rating', 'tasks_completed', 'customers_served',
        'bank_account', 'ifsc_code', 'upi_id',
        'vle_certificate_url', 'collaboration_letter_url',
        'aadhaar_url', 'centre_photo_url',
        'status', 'rejection_reason', 'verified_at',
    ];

    protected $casts = [
        'services_json'      => 'array',
        'languages_json'     => 'array',
        'working_hours_json' => 'array',
        'pricing_json'       => 'array',
        'verified_at'        => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customers()
    {
        return $this->hasMany(CscCustomer::class, 'seva_mitra_id');
    }

    public function earnings()
    {
        return $this->hasMany(CscEarning::class, 'seva_mitra_id');
    }

    public function reviews()
    {
        return $this->hasMany(AgentReview::class, 'seva_mitra_id');
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }
}
