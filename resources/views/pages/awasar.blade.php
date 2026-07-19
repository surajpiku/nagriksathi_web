<x-app-layout title="Sarkari Awasar">

    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center gap-3 mb-2">
                <span class="text-3xl">💼</span>
                <div>
                    <h1 class="text-2xl font-bold" id="page-title">Sarkari Awasar</h1>
                    <p class="text-blue-200 text-sm" id="page-subtitle">Government Jobs, Exams and Scholarships</p>
                </div>
            </div>
        </div>
    </div>

    @if(request('matched') === 'true')
    <div class="bg-blue-50 border-b border-blue-200 py-3">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-blue-600 font-semibold text-sm">Showing your matched opportunities only</span>
                <span id="matched-opp-count" class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full"></span>
            </div>
            <a href="/awasar" class="text-xs text-blue-600 hover:underline">View all opportunities</a>
        </div>
    </div>
    @endif

    <div class="bg-orange-500 text-white py-3">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-sm">
                <div><span class="font-bold text-lg">24+</span><br><span class="text-orange-100 text-xs">Active Opportunities</span></div>
                <div><span class="font-bold text-lg">10</span><br><span class="text-orange-100 text-xs">Categories</span></div>
                <div><span class="font-bold text-lg">2L+</span><br><span class="text-orange-100 text-xs">Total Vacancies</span></div>
                <div><span class="font-bold text-lg">Free</span><br><span class="text-orange-100 text-xs">Always Free</span></div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        @if($deadlines->count() > 0)
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <h3 class="font-bold text-red-700 text-sm mb-3">Apply Now — Deadlines Approaching</h3>
            <div class="flex gap-3 flex-wrap">
                @foreach($deadlines as $d)
                <a href="/awasar/{{ $d->id }}" class="bg-white border border-red-200 rounded-lg px-3 py-2 text-xs hover:border-red-400 transition">
                    <div class="font-semibold text-gray-800">{{ $d->name }}</div>
                    <div class="text-red-500 font-bold">Last Date: {{ $d->apply_end->format('d M Y') }} ({{ $d->apply_end->diffInDays(now()) }} days left)</div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex gap-6">

            <!-- Sidebar Filters -->
            <aside class="w-64 flex-shrink-0 hidden md:block">

                <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm">Search</h3>
                    <input type="text" id="sidebar-search" placeholder="Search by name..."
                        oninput="filterOpps()"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm outline-none focus:border-orange-400">
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm">Category</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="opp_category" value="" checked class="accent-orange-500"
                                onchange="syncCatPill(''); filterOpps();">
                            <span class="text-sm text-gray-600">All Categories</span>
                        </label>
                        @foreach($categories as $cat)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="opp_category" value="{{ $cat->slug }}" class="accent-orange-500"
                                onchange="syncCatPill('{{ $cat->slug }}'); filterOpps();">
                            <span class="text-sm text-gray-600">{{ $cat->icon }} {{ $cat->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm">Level</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="opp_level" value="" checked class="accent-orange-500" onchange="filterOpps()">
                            <span class="text-sm text-gray-600">All India</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="opp_level" value="central" class="accent-orange-500" onchange="filterOpps()">
                            <span class="text-sm text-gray-600">Central Government</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="opp_level" value="state" class="accent-orange-500" onchange="filterOpps()">
                            <span class="text-sm text-gray-600">State Government</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="opp_level" value="district" class="accent-orange-500" onchange="filterOpps()">
                            <span class="text-sm text-gray-600">District Level</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="opp_level" value="local" class="accent-orange-500" onchange="filterOpps()">
                            <span class="text-sm text-gray-600">Local Level</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm">Sort By</h3>
                    <select id="sort-select" onchange="filterOpps()" class="w-full border border-gray-300 rounded px-3 py-2 text-sm outline-none focus:border-orange-400">
                        <option value="deadline">Earliest Deadline</option>
                        <option value="vacancy">Most Vacancies</option>
                        <option value="name">A to Z</option>
                    </select>
                </div>

                <!-- Matched Sidebar -->
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">Matched for You</h3>
                    <div id="matched-opps">
                        <div class="text-center py-6 text-gray-400">
                            <div class="text-3xl mb-2">🔐</div>
                            <div class="text-xs">Login to see matched opportunities</div>
                            <a href="/login" class="mt-2 inline-block bg-orange-500 text-white text-xs px-4 py-1.5 rounded-lg font-semibold">Login Now</a>
                        </div>
                    </div>
                </div>

            </aside>

            <!-- Main Content -->
            <div class="flex-1">

                <!-- Top Bar -->
                <div class="flex items-center justify-between mb-5">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-semibold text-gray-800" id="opps-count">{{ $allOpportunities->count() }}</span> opportunities
                    </div>
                    <a href="/awasar?matched=true" id="view-matched-btn" style="display:none;"
                        class="bg-blue-900 text-white text-xs px-4 py-2 rounded-lg font-semibold hover:bg-blue-800 transition">
                        View My Matched Jobs →
                    </a>
                </div>

                <!-- Category Pills -->
                <div class="flex gap-2 flex-wrap mb-5">
                    <button onclick="filterByCatPill('')"
                        class="cat-pill active px-3 py-1 rounded-full text-xs font-semibold border bg-orange-500 text-white border-orange-500"
                        data-cat="">All</button>
                    @foreach($categories as $cat)
                    <button onclick="filterByCatPill('{{ $cat->slug }}')"
                        class="cat-pill px-3 py-1 rounded-full text-xs font-semibold border bg-white text-gray-600 border-gray-300 hover:border-orange-400"
                        data-cat="{{ $cat->slug }}">{{ $cat->icon }} {{ $cat->name }}</button>
                    @endforeach
                </div>

                <!-- Matched Grid — shown via JS when matched=true -->
                <div id="opps-matched-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 {{ request('matched') === 'true' ? '' : 'hidden' }}"></div>

                <!-- Normal view — flat list with data attributes for JS filtering -->
                <div id="opps-normal" class="{{ request('matched') === 'true' ? 'hidden' : '' }}">

                    <!-- Featured -->
                    @if($featured->count() > 0)
                    <h2 class="font-bold text-gray-800 text-lg mb-4">Featured Opportunities</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" id="featured-grid">
                        @foreach($featured as $opp)
                        <a href="/awasar/{{ $opp->id }}"
                            class="opp-card bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md hover:border-orange-300 transition block"
                            data-name="{{ strtolower($opp->name) }}"
                            data-category="{{ $opp->category->slug }}"
                            data-level="{{ $opp->level }}"
                            data-vacancy="{{ $opp->vacancy_count ?? 0 }}"
                            data-deadline="{{ $opp->apply_end ? $opp->apply_end->timestamp : 9999999999 }}">
                            <div class="flex items-start justify-between mb-3">
                                <span class="text-xl">{{ $opp->category->icon }}</span>
                                @if($opp->apply_end && $opp->apply_end->diffInDays(now()) <= 7)
                                <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full font-semibold animate-pulse">Closing Soon!</span>
                                @else
                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-medium">{{ ucfirst($opp->level) }}</span>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-800 text-sm mb-0.5">{{ $opp->name }}</h3>
                            <p class="text-xs text-gray-400 mb-2">{{ $opp->conducting_body }} — {{ $opp->post_name }}</p>
                            <p class="text-xs text-gray-500 mb-3">{{ Str::limit($opp->description, 80) }}</p>
                            <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                                @if($opp->vacancy_count)
                                <div class="bg-gray-50 rounded px-2 py-1"><span class="text-gray-400">Vacancies</span><div class="font-bold text-gray-700">{{ number_format($opp->vacancy_count) }}</div></div>
                                @endif
                                @if($opp->salary_range)
                                <div class="bg-green-50 rounded px-2 py-1"><span class="text-gray-400">Salary</span><div class="font-bold text-green-700 truncate">{{ $opp->salary_range }}</div></div>
                                @endif
                            </div>
                            @if($opp->apply_end)
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <span class="text-xs text-red-500 font-medium">Last Date: {{ $opp->apply_end->format('d M Y') }}</span>
                                <span class="text-orange-500 text-xs font-bold">View Details</span>
                            </div>
                            @endif
                        </a>
                        @endforeach
                    </div>
                    @endif

                    <!-- All Opportunities — flat list with data attributes -->
                    <h2 class="font-bold text-gray-800 text-lg mb-4">All Opportunities</h2>
                    <div id="all-opps-list" class="space-y-2">
                        @foreach($allOpportunities as $opp)
                        <a href="/awasar/{{ $opp->id }}"
                            class="opp-card flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-orange-300 hover:shadow-sm transition"
                            data-name="{{ strtolower($opp->name) }}"
                            data-category="{{ $opp->category->slug }}"
                            data-level="{{ $opp->level }}"
                            data-vacancy="{{ $opp->vacancy_count ?? 0 }}"
                            data-deadline="{{ $opp->apply_end ? $opp->apply_end->timestamp : 9999999999 }}">
                            <div class="flex items-center gap-3 flex-1">
                                <span class="text-xl flex-shrink-0">{{ $opp->category->icon }}</span>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">{{ $opp->name }}</div>
                                    <div class="text-xs text-gray-400 flex items-center gap-2 flex-wrap">
                                        <span>{{ $opp->conducting_body }}</span>
                                        @if($opp->vacancy_count)<span>•</span><span>{{ number_format($opp->vacancy_count) }} vacancies</span>@endif
                                        <span>•</span>
                                        <span class="px-1.5 py-0.5 rounded text-xs {{ $opp->level === 'central' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }}">
                                            {{ $opp->level === 'central' ? 'Central' : ($opp->state_code ?? ucfirst($opp->level)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right ml-3 flex-shrink-0">
                                @if($opp->apply_end)
                                <div class="text-xs {{ $opp->apply_end->diffInDays(now()) <= 7 ? 'text-red-500 font-bold animate-pulse' : 'text-orange-500' }}">
                                    {{ $opp->apply_end->format('d M') }}
                                </div>
                                @endif
                                @if($opp->salary_range)
                                <div class="text-xs text-green-700 font-semibold">{{ Str::limit($opp->salary_range, 18) }}</div>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- No results -->
                    <div id="no-results" class="hidden text-center py-16 text-gray-400">
                        <div class="text-4xl mb-3">😔</div>
                        <div class="font-semibold">No opportunities match your filters</div>
                        <button onclick="filterByCatPill('')" class="text-orange-500 text-sm mt-2 hover:underline">Clear filters</button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        var token     = window.nagrik ? window.nagrik.token : localStorage.getItem('nagrik_token');
        var isMatched = new URLSearchParams(window.location.search).get('matched') === 'true';

        if (token) {
            document.getElementById('view-matched-btn').style.display = 'inline-block';
        }

        if (isMatched && token) {
            document.getElementById('page-title').textContent    = 'Your Matched Awasar';
            document.getElementById('page-subtitle').textContent = 'Opportunities matched to your profile';
            loadMatchedOpportunities();
        }

        // Load sidebar matched
        if (token) {
            fetch('/api/v1/opportunities/matched', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
            }).then(function(r) { return r.json(); }).then(function(data) {
                var matches = data.data || [];
                var el = document.getElementById('matched-opps');
                if (matches.length === 0) {
                    el.innerHTML = '<div class="text-center py-4 text-xs text-gray-400">Complete your profile to see matched opportunities</div>';
                    return;
                }
                var html = matches.slice(0, 4).map(function(m) {
                    var opp = m.opportunity;
                    if (!opp) return '';
                    return '<a href="/awasar/' + opp.id + '" class="flex items-center justify-between p-2 rounded-lg hover:bg-orange-50 transition mb-2 block">' +
                        '<div><div class="text-xs font-semibold text-gray-800">' + (opp.name||'') + '</div>' +
                        '<div class="text-xs text-gray-400">' + (opp.conducting_body||'') + '</div></div>' +
                        '<span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full ml-2 whitespace-nowrap">' +
                        (m.eligibility_status === 'eligible' ? 'Eligible' : 'Prepare') + '</span></a>';
                }).join('');
                html += '<a href="/awasar?matched=true" class="block text-center text-xs text-orange-500 hover:underline mt-2 font-semibold">View All ' + matches.length + ' Matched</a>';
                el.innerHTML = html;
            }).catch(function() {});
        }

        async function loadMatchedOpportunities() {
            try {
                var res  = await fetch('/api/v1/opportunities/matched', {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                });
                var data = await res.json();
                if (!data.data || data.data.length === 0) return;

                var countEl = document.getElementById('matched-opp-count');
                if (countEl) countEl.textContent = data.data.length + ' opportunities';
                document.getElementById('opps-count').textContent = data.data.length;

                var grid = document.getElementById('opps-matched-grid');
                grid.innerHTML = data.data.map(function(match) {
                    var opp = match.opportunity;
                    if (!opp) return '';
                    var catSlug = opp.category ? opp.category.slug : '';
                    return '<a href="/awasar/' + opp.id + '"' +
                        ' class="opp-card bg-white border-2 border-blue-200 rounded-xl p-5 hover:shadow-md transition relative block"' +
                        ' data-name="' + (opp.name||'').toLowerCase() + '"' +
                        ' data-category="' + catSlug + '"' +
                        ' data-level="' + (opp.level||'') + '"' +
                        ' data-vacancy="' + (opp.vacancy_count||0) + '"' +
                        ' data-deadline="9999999999">' +
                        '<div class="absolute top-3 right-3 bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">Matched</div>' +
                        '<div class="flex items-start justify-between mb-2">' +
                        '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-medium">' + (opp.level||'central') + '</span>' +
                        (opp.vacancy_count ? '<span class="text-xs text-gray-500">' + opp.vacancy_count + ' vacancies</span>' : '') +
                        '</div>' +
                        '<h3 class="font-bold text-gray-800 text-sm mb-0.5">' + (opp.name||'') + '</h3>' +
                        '<p class="text-xs text-gray-400 mb-1">' + (opp.conducting_body||'') + '</p>' +
                        '<p class="text-xs text-gray-500 mb-3">' + (opp.salary_range||'') + '</p>' +
                        (opp.apply_end ? '<div class="text-xs text-red-500 font-medium mb-2">Last date: ' + new Date(opp.apply_end).toLocaleDateString('en-IN') + '</div>' : '') +
                        '<div class="flex gap-2 pt-2 border-t border-gray-100">' +
                        '<span class="text-blue-600 text-xs font-semibold">Details</span>' +
                        (opp.apply_url ? ' <span class="bg-orange-500 text-white text-xs px-3 py-1 rounded">Apply</span>' : '') +
                        '</div></a>';
                }).join('');
            } catch(e) { console.error(e); }
        }

        function filterByCatPill(slug) {
            document.querySelectorAll('.cat-pill').forEach(function(btn) {
                btn.className = btn.dataset.cat === slug
                    ? 'cat-pill active px-3 py-1 rounded-full text-xs font-semibold border bg-orange-500 text-white border-orange-500'
                    : 'cat-pill px-3 py-1 rounded-full text-xs font-semibold border bg-white text-gray-600 border-gray-300 hover:border-orange-400';
            });
            document.querySelectorAll('input[name="opp_category"]').forEach(function(r) { r.checked = r.value === slug; });
            filterOpps();
        }

        function syncCatPill(slug) {
            document.querySelectorAll('.cat-pill').forEach(function(btn) {
                btn.className = btn.dataset.cat === slug
                    ? 'cat-pill active px-3 py-1 rounded-full text-xs font-semibold border bg-orange-500 text-white border-orange-500'
                    : 'cat-pill px-3 py-1 rounded-full text-xs font-semibold border bg-white text-gray-600 border-gray-300 hover:border-orange-400';
            });
        }

        function filterOpps() {
            var search    = (document.getElementById('sidebar-search') ? document.getElementById('sidebar-search').value : '').toLowerCase().trim();
            var activeCat = document.querySelector('input[name="opp_category"]:checked') ? document.querySelector('input[name="opp_category"]:checked').value : '';
            var level     = document.querySelector('input[name="opp_level"]:checked') ? document.querySelector('input[name="opp_level"]:checked').value : '';
            var sort      = document.getElementById('sort-select') ? document.getElementById('sort-select').value : 'deadline';

            // Get all opp-cards from both featured grid and list
            var allCards = Array.from(document.querySelectorAll('.opp-card'));

            // For matched mode — filter matched grid
            // For normal mode — filter all-opps-list + featured-grid
            allCards.forEach(function(card) {
                var show = true;
                if (search    && card.dataset.name.indexOf(search) === -1)    show = false;
                if (activeCat && card.dataset.category !== activeCat)          show = false;
                if (level     && card.dataset.level !== level)                 show = false;
                card.style.display = show ? '' : 'none';
            });

            var visible = allCards.filter(function(c) { return c.style.display !== 'none'; });
            document.getElementById('opps-count').textContent = visible.length;

            // Show/hide no results
            var noResults = document.getElementById('no-results');
            if (noResults) {
                if (visible.length > 0) noResults.classList.add('hidden');
                else noResults.classList.remove('hidden');
            }

            // Sort all-opps-list items
            var listContainer = document.getElementById('all-opps-list');
            if (listContainer) {
                var listCards = Array.from(listContainer.querySelectorAll('.opp-card'));
                var visibleList = listCards.filter(function(c) { return c.style.display !== 'none'; });
                visibleList.sort(function(a, b) {
                    if (sort === 'deadline') return Number(a.dataset.deadline) - Number(b.dataset.deadline);
                    if (sort === 'vacancy')  return Number(b.dataset.vacancy)  - Number(a.dataset.vacancy);
                    return a.dataset.name.localeCompare(b.dataset.name);
                });
                visibleList.forEach(function(card) { listContainer.appendChild(card); });
            }

            // Sort featured grid too
            var featuredContainer = document.getElementById('featured-grid');
            if (featuredContainer) {
                var featCards = Array.from(featuredContainer.querySelectorAll('.opp-card'));
                var visibleFeat = featCards.filter(function(c) { return c.style.display !== 'none'; });
                visibleFeat.sort(function(a, b) {
                    if (sort === 'deadline') return Number(a.dataset.deadline) - Number(b.dataset.deadline);
                    if (sort === 'vacancy')  return Number(b.dataset.vacancy)  - Number(a.dataset.vacancy);
                    return a.dataset.name.localeCompare(b.dataset.name);
                });
                visibleFeat.forEach(function(card) { featuredContainer.appendChild(card); });
            }

            // For matched grid
            var matchedContainer = document.getElementById('opps-matched-grid');
            if (matchedContainer && isMatched) {
                var matchCards = Array.from(matchedContainer.querySelectorAll('.opp-card'));
                var visibleMatch = matchCards.filter(function(c) { return c.style.display !== 'none'; });
                visibleMatch.sort(function(a, b) {
                    if (sort === 'vacancy') return Number(b.dataset.vacancy) - Number(a.dataset.vacancy);
                    return a.dataset.name.localeCompare(b.dataset.name);
                });
                visibleMatch.forEach(function(card) { matchedContainer.appendChild(card); });
            }
        }
    </script>

</x-app-layout>