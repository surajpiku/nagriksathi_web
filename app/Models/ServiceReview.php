<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    protected $fillable = ['user_service_id', 'reviewer_id', 'rating', 'comment', 'is_approved'];

    public function service()  { return $this->belongsTo(UserService::class, 'user_service_id'); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }
}