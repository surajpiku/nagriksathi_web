<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CscEarning extends Model
{
    protected $fillable = [
        'seva_mitra_id', 'csc_customer_id', 'sathi_task_id',
        'earning_type', 'gross_amount', 'commission_deducted',
        'net_amount', 'payment_status', 'payment_reference', 'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(CscAgent::class, 'seva_mitra_id');
    }

    public function customer()
    {
        return $this->belongsTo(CscCustomer::class, 'csc_customer_id');
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }
}
