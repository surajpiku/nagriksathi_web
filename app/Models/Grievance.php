<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grievance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'application_id', 'cpgrams_id', 'rti_reference',
        'rti_text', 'status', 'filed_at', 'resolved_at',
    ];

    protected $casts = [
        'filed_at'    => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}