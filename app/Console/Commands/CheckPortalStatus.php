<?php

namespace App\Console\Commands;

use App\Models\PortalStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckPortalStatus extends Command
{
    protected $signature   = 'portals:check-status';
    protected $description = 'Check status of all government portals';

    public function handle()
    {
        $this->info('🔍 Checking portal statuses...');

        $portals = PortalStatus::where('is_active', true)->get();

        foreach ($portals as $portal) {
            try {
                $start    = microtime(true);
                $response = Http::timeout(10)->get($portal->check_url);
                $time     = round((microtime(true) - $start) * 1000);

                $status = match(true) {
                    !$response->successful() => 'down',
                    $time > 5000             => 'slow',
                    $time > 2000             => 'slow',
                    default                  => 'online',
                };

                $portal->update([
                    'status'           => $status,
                    'response_time_ms' => $time,
                    'last_checked_at'  => now(),
                    'down_since'       => $status === 'down' && !$portal->down_since
                                            ? now() : ($status !== 'down' ? null : $portal->down_since),
                ]);

                $emoji = match($status) {
                    'online' => '🟢',
                    'slow'   => '🟡',
                    'down'   => '🔴',
                    default  => '⚪',
                };

                $this->info("{$emoji} {$portal->portal_name} — {$status} ({$time}ms)");

            } catch (\Exception $e) {
                $portal->update([
                    'status'         => 'down',
                    'last_checked_at'=> now(),
                    'down_since'     => $portal->down_since ?? now(),
                ]);
                $this->warn("🔴 {$portal->portal_name} — unreachable");
            }
        }

        $this->info('✅ Portal check complete!');
        return 0;
    }
}