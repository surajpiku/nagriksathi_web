<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'hindi_name', 'slug', 'icon',
        'description', 'display_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schemes()
    {
        return $this->hasMany(Scheme::class, 'category_id');
    }
}