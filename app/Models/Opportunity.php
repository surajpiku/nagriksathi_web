<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Opportunity extends Model
{
    use Searchable;

    protected $fillable = [
        'name', 'hindi_name', 'slug', 'category_id', 'conducting_body', 'post_name',
        'level', 'state_code', 'description', 'eligibility_rules_json',
        'physical_standards_json', 'documents_required_json', 'vacancy_count',
        'salary_range', 'job_location', 'notification_url', 'apply_url',
        'syllabus_url', 'official_site', 'helpline', 'apply_start', 'apply_end',
        'exam_date', 'admit_card_date', 'result_date', 'is_active', 'is_featured','district', 'local_level',
    ];

    protected $casts = [
        'eligibility_rules_json'  => 'array',
        'physical_standards_json' => 'array',
        'documents_required_json' => 'array',
        'apply_start'             => 'date',
        'apply_end'               => 'date',
        'exam_date'               => 'date',
        'admit_card_date'         => 'date',
        'result_date'             => 'date',
        'is_active'               => 'boolean',
        'is_featured'             => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(OpportunityCategory::class, 'category_id');
    }

    public function steps()
    {
        return $this->hasMany(OpportunityStep::class)->orderBy('step_number');
    }

    public function userMatches()
    {
        return $this->hasMany(UserOpportunityMatch::class);
    }

    public function isDeadlineSoon(): bool
    {
        return $this->apply_end && $this->apply_end->diffInDays(now()) <= 7;
    }

    public function daysToApply(): ?int
    {
        return $this->apply_end ? now()->diffInDays($this->apply_end, false) : null;
    }

    public function toSearchableArray(): array
    {
        return [
            'name'           => $this->name,
            'hindi_name'     => $this->hindi_name,
            'post_name'      => $this->post_name,
            'conducting_body'=> $this->conducting_body,
            'description'    => $this->description,
        ];
    }
}