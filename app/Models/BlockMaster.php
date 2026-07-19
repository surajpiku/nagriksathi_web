<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockMaster extends Model
{
    protected $table = 'blocks_master';

    protected $fillable = [
        'district_id', 'name', 'hindi_name', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function district()
    {
        return $this->belongsTo(DistrictMaster::class, 'district_id');
    }
}