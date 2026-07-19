<x-filament-panels::page>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @php $stats = $this->getStats(); @endphp

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center shadow-sm">
            <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_schemes']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Schemes</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center shadow-sm">
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['active_schemes']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Active Schemes</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center shadow-sm">
            <div class="text-3xl font-bold text-orange-500">{{ number_format($stats['central_schemes']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Central Schemes</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center shadow-sm">
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['state_schemes']) }}</div>
            <div class="text-xs text-gray-500 mt-1">State Schemes</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center shadow-sm">
            <div class="text-3xl font-bold text-blue-500">{{ number_format($stats['total_opportunities']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Jobs</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center shadow-sm">
            <div class="text-3xl font-bold text-green-500">{{ number_format($stats['active_opportunities']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Active Jobs</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center shadow-sm">
            <div class="text-3xl font-bold text-red-500">{{ number_format($stats['expired_opportunities']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Expired Jobs (Active)</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center shadow-sm">
            <div class="text-3xl font-bold text-gray-400">{{ now()->format('H:i') }}</div>
            <div class="text-xs text-gray-500 mt-1">Server Time</div>
        </div>
    </div>

    {{-- Command Groups --}}
    @foreach($this->getCommands() as $groupKey => $group)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm mb-6">

        {{-- Group Header --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">{{ $group['title'] }}</h2>
        </div>

        {{-- Commands --}}
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($group['commands'] as $cmd)
            <div class="px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-800 dark:text-gray-100 text-sm">{{ $cmd['label'] }}</span>
                        </div>
                        <div class="text-xs text-gray-500 mb-2">{{ $cmd['description'] }}</div>
                        <code class="text-xs bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 px-2 py-1 rounded font-mono">
                            php artisan {{ $cmd['command'] }}
                        </code>
                    </div>
                    <div class="flex-shrink-0">
                        <button
                            wire:click="runCommand('{{ $cmd['key'] }}')"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-wait"
                            wire:target="runCommand('{{ $cmd['key'] }}')"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 disabled:opacity-50"
                        >
                            <span wire:loading wire:target="runCommand('{{ $cmd['key'] }}')">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            <span wire:loading.remove wire:target="runCommand('{{ $cmd['key'] }}')">▶</span>
                            <span wire:loading wire:target="runCommand('{{ $cmd['key'] }}')">Running...</span>
                            <span wire:loading.remove wire:target="runCommand('{{ $cmd['key'] }}')">Run</span>
                        </button>
                    </div>
                </div>

                {{-- Output --}}
                @if(isset($commandOutputs[$cmd['key']]))
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-medium {{ $commandOutputs[$cmd['key']]['success'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $commandOutputs[$cmd['key']]['success'] ? '✅ Completed' : '❌ Failed' }}
                            at {{ $commandOutputs[$cmd['key']]['time'] }}
                        </span>
                        <button wire:click="clearOutput('{{ $cmd['key'] }}')" class="text-xs text-gray-400 hover:text-gray-600">
                            Clear
                        </button>
                    </div>
                    <pre class="text-xs bg-gray-900 text-green-400 p-3 rounded-lg overflow-x-auto max-h-48 overflow-y-auto whitespace-pre-wrap">{{ $commandOutputs[$cmd['key']]['output'] }}</pre>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Schedule Info --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-5">
        <h3 class="font-bold text-blue-800 dark:text-blue-200 mb-3">⏰ Automatic Schedule</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-blue-700 dark:text-blue-300">
            <div>🕕 6:00 AM Daily — Ministry RSS feeds</div>
            <div>🕖 7:00 AM Daily — Job RSS feeds</div>
            <div>📅 Every Monday — MyScheme fetch (50)</div>
            <div>📅 Every Wednesday — DBT schemes</div>
            <div>📅 Every Friday — NSP scholarships</div>
            <div>📅 Monthly — All 23 states fetch</div>
        </div>
    </div>

</x-filament-panels::page>