<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OpportunityStep extends Model
{
    protected $fillable = ['opportunity_id', 'step_number', 'title', 'description', 'link', 'link_label'];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }
}