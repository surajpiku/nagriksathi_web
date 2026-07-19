<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Scheme;
use App\Models\Opportunity;
use Illuminate\Support\Facades\Artisan;

class SchemeCommandCenter extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-command-line';
    protected static ?string $navigationLabel = 'Command Center';
    protected static ?string $navigationGroup = 'Schemes';
    protected static ?string $title           = 'Scheme & Job Command Center';
    protected static ?int    $navigationSort  = 10;
    protected static string  $view            = 'filament.pages.scheme-command-center';

    public array $commandOutputs = [];
    public bool  $isRunning      = false;

    public function getStats(): array
    {
        return [
            'total_schemes'       => Scheme::count(),
            'active_schemes'      => Scheme::where('is_active', true)->count(),
            'central_schemes'     => Scheme::where('is_central', true)->count(),
            'state_schemes'       => Scheme::where('is_central', false)->count(),
            'total_opportunities' => Opportunity::count(),
            'active_opportunities'=> Opportunity::where('is_active', true)->count(),
            'expired_opportunities'=> Opportunity::where('apply_end', '<', now()->toDateString())->where('is_active', true)->count(),
        ];
    }

    public function getCommands(): array
    {
        return [
            'schemes' => [
                'title'    => '📋 Scheme Fetching Commands',
                'commands' => [
                    [
                        'key'         => 'fetch_myscheme',
                        'command'     => 'schemes:fetch-myscheme --limit=50',
                        'label'       => 'Fetch MyScheme.gov.in (50)',
                        'description' => 'Fetch 50 schemes from official MyScheme.gov.in API. Falls back to AI if API unavailable.',
                        'color'       => 'primary',
                        'icon'        => 'heroicon-o-arrow-down-tray',
                    ],
                    [
                        'key'         => 'fetch_myscheme_all',
                        'command'     => 'schemes:fetch-myscheme --all',
                        'label'       => 'Fetch MyScheme.gov.in (ALL)',
                        'description' => 'Fetch ALL schemes from MyScheme.gov.in across all pages. Takes 5-10 minutes.',
                        'color'       => 'primary',
                        'icon'        => 'heroicon-o-arrow-down-tray',
                    ],
                    [
                        'key'         => 'fetch_dbt',
                        'command'     => 'schemes:fetch-dbt --count=30',
                        'label'       => 'Fetch DBT Bharat Schemes',
                        'description' => 'Fetch Direct Benefit Transfer schemes from dbtbharat.gov.in. Cash transfer schemes only.',
                        'color'       => 'success',
                        'icon'        => 'heroicon-o-banknotes',
                    ],
                    [
                        'key'         => 'fetch_nsp',
                        'command'     => 'schemes:fetch-nsp --count=40',
                        'label'       => 'Fetch NSP Scholarships',
                        'description' => 'Fetch scholarships from National Scholarship Portal. Pre/Post matric, SC/ST/OBC/Minority.',
                        'color'       => 'warning',
                        'icon'        => 'heroicon-o-academic-cap',
                    ],
                    [
                        'key'         => 'fetch_ministry_rss',
                        'command'     => 'schemes:scrape-ministry-rss',
                        'label'       => 'Scrape Ministry RSS Feeds',
                        'description' => 'Scrape PIB and india.gov.in RSS feeds for new scheme announcements. Runs daily at 6AM.',
                        'color'       => 'info',
                        'icon'        => 'heroicon-o-rss',
                    ],
                    [
                        'key'         => 'fetch_state_bihar',
                        'command'     => 'schemes:fetch-state --state=Bihar',
                        'label'       => 'Fetch Bihar Schemes',
                        'description' => 'Fetch 15 Bihar-specific state schemes via AI discovery. Mukhyamantri schemes, state pensions etc.',
                        'color'       => 'warning',
                        'icon'        => 'heroicon-o-map-pin',
                    ],
                    [
                        'key'         => 'fetch_state_up',
                        'command'     => 'schemes:fetch-state --state=Uttar Pradesh',
                        'label'       => 'Fetch UP Schemes',
                        'description' => 'Fetch 15 Uttar Pradesh state-specific schemes via AI.',
                        'color'       => 'warning',
                        'icon'        => 'heroicon-o-map-pin',
                    ],
                    [
                        'key'         => 'fetch_all_states',
                        'command'     => 'schemes:fetch-state --all',
                        'label'       => 'Fetch ALL States (23)',
                        'description' => 'Fetch 15 schemes for each of 23 states. Takes 10-15 minutes. Runs monthly.',
                        'color'       => 'danger',
                        'icon'        => 'heroicon-o-globe-asia-australia',
                    ],
                    [
                        'key'         => 'ai_discover',
                        'command'     => 'schemes:ai-discover --count=10',
                        'label'       => 'AI Discover Schemes',
                        'description' => 'Use Claude AI to discover new central schemes and update existing ones with fresh data.',
                        'color'       => 'purple',
                        'icon'        => 'heroicon-o-sparkles',
                    ],
                    [
                        'key'         => 'check_deadlines',
                        'command'     => 'schemes:check-deadlines',
                        'label'       => 'Check & Expire Deadlines',
                        'description' => 'Deactivate schemes past their deadline. Runs daily automatically.',
                        'color'       => 'gray',
                        'icon'        => 'heroicon-o-clock',
                    ],
                ],
            ],
            'opportunities' => [
                'title'    => '💼 Job & Opportunity Commands',
                'commands' => [
                    [
                        'key'         => 'scrape_rss',
                        'command'     => 'opportunities:scrape-rss',
                        'label'       => 'Scrape Job RSS Feeds',
                        'description' => 'Scrape SarkariResult, FreeJobAlert, RojgarResult RSS feeds. Runs daily at 7AM.',
                        'color'       => 'primary',
                        'icon'        => 'heroicon-o-briefcase',
                    ],
                    [
                        'key'         => 'ai_discover_opp',
                        'command'     => 'opportunities:ai-discover --count=10',
                        'label'       => 'AI Discover Jobs',
                        'description' => 'Use Claude AI to discover new government job opportunities. Runs weekly.',
                        'color'       => 'success',
                        'icon'        => 'heroicon-o-sparkles',
                    ],
                    [
                        'key'         => 'expire_opportunities',
                        'command'     => 'opportunities:check-deadlines',
                        'label'       => 'Expire Past Deadline Jobs',
                        'description' => 'Deactivate all opportunities where apply_end date has passed.',
                        'color'       => 'danger',
                        'icon'        => 'heroicon-o-trash',
                    ],
                ],
            ],
        ];
    }

    public function runCommand(string $commandKey): void
    {
        $allCommands = collect($this->getCommands())
            ->flatMap(fn ($group) => $group['commands'])
            ->keyBy('key');

        $cmd = $allCommands->get($commandKey);
        if (!$cmd) {
            Notification::make()->title('Command not found')->danger()->send();
            return;
        }

        try {
            $parts  = explode(' ', $cmd['command']);
            $artisan = array_shift($parts);

            // Parse options
            $options = [];
            foreach ($parts as $part) {
                if (str_starts_with($part, '--')) {
                    $option = ltrim($part, '--');
                    if (str_contains($option, '=')) {
                        [$key, $value] = explode('=', $option, 2);
                        $options['--' . $key] = $value;
                    } else {
                        $options['--' . $option] = true;
                    }
                }
            }

            Artisan::call($artisan, $options);
            $output = Artisan::output();

            $this->commandOutputs[$commandKey] = [
                'output'  => $output ?: 'Command completed with no output.',
                'success' => true,
                'time'    => now()->format('H:i:s'),
            ];

            Notification::make()
                ->title('Command completed: ' . $cmd['label'])
                ->success()
                ->send();

        } catch (\Exception $e) {
            $this->commandOutputs[$commandKey] = [
                'output'  => 'Error: ' . $e->getMessage(),
                'success' => false,
                'time'    => now()->format('H:i:s'),
            ];

            Notification::make()
                ->title('Command failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function clearOutput(string $commandKey): void
    {
        unset($this->commandOutputs[$commandKey]);
    }
}