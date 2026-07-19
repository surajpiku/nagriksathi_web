<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'dob', 'gender', 'relationship', 'profile_json',
    ];

    protected $casts = [
        'dob'          => 'date',
        'profile_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}