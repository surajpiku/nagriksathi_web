<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = ['category_id', 'name', 'hindi_name', 'slug', 'icon', 'is_active'];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }
}