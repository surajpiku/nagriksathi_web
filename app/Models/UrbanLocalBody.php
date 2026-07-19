<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UrbanLocalBody extends Model
{
    protected $table    = 'urban_local_bodies_master';
    protected $fillable = ['district_id', 'name', 'hindi_name', 'type', 'lgd_code', 'is_active'];

    public function district() { return $this->belongsTo(DistrictMaster::class, 'district_id'); }
}