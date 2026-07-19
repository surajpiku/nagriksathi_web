<x-app-layout title="Government Portal Status">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold">🌐 Government Portal Status</h1>
                <p class="text-blue-200 text-sm">Live status of all major government portals</p>
            </div>
            <div class="flex gap-3">
                <a href="/csc/dashboard" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    ← Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">

        <!-- Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-3xl font-bold text-green-600" id="count-online">—</div>
                <div class="text-xs text-gray-500 mt-1">🟢 Online</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-3xl font-bold text-yellow-500" id="count-slow">—</div>
                <div class="text-xs text-gray-500 mt-1">🟡 Slow</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-3xl font-bold text-red-500" id="count-down">—</div>
                <div class="text-xs text-gray-500 mt-1">🔴 Down</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-3xl font-bold text-gray-400" id="count-unknown">—</div>
                <div class="text-xs text-gray-500 mt-1">⚪ Unknown</div>
            </div>
        </div>

        <!-- Last Updated -->
        <div class="text-center text-xs text-gray-400 mb-6" id="last-updated">
            Loading portal statuses...
        </div>

        <!-- Portal List -->
        <div id="portals-list" class="space-y-3">
            <!-- Loaded via JS -->
            @for($i = 0; $i < 6; $i++)
            <div class="bg-white border border-gray-200 rounded-xl p-4 animate-pulse">
                <div class="flex items-center gap-4">
                    <div class="w-4 h-4 bg-gray-200 rounded-full"></div>
                    <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                    <div class="w-20 h-4 bg-gray-200 rounded"></div>
                </div>
            </div>
            @endfor
        </div>

        <!-- Info -->
        <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-4 text-center">
            <p class="text-xs text-blue-600">
                Status is checked every 15 minutes automatically.
                🟢 Online = Working fine | 🟡 Slow = High traffic | 🔴 Down = Currently unavailable
            </p>
        </div>

    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');

        loadPortalStatus();

        async function loadPortalStatus() {
            try {
                const res = await fetch('/api/v1/csc/toolkit/portal-status', {
    headers: { 'Accept': 'application/json' }
});
                const data = await res.json();

                if (!data.success) return;

                const summary = data.summary;
                document.getElementById('count-online').textContent  = summary.online;
                document.getElementById('count-slow').textContent    = summary.slow;
                document.getElementById('count-down').textContent    = summary.down;
                document.getElementById('count-unknown').textContent = summary.unknown;

                if (data.last_updated) {
                    document.getElementById('last-updated').textContent =
                        'Last checked: ' + new Date(data.last_updated).toLocaleString('en-IN');
                } else {
                    document.getElementById('last-updated').textContent =
                        'Status not checked yet — run: php artisan portals:check-status';
                }

                const list = document.getElementById('portals-list');
                list.innerHTML = data.portals.map(p => {
                    const emoji = { online: '🟢', slow: '🟡', down: '🔴', unknown: '⚪' }[p.status] || '⚪';
                    const color = {
                        online:  'border-green-200 bg-green-50',
                        slow:    'border-yellow-200 bg-yellow-50',
                        down:    'border-red-200 bg-red-50',
                        unknown: 'border-gray-200 bg-gray-50',
                    }[p.status] || 'border-gray-200 bg-gray-50';

                    const timeText = p.response_time_ms
                        ? `${p.response_time_ms}ms`
                        : 'Not checked';

                    const downText = p.status === 'down' && p.down_since
                        ? `Down since: ${new Date(p.down_since).toLocaleString('en-IN')}`
                        : '';

                    return `
                        <div class="bg-white border ${color} rounded-xl p-4 flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">${emoji}</span>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">${p.portal_name}</div>
                                    <div class="text-xs text-gray-400">${p.portal_url}</div>
                                    ${downText ? `<div class="text-xs text-red-500 font-medium">${downText}</div>` : ''}
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-400">${timeText}</span>
                                <a href="${p.portal_url}" target="_blank"
                                   class="text-xs text-blue-600 hover:underline font-medium">
                                    Open →
                                </a>
                            </div>
                        </div>
                    `;
                }).join('');

            } catch(e) {
                document.getElementById('last-updated').textContent = 'Error loading portal status';
            }
        }
    </script>

</x-app-layout>
