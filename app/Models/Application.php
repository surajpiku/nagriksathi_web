<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'scheme_id', 'reference_number', 'status',
        'submitted_at', 'expected_by', 'portal_status', 'last_checked_at',
    ];

    protected $casts = [
        'submitted_at'    => 'datetime',
        'expected_by'     => 'datetime',
        'last_checked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function grievance()
    {
        return $this->hasOne(Grievance::class);
    }
}