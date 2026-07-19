<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StateMaster extends Model
{
    protected $table = 'states_master';

    protected $fillable = [
        'name', 'hindi_name', 'code', 'type', 'capital', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function districts()
    {
        return $this->hasMany(DistrictMaster::class, 'state_id');
    }
}