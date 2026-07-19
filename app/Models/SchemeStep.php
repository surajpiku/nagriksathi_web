<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheme_id', 'step_number', 'title',
        'description', 'link', 'link_label', 'office_type', 'is_online',
    ];

    protected $casts = [
        'is_online' => 'boolean',
    ];

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}