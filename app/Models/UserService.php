<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'service_type_id', 'description',
        'availability', 'price_range', 'languages_json', 'service_area',
        'rating', 'review_count', 'contact_count', 'status', 'is_verified',
    ];

    protected $casts = [
        'languages_json' => 'array',
        'is_verified'    => 'boolean',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(ServiceCategory::class, 'category_id'); }
    public function type()     { return $this->belongsTo(ServiceType::class, 'service_type_id'); }
    public function reviews()  { return $this->hasMany(ServiceReview::class); }
    public function contacts() { return $this->hasMany(ServiceContact::class); }
}