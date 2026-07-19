<?php

namespace App\Console\Commands;

use App\Models\Opportunity;
use App\Models\Alert;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckOpportunityDeadlines extends Command
{
    protected $signature   = 'opportunities:check-deadlines';
    protected $description = 'Check opportunity deadlines and send alerts';

    public function handle()
    {
        // 1 — Mark expired opportunities as inactive
        $expired = Opportunity::where('is_active', true)
            ->whereNotNull('apply_end')
            ->where('apply_end', '<', now())
            ->get();

        foreach ($expired as $opp) {
            $opp->update(['is_active' => false]);
            $this->info("❌ Expired: {$opp->name}");
        }

        // 2 — Alert users about deadlines in 7 days
        $closing = Opportunity::where('is_active', true)
            ->whereNotNull('apply_end')
            ->whereBetween('apply_end', [now(), now()->addDays(7)])
            ->get();

        foreach ($closing as $opp) {
            // Find matched users
            $matches = $opp->userMatches()
                ->where('eligibility_status', 'eligible')
                ->with('user')
                ->get();

            foreach ($matches as $match) {
                Alert::firstOrCreate(
                    [
                        'user_id' => $match->user_id,
                        'type'    => 'deadline',
                        'title'   => "Last chance: {$opp->name}",
                    ],
                    [
                        'message' => "Apply by {$opp->apply_end->format('d M Y')} — only {$opp->apply_end->diffInDays(now())} days left!",
                        'urgency' => 'critical',
                        'sent_at' => now(),
                        'data_json' => ['opportunity_id' => $opp->id, 'apply_url' => $opp->apply_url],
                    ]
                );
            }
            $this->info("⚠️ Deadline alert sent: {$opp->name}");
        }

        // 3 — Auto clone expiring opportunities for next year
        $expiringSoon = Opportunity::where('is_active', true)
            ->whereNotNull('apply_end')
            ->whereBetween('apply_end', [now(), now()->addDays(30)])
            ->get();

        foreach ($expiringSoon as $opp) {
            $nextYear = now()->year + 1;
            $newSlug  = preg_replace('/\d{4}/', $nextYear, $opp->slug);

            // Only create if doesn't exist yet
            if (!Opportunity::where('slug', $newSlug)->exists()) {
                Opportunity::create([
                    'name'                   => preg_replace('/\d{4}/', $nextYear, $opp->name),
                    'hindi_name'             => $opp->hindi_name ? preg_replace('/\d{4}/', $nextYear, $opp->hindi_name) : null,
                    'slug'                   => $newSlug,
                    'category_id'            => $opp->category_id,
                    'conducting_body'        => $opp->conducting_body,
                    'post_name'              => $opp->post_name,
                    'level'                  => $opp->level,
                    'state_code'             => $opp->state_code,
                    'district'               => $opp->district,
                    'local_level'            => $opp->local_level,
                    'description'            => $opp->description,
                    'eligibility_rules_json' => $opp->eligibility_rules_json,
                    'documents_required_json'=> $opp->documents_required_json,
                    'salary_range'           => $opp->salary_range,
                    'job_location'           => $opp->job_location,
                    'official_site'          => $opp->official_site,
                    'apply_url'              => $opp->official_site, // Reset to home page
                    'is_active'              => false, // Draft until admin confirms dates
                    'is_featured'            => $opp->is_featured,
                ]);
                $this->info("🔄 Auto-drafted next year: {$newSlug}");
            }
        }

        $this->info('✅ Deadline check complete!');
        return 0;
    }
}