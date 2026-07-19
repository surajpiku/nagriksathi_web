<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceContact extends Model
{
    protected $fillable = ['user_service_id', 'seeker_id', 'contact_method', 'contacted_at'];

    public function service() { return $this->belongsTo(UserService::class, 'user_service_id'); }
    public function seeker()  { return $this->belongsTo(User::class, 'seeker_id'); }
}