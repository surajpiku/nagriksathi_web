<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistrictMaster extends Model
{
    protected $table = 'districts_master';

    protected $fillable = [
        'state_id', 'name', 'hindi_name', 'code', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function state()
    {
        return $this->belongsTo(StateMaster::class, 'state_id');
    }

    public function blocks()
    {
        return $this->hasMany(BlockMaster::class, 'district_id');
    }
}