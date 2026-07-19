<?php

namespace Modules\CscToolkit\Models;

use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model

{
    protected $table = 'csc_form_templates';
    protected $fillable = [
        'form_id', 'form_name', 'hindi_name', 'category',
        'portal_url', 'portal_name', 'fields_json',
        'total_fields', 'is_active',
    ];

    protected $casts = [
        'fields_json' => 'array',
        'is_active'   => 'boolean',
    ];
}