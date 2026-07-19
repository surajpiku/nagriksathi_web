<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SubdistrictMaster extends Model
{
    protected $table    = 'subdistricts_master';
    protected $fillable = ['district_id', 'name', 'hindi_name', 'code', 'type', 'is_active'];

    public function district()       { return $this->belongsTo(DistrictMaster::class, 'district_id'); }
    public function gramPanchayats() { return $this->hasMany(GramPanchayat::class, 'subdistrict_id'); }
}