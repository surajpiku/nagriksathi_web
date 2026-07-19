<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GramPanchayat extends Model
{
    protected $table    = 'gram_panchayats_master';
    protected $fillable = ['subdistrict_id', 'name', 'hindi_name', 'lgd_code', 'is_active'];

    public function subdistrict() { return $this->belongsTo(SubdistrictMaster::class, 'subdistrict_id'); }
}