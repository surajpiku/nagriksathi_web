<x-app-layout title="Government Schemes">

    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-sm text-blue-300 mb-1">Home → Schemes</div>
            <h1 class="text-2xl font-bold mb-1" id="page-title">Government Schemes</h1>
            <p class="text-blue-200 text-sm" id="page-subtitle">Discover 500+ central and state government schemes you may be eligible for</p>
        </div>
    </div>

    @if(request('matched') === 'true')
    <div class="bg-green-50 border-b border-green-200 py-3">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-green-600 font-semibold text-sm">Showing your matched schemes only</span>
                <span id="matched-count" class="bg-green-600 text-white text-xs px-2 py-0.5 rounded-full"></span>
            </div>
            <a href="/schemes" class="text-xs text-green-600 hover:underline">View all schemes</a>
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex gap-6">

            <aside class="w-64 flex-shrink-0 hidden md:block">

                <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm">Search Schemes</h3>
                    <input type="text" id="sidebar-search" placeholder="Search by name..."
                        oninput="filterSchemes()"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm outline-none focus:border-orange-400">
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm">Category</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="category" value="" checked class="accent-orange-500"
                                onchange="syncPillFromRadio(''); filterSchemes();">
                            <span class="text-sm text-gray-600">All Categories</span>
                        </label>
                        @foreach($categories as $category)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="category" value="{{ $category->slug }}" class="accent-orange-500"
                                onchange="syncPillFromRadio('{{ $category->slug }}'); filterSchemes();">
                            <span class="text-sm text-gray-600">{{ $category->icon }} {{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm">Benefit Type</h3>
                    <div class="space-y-2">
                        @foreach(['cash','loan','insurance','subsidy','scholarship','pension','grant','service','training'] as $type)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" value="{{ $type }}" class="accent-orange-500 benefit-filter" onchange="filterSchemes()">
                            <span class="text-sm text-gray-600 capitalize">{{ $type }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm">Scheme Type</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="scheme_type" value="" checked class="accent-orange-500" onchange="filterSchemes()">
                            <span class="text-sm text-gray-600">All Schemes</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="scheme_type" value="central" class="accent-orange-500" onchange="filterSchemes()">
                            <span class="text-sm text-gray-600">Central Government</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="scheme_type" value="state" class="accent-orange-500" onchange="filterSchemes()">
                            <span class="text-sm text-gray-600">State Government</span>
                        </label>
                    </div>
                </div>

            </aside>

            <div class="flex-1">

                <div class="flex items-center justify-between mb-5">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-semibold text-gray-800" id="schemes-count">{{ $schemes->total() }}</span> schemes
                    </div>
                    <select id="sort-select" onchange="filterSchemes()" class="border border-gray-300 rounded px-3 py-1.5 text-sm outline-none focus:border-orange-400">
                        <option value="benefit">Sort: Highest Benefit</option>
                        <option value="name">Sort: A to Z</option>
                    </select>
                </div>

                <div class="flex gap-2 flex-wrap mb-5">
                    <button onclick="filterByPill('')" class="pill-btn active px-3 py-1 rounded-full text-xs font-semibold border bg-orange-500 text-white border-orange-500" data-cat="">All</button>
                    @foreach($categories as $category)
                    <button onclick="filterByPill('{{ $category->slug }}')"
                        class="pill-btn px-3 py-1 rounded-full text-xs font-semibold border bg-white text-gray-600 border-gray-300 hover:border-orange-400"
                        data-cat="{{ $category->slug }}">{{ $category->icon }} {{ $category->name }}</button>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" id="schemes-grid">
                    @forelse($schemes as $scheme)
                    <div class="scheme-card bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md hover:border-orange-300 transition"
                        data-name="{{ strtolower($scheme->name) }}"
                        data-benefit-type="{{ $scheme->benefit_type }}"
                        data-category="{{ $scheme->category->slug }}"
                        data-is-central="{{ $scheme->is_central ? 'central' : 'state' }}"
                        data-benefit="{{ $scheme->benefit_value }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">{{ $scheme->category->icon }}</span>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-medium">{{ $scheme->benefit_type }}</span>
                            </div>
                            <span class="text-green-700 font-bold text-base">Rs.{{ number_format($scheme->benefit_value) }}</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base mb-0.5">{{ $scheme->name }}</h3>
                        <p class="text-xs text-gray-400 mb-2">{{ $scheme->hindi_name }}</p>
                        <p class="text-sm text-gray-500 mb-3">{{ Str::limit($scheme->description, 100) }}</p>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded">{{ $scheme->ministry }}</span>
                            @if($scheme->is_central)
                            <span class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Central</span>
                            @else
                            <span class="text-xs text-purple-600 bg-purple-50 px-2 py-0.5 rounded">State</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="flex gap-3 text-xs text-gray-500">
                                @if($scheme->helpline)<span>{{ $scheme->helpline }}</span>@endif
                            </div>
                            <div class="flex gap-2">
                                <a href="/schemes/{{ $scheme->id }}" class="text-blue-600 text-xs font-semibold hover:underline">Details</a>
                                <a href="{{ $scheme->portal_url }}" target="_blank" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-3 py-1 rounded transition">Apply</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 text-center py-16 text-gray-400">
                        <div class="text-4xl mb-3">Search</div>
                        <div class="font-semibold">No schemes found</div>
                    </div>
                    @endforelse
                </div>

                <div id="no-results" class="hidden text-center py-16 text-gray-400">
                    <div class="font-semibold">No schemes match your filters</div>
                    <button onclick="filterByPill('')" class="text-orange-500 text-sm mt-2 hover:underline">Clear filters</button>
                </div>

                <div class="flex justify-center" id="pagination-section">
                    {{ $schemes->links() }}
                </div>

            </div>
        </div>
    </div>

    <script>
        var token     = window.nagrik ? window.nagrik.token : localStorage.getItem('nagrik_token');
        var isMatched = new URLSearchParams(window.location.search).get('matched') === 'true';

        if (isMatched && token) {
            document.getElementById('page-title').textContent    = 'Your Matched Schemes';
            document.getElementById('page-subtitle').textContent = 'Schemes you are eligible for based on your profile';
            document.getElementById('pagination-section').style.display = 'none';
            loadMatchedSchemes();
        }

        async function loadMatchedSchemes() {
            try {
                var res  = await fetch('/api/v1/schemes/matched', {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                });
                var data = await res.json();
                if (!data.data || data.data.length === 0) return;

                var countEl = document.getElementById('matched-count');
                if (countEl) countEl.textContent = data.data.length + ' schemes';
                document.getElementById('schemes-count').textContent = data.data.length;

                var grid = document.getElementById('schemes-grid');
                grid.innerHTML = data.data.map(function(match) {
                    var s = match.scheme;
                    if (!s) return '';
                    var catSlug = (s.category && s.category.slug) ? s.category.slug : '';
                    return '<div class="scheme-card bg-white border-2 border-green-200 rounded-xl p-5 hover:shadow-md transition relative"' +
                        ' data-name="' + (s.name||'').toLowerCase() + '"' +
                        ' data-benefit-type="' + (s.benefit_type||'') + '"' +
                        ' data-category="' + catSlug + '"' +
                        ' data-is-central="' + (s.is_central ? 'central' : 'state') + '"' +
                        ' data-benefit="' + (s.benefit_value||0) + '">' +
                        '<div class="absolute top-3 right-3 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full font-semibold">Matched</div>' +
                        '<div class="flex items-start justify-between mb-3">' +
                        '<span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-medium">' + (s.benefit_type||'benefit') + '</span>' +
                        '<span class="text-green-700 font-bold text-base">Rs.' + Number(s.benefit_value||0).toLocaleString('en-IN') + '</span>' +
                        '</div>' +
                        '<h3 class="font-bold text-gray-800 text-base mb-0.5">' + (s.name||'') + '</h3>' +
                        '<p class="text-xs text-gray-400 mb-2">' + (s.hindi_name||'') + '</p>' +
                        '<p class="text-sm text-gray-500 mb-3">' + (s.description||'').substring(0,100) + '...</p>' +
                        '<div class="flex items-center justify-between pt-3 border-t border-gray-100">' +
                        '<span class="text-xs text-gray-400">' + (s.ministry||'') + '</span>' +
                        '<div class="flex gap-2">' +
                        '<a href="/schemes/' + s.id + '" class="text-blue-600 text-xs font-semibold hover:underline">Details</a>' +
                        (s.portal_url ? '<a href="' + s.portal_url + '" target="_blank" class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-3 py-1 rounded transition">Apply</a>' : '') +
                        '</div></div></div>';
                }).join('');
            } catch(e) { console.error(e); }
        }

        function filterByPill(slug) {
            document.querySelectorAll('.pill-btn').forEach(function(btn) {
                btn.className = btn.dataset.cat === slug
                    ? 'pill-btn active px-3 py-1 rounded-full text-xs font-semibold border bg-orange-500 text-white border-orange-500'
                    : 'pill-btn px-3 py-1 rounded-full text-xs font-semibold border bg-white text-gray-600 border-gray-300 hover:border-orange-400';
            });
            document.querySelectorAll('input[name="category"]').forEach(function(r) { r.checked = r.value === slug; });
            filterSchemes();
        }

        function syncPillFromRadio(slug) {
            document.querySelectorAll('.pill-btn').forEach(function(btn) {
                btn.className = btn.dataset.cat === slug
                    ? 'pill-btn active px-3 py-1 rounded-full text-xs font-semibold border bg-orange-500 text-white border-orange-500'
                    : 'pill-btn px-3 py-1 rounded-full text-xs font-semibold border bg-white text-gray-600 border-gray-300 hover:border-orange-400';
            });
        }

        function filterSchemes() {
            var search       = (document.getElementById('sidebar-search') ? document.getElementById('sidebar-search').value : '').toLowerCase().trim();
            var activeCat    = document.querySelector('input[name="category"]:checked') ? document.querySelector('input[name="category"]:checked').value : '';
            var schemeType   = document.querySelector('input[name="scheme_type"]:checked') ? document.querySelector('input[name="scheme_type"]:checked').value : '';
            var benefitTypes = Array.from(document.querySelectorAll('.benefit-filter:checked')).map(function(el) { return el.value; });
            var sort         = document.getElementById('sort-select') ? document.getElementById('sort-select').value : 'benefit';

            var cards = Array.from(document.querySelectorAll('.scheme-card'));

            cards.forEach(function(card) {
                var show = true;
                if (search      && card.dataset.name.indexOf(search) === -1)                         show = false;
                if (activeCat   && card.dataset.category !== activeCat)                              show = false;
                if (schemeType  && card.dataset.isCentral !== schemeType)                            show = false;
                if (benefitTypes.length > 0 && benefitTypes.indexOf(card.dataset.benefitType) === -1) show = false;
                card.style.display = show ? '' : 'none';
            });

            var visible = cards.filter(function(c) { return c.style.display !== 'none'; });
            document.getElementById('schemes-count').textContent = visible.length;

            var noResults = document.getElementById('no-results');
            if (noResults) {
                if (visible.length > 0) { noResults.classList.add('hidden'); }
                else { noResults.classList.remove('hidden'); }
            }

            var grid = document.getElementById('schemes-grid');
            visible.sort(function(a, b) {
                if (sort === 'benefit') return Number(b.dataset.benefit) - Number(a.dataset.benefit);
                return a.dataset.name.localeCompare(b.dataset.name);
            });
            visible.forEach(function(card) { grid.appendChild(card); });
        }
    </script>

</x-app-layout>