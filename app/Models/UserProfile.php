<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'dob', 'gender', 'state', 'district', 'city',
        'occupation', 'annual_income', 'caste_category', 'bpl_status',
        'land_acres', 'house_type', 'has_vehicle', 'assets_json', 'is_complete','state_lgd_code',
'subdistrict_id',
'gram_panchayat',
'locality_name',
'ward_number',
'location_complete',
'location_depth',
'gps_captured_at',
    ];

    protected $casts = [
        'dob'         => 'date',
        'bpl_status'  => 'boolean',
        'has_vehicle' => 'boolean',
        'is_complete' => 'boolean',
        'assets_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAgeAttribute()
    {
        return $this->dob ? $this->dob->age : null;
    }
}