<?php

namespace App\Console\Commands;

use App\Models\Scheme;
use App\Models\Alert;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckSchemeDeadlines extends Command
{
    protected $signature   = 'schemes:check-deadlines';
    protected $description = 'Check scheme deadlines and document expiry';

    public function handle()
    {
        // Mark expired schemes
        $expired = Scheme::where('is_active', true)
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->get();

        foreach ($expired as $scheme) {
            $scheme->update(['is_active' => false]);
            $this->info("❌ Scheme expired: {$scheme->name}");
        }

        // Alert about schemes closing in 30 days
        $closing = Scheme::where('is_active', true)
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [now(), now()->addDays(30)])
            ->get();

        foreach ($closing as $scheme) {
            $matches = $scheme->userMatches()
                ->where('eligibility_status', 'eligible')
                ->get();

            foreach ($matches as $match) {
                Alert::firstOrCreate(
                    [
                        'user_id' => $match->user_id,
                        'type'    => 'deadline',
                        'title'   => "Scheme closing: {$scheme->name}",
                    ],
                    [
                        'message' => "Apply before {$scheme->deadline->format('d M Y')}. Don't miss this benefit!",
                        'urgency' => 'high',
                        'sent_at' => now(),
                        'data_json' => ['scheme_id' => $scheme->id],
                    ]
                );
            }
            $this->info("⚠️ Scheme deadline alert: {$scheme->name}");
        }

        // Document expiry alerts
        $expiringDocs = \App\Models\UserDocument::whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(30))
            ->whereDate('expiry_date', '>=', now())
            ->get();

        foreach ($expiringDocs as $doc) {
            Alert::firstOrCreate(
                [
                    'user_id' => $doc->user_id,
                    'type'    => 'renewal',
                    'title'   => "Document expiring: {$doc->doc_type}",
                ],
                [
                    'message' => "Your {$doc->doc_type} expires on {$doc->expiry_date->format('d M Y')}. Renew it soon!",
                    'urgency' => $doc->expiry_date->diffInDays(now()) <= 7 ? 'critical' : 'high',
                    'sent_at' => now(),
                ]
            );
            $this->info("📄 Doc expiry alert: {$doc->doc_type} for user {$doc->user_id}");
        }

        $this->info('✅ Scheme deadline check complete!');
        return 0;
    }
}