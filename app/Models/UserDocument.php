<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'doc_type', 'file_url', 'file_size',
        'extracted_data_json', 'expiry_date', 'verified_at',
        'digilocker_id', 'ocr_status',
    ];

    protected $casts = [
        'expiry_date'         => 'date',
        'verified_at'         => 'datetime',
        'extracted_data_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}