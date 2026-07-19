<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSchemeMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'scheme_id', 'eligibility_status',
        'match_score', 'matched_at', 'applied_at', 'claimed_at', 'benefit_value',
    ];

    protected $casts = [
        'matched_at' => 'datetime',
        'applied_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}