<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Scheme extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name', 'hindi_name', 'slug', 'category_id', 'ministry', 'state',
        'description', 'eligibility_rules_json', 'documents_required_json',
        'benefit_value', 'benefit_type', 'portal_url', 'form_url',
        'status_url', 'helpline', 'whatsapp', 'deadline', 'is_central', 'is_active',
    ];

    protected $casts = [
        'eligibility_rules_json'   => 'array',
        'documents_required_json'  => 'array',
        'deadline'                 => 'date',
        'is_central'               => 'boolean',
        'is_active'                => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(SchemeCategory::class, 'category_id');
    }

    public function steps()
    {
        return $this->hasMany(SchemeStep::class);
    }

    public function userMatches()
    {
        return $this->hasMany(UserSchemeMatch::class);
    }

    public function toSearchableArray()
    {
        return [
            'name'        => $this->name,
            'hindi_name'  => $this->hindi_name,
            'description' => $this->description,
            'ministry'    => $this->ministry,
        ];
    }
}