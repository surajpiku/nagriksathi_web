<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ServicePrivacy extends Model
{
    protected $table = 'service_privacy';
    protected $fillable = ['user_id', 'visibility', 'location_precision', 'contact_preference', 'show_phone', 'all_paused'];

    protected $casts = ['show_phone' => 'boolean', 'all_paused' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
}