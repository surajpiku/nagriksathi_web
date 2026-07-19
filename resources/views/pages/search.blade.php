<x-app-layout title="Smart Search">

    <!-- Header -->
    <div class="bg-blue-900 text-white py-10">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold mb-2">🔍 Smart Search</h1>
            <p class="text-blue-200 text-sm mb-6">Search 500+ schemes, documents, services in Hindi or English</p>

            <!-- Search Bar -->
            <div class="bg-white rounded-xl shadow-lg p-2 flex gap-2">
                <input type="text"
                    id="search-input"
                    placeholder="Search schemes... e.g. 'PM Kisan', 'housing loan', 'scholarship'"
                    class="flex-1 px-4 py-3 text-gray-700 outline-none text-sm rounded-lg"
                    oninput="handleSearch(this.value)"
                    onkeydown="handleKeyDown(event)">
                <button onclick="doSearch()"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold text-sm transition">
                    Search
                </button>
            </div>

            <!-- Live Suggestions -->
            <div id="suggestions-box" class="hidden bg-white rounded-xl shadow-lg mt-1 text-left overflow-hidden">
            </div>

            <!-- Trending -->
            <div class="mt-4 flex flex-wrap gap-2 justify-center">
                <span class="text-blue-300 text-xs">Trending:</span>
                <button onclick="quickSearch('PM Kisan')" class="bg-white/10 hover:bg-white/20 text-white text-xs px-3 py-1 rounded-full transition">PM Kisan</button>
                <button onclick="quickSearch('Ayushman Bharat')" class="bg-white/10 hover:bg-white/20 text-white text-xs px-3 py-1 rounded-full transition">Ayushman Bharat</button>
                <button onclick="quickSearch('scholarship')" class="bg-white/10 hover:bg-white/20 text-white text-xs px-3 py-1 rounded-full transition">Scholarship</button>
                <button onclick="quickSearch('housing')" class="bg-white/10 hover:bg-white/20 text-white text-xs px-3 py-1 rounded-full transition">Housing</button>
                <button onclick="quickSearch('MGNREGA')" class="bg-white/10 hover:bg-white/20 text-white text-xs px-3 py-1 rounded-full transition">MGNREGA</button>
                <button onclick="quickSearch('pension')" class="bg-white/10 hover:bg-white/20 text-white text-xs px-3 py-1 rounded-full transition">Pension</button>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">

        <!-- Category Filter Pills -->
        <div class="flex gap-2 flex-wrap mb-6">
            <button onclick="filterCategory('')"
                id="cat-all"
                class="px-4 py-1.5 rounded-full text-xs font-semibold bg-orange-500 text-white border border-orange-500">
                All Categories
            </button>
            @foreach($categories as $category)
            <button onclick="filterCategory('{{ $category->id }}')"
                id="cat-{{ $category->id }}"
                class="px-4 py-1.5 rounded-full text-xs font-semibold bg-white text-gray-600 border border-gray-300 hover:border-orange-400 hover:text-orange-500 transition">
                {{ $category->icon }} {{ $category->name }}
            </button>
            @endforeach
        </div>

        <!-- Initial State -->
        <div id="initial-state" class="text-center py-16">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="font-bold text-gray-700 text-lg mb-2">Search Government Schemes</h3>
            <p class="text-gray-400 text-sm">Type above to search 500+ central and state schemes</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 max-w-2xl mx-auto">
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl mb-1">💰</div>
                    <div class="text-xs font-semibold text-gray-600">Financial</div>
                    <div class="text-xs text-gray-400">5 schemes</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl mb-1">🏥</div>
                    <div class="text-xs font-semibold text-gray-600">Health</div>
                    <div class="text-xs text-gray-400">3 schemes</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl mb-1">📚</div>
                    <div class="text-xs font-semibold text-gray-600">Education</div>
                    <div class="text-xs text-gray-400">3 schemes</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl mb-1">🌾</div>
                    <div class="text-xs font-semibold text-gray-600">Agriculture</div>
                    <div class="text-xs text-gray-400">4 schemes</div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="hidden text-center py-16">
            <div class="inline-block w-8 h-8 border-4 border-orange-500 border-t-transparent rounded-full animate-spin mb-4"></div>
            <p class="text-gray-400 text-sm">Searching schemes...</p>
        </div>

        <!-- Results -->
        <div id="results-state" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <div class="text-sm text-gray-500">
                    Found <span id="results-count" class="font-bold text-gray-800">0</span> results for
                    "<span id="results-query" class="font-bold text-gray-800"></span>"
                </div>
                <select id="sort-select" onchange="sortResults()" class="border border-gray-300 rounded px-3 py-1.5 text-xs outline-none">
                    <option value="benefit">Highest Benefit</option>
                    <option value="name">A to Z</option>
                </select>
            </div>

            <div id="results-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
        </div>

        <!-- No Results -->
        <div id="no-results-state" class="hidden text-center py-16">
            <div class="text-5xl mb-4">😔</div>
            <h3 class="font-bold text-gray-700 mb-2">No schemes found</h3>
            <p class="text-gray-400 text-sm mb-4">Try different keywords or browse by category</p>
            <a href="/schemes" class="bg-orange-500 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600 transition">
                Browse All Schemes →
            </a>
        </div>

    </div>

    <script>
        let searchTimeout = null;
        let currentCategory = '';
        let allResults = [];

        function quickSearch(term) {
            document.getElementById('search-input').value = term;
            doSearch();
        }

        function handleKeyDown(e) {
            if (e.key === 'Enter') doSearch();
        }

        function handleSearch(value) {
            clearTimeout(searchTimeout);
            hideSuggestions();

            if (value.length < 2) return;

            searchTimeout = setTimeout(() => {
                fetchSuggestions(value);
            }, 300);
        }

        async function fetchSuggestions(q) {
            try {
                const res = await fetch(`/api/v1/search/suggest?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.data && data.data.length > 0) {
                    showSuggestions(data.data);
                }
            } catch(e) {}
        }

        function showSuggestions(suggestions) {
            const box = document.getElementById('suggestions-box');
            box.innerHTML = suggestions.map(s => `
                <button onclick="quickSearch('${s}')"
                    class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-0 flex items-center gap-2">
                    <span class="text-gray-400">🔍</span> ${s}
                </button>
            `).join('');
            box.classList.remove('hidden');
        }

        function hideSuggestions() {
            document.getElementById('suggestions-box').classList.add('hidden');
        }

        async function doSearch() {
            hideSuggestions();
            const q = document.getElementById('search-input').value.trim();
            if (q.length < 2) return;

            showState('loading');

            try {
                const res = await fetch(`/api/v1/search?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                allResults = data.data || [];

                document.getElementById('results-query').textContent = q;
                renderResults(allResults);
            } catch(e) {
                showState('no-results');
            }
        }

        function filterCategory(categoryId) {
            currentCategory = categoryId;

            // Update pill styles
            document.querySelectorAll('[id^="cat-"]').forEach(btn => {
                btn.className = 'px-4 py-1.5 rounded-full text-xs font-semibold bg-white text-gray-600 border border-gray-300 hover:border-orange-400 hover:text-orange-500 transition';
            });
            const activeBtn = document.getElementById(categoryId ? 'cat-' + categoryId : 'cat-all');
            if (activeBtn) activeBtn.className = 'px-4 py-1.5 rounded-full text-xs font-semibold bg-orange-500 text-white border border-orange-500';

            if (allResults.length > 0) renderResults(allResults);
        }

        function sortResults() {
            renderResults(allResults);
        }

        function renderResults(results) {
            let filtered = results;

            if (currentCategory) {
                filtered = results.filter(r => r.category_id == currentCategory);
            }

            const sort = document.getElementById('sort-select').value;
            if (sort === 'benefit') {
                filtered.sort((a, b) => b.benefit_value - a.benefit_value);
            } else {
                filtered.sort((a, b) => a.name.localeCompare(b.name));
            }

            document.getElementById('results-count').textContent = filtered.length;

            if (filtered.length === 0) {
                showState('no-results');
                return;
            }

            showState('results');

            document.getElementById('results-grid').innerHTML = filtered.map(scheme => `
                <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md hover:border-orange-300 transition">
                    <div class="flex items-start justify-between mb-3">
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-medium">
                            ${scheme.benefit_type}
                        </span>
                        <span class="text-green-700 font-bold text-base">
                            ₹${Number(scheme.benefit_value).toLocaleString('en-IN')}
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-0.5">${scheme.name}</h3>
                    <p class="text-xs text-gray-400 mb-2">${scheme.hindi_name || ''}</p>
                    <p class="text-xs text-gray-500 mb-3">${scheme.description?.substring(0, 100)}...</p>
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                        <span class="text-xs text-gray-400">${scheme.ministry || ''}</span>
                        <div class="flex gap-2">
                            <a href="/schemes/${scheme.id}"
                               class="text-blue-600 text-xs font-semibold hover:underline">Details</a>
                            <a href="${scheme.portal_url}" target="_blank"
                               class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-3 py-1 rounded transition">
                                Apply →
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function showState(state) {
            ['initial', 'loading', 'results', 'no-results'].forEach(s => {
                document.getElementById(s + '-state').classList.add('hidden');
            });
            document.getElementById(state + '-state').classList.remove('hidden');
        }

        // Close suggestions on outside click
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#search-input') && !e.target.closest('#suggestions-box')) {
                hideSuggestions();
            }
        });
    </script>

</x-app-layout>