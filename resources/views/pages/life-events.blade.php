 
<x-app-layout title="Life Events">

    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-2xl font-bold mb-2">🌟 Life Events</h1>
            <p class="text-blue-200 text-sm">Major life changes unlock new government scheme bundles</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-10">

        <div class="text-center mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-2">What's happening in your life?</h2>
            <p class="text-gray-500 text-sm">Select a life event to discover schemes you're now eligible for</p>
        </div>

        <div id="events-grid" class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            <!-- Loaded via API -->
        </div>

        <!-- Result Panel -->
        <div id="event-result" class="hidden bg-white border-2 border-orange-400 rounded-2xl p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-2" id="result-title"></h3>
            <p class="text-gray-500 text-sm mb-4">These schemes are now available for you:</p>
            <div id="result-schemes" class="space-y-2 mb-4"></div>
            <div class="flex gap-3">
                <a href="/dashboard" class="flex-1 text-center bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg text-sm font-semibold transition">
                    View Dashboard →
                </a>
                <a href="/schemes" class="flex-1 text-center border border-gray-300 text-gray-600 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                    Browse All Schemes
                </a>
            </div>
        </div>

        <!-- Info Banner -->
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 text-center">
            <div class="text-2xl mb-2">💡</div>
            <h3 class="font-semibold text-blue-800 mb-1">Why Life Events Matter</h3>
            <p class="text-blue-600 text-sm">When life changes, your government entitlements change too. A new baby, job loss, marriage, or retirement — each unlocks a new bundle of schemes worth lakhs of rupees.</p>
        </div>

    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');

        const eventEmojis = {
            baby_born: '👶',
            job_lost: '💼',
            married: '💍',
            retired: '👴',
            disability: '♿',
            farmer: '🌾',
        };

        const eventColors = {
            baby_born: 'border-pink-300 hover:border-pink-500',
            job_lost: 'border-red-300 hover:border-red-500',
            married: 'border-purple-300 hover:border-purple-500',
            retired: 'border-blue-300 hover:border-blue-500',
            disability: 'border-green-300 hover:border-green-500',
            farmer: 'border-yellow-300 hover:border-yellow-500',
        };

        async function loadEvents() {
            try {
                const res = await fetch('/api/v1/life-events', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                const events = data.data || [];

                document.getElementById('events-grid').innerHTML = events.map(event => `
                    <button onclick="triggerEvent('${event.type}', '${event.label}')"
                        class="bg-white border-2 ${eventColors[event.type] || 'border-gray-300 hover:border-orange-400'} rounded-2xl p-6 text-center transition hover:shadow-md cursor-pointer">
                        <div class="text-4xl mb-3">${eventEmojis[event.type] || '🌟'}</div>
                        <div class="font-bold text-gray-800 text-sm">${event.label.replace(/_/g, ' ')}</div>
                        <div class="text-xs text-gray-400 mt-1">${event.schemes.length} schemes available</div>
                    </button>
                `).join('');
            } catch(e) {}
        }

        async function triggerEvent(type, label) {
            if (!token) {
                window.location.href = '/login';
                return;
            }

            try {
                const res = await fetch(`/api/v1/life-events/${type}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const data = await res.json();

                if (data.success) {
                    document.getElementById('result-title').textContent =
                        '🎯 Schemes for: ' + label.replace(/_/g, ' ');

                    document.getElementById('result-schemes').innerHTML = data.schemes.map(scheme => `
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-green-600">✅</span>
                            <span class="text-sm text-gray-700 font-medium">${scheme}</span>
                        </div>
                    `).join('');

                    document.getElementById('event-result').classList.remove('hidden');
                    document.getElementById('event-result').scrollIntoView({ behavior: 'smooth' });
                }
            } catch(e) {}
        }

        loadEvents();
    </script>

</x-app-layout>
