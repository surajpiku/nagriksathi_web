<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = ['name', 'hindi_name', 'slug', 'icon', 'description', 'display_order', 'is_active'];

    public function types()
    {
        return $this->hasMany(ServiceType::class, 'category_id');
    }

    public function services()
    {
        return $this->hasMany(UserService::class, 'category_id');
    }
}