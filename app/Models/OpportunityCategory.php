<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OpportunityCategory extends Model
{
    protected $fillable = ['name', 'hindi_name', 'slug', 'icon', 'description', 'display_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'category_id');
    }
}