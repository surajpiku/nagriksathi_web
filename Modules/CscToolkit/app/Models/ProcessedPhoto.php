<?php

namespace Modules\CscToolkit\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessedPhoto extends Model
{
    protected $fillable = [
        'csc_agent_id', 'user_id', 'photo_type',
        'preset', 'original_url', 'processed_url',
        'width_mm', 'height_mm', 'file_size_kb', 'format',
    ];

    public function agent()
    {
        return $this->belongsTo(\App\Models\CscAgent::class, 'csc_agent_id');
    }
}
