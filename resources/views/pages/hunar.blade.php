<x-app-layout title="Hunar Directory — Local Skills & Services">

    <!-- Header -->
    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center gap-3 mb-4">
                <span class="text-3xl">🔧</span>
                <div>
                    <h1 class="text-2xl font-bold">Hunar Directory</h1>
                    <p class="text-blue-200 text-sm">Apne Ilake Ke Kaarigaar — हुनर डायरेक्टरी</p>
                </div>
            </div>

            <!-- Smart Location Search -->
            <div class="bg-white rounded-2xl p-5">
                <h3 class="font-bold text-gray-700 text-sm mb-3">📍 Find Skills Near You</h3>

                <!-- Pincode Row -->
                <div class="flex gap-2 mb-3">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-500 mb-1">Pincode *</label>
                        <input type="number" id="loc-pincode" placeholder="e.g. 842001" maxlength="6"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 outline-none focus:border-orange-400"
                            oninput="if(this.value.length==6) onPincodeEntered()">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs text-gray-500 mb-1">Skill (optional)</label>
                        <input type="text" id="hunar-search" placeholder="e.g. Plumber, Teacher..."
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 outline-none focus:border-orange-400"
                            onkeydown="if(event.key==='Enter') doSearch()">
                    </div>
                    <div class="flex items-end">
                        <button onclick="doSearch()"
                            class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-2.5 rounded-xl transition text-sm whitespace-nowrap">
                            Find →
                        </button>
                    </div>
                </div>

                <!-- Divider -->
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400">OR search by location</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <!-- State + District -->
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">State</label>
                        <select id="loc-state" onchange="onStateChange()"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-orange-400">
                            <option value="">Select State</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">District</label>
                        <select id="loc-district" onchange="onDistrictChange()"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm text-gray-800 outline-none focus:border-orange-400">
                            <option value="">Select District</option>
                        </select>
                    </div>
                </div>

                <!-- Block autocomplete -->
                <div class="relative">
                    <label class="block text-xs text-gray-500 mb-1">Block / Tehsil (optional)</label>
                    <input type="text" id="loc-block" placeholder="Type block name..."
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 outline-none focus:border-orange-400"
                        oninput="onBlockSearch(this.value)" autocomplete="off">
                    <div id="block-suggestions"
                        class="hidden absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-xl shadow-lg z-50 max-h-40 overflow-y-auto mt-1"></div>
                </div>

                <!-- Search status -->
                <div id="search-status-msg" class="hidden text-xs text-blue-600 bg-blue-50 rounded-lg px-3 py-2 mt-2"></div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- Add Your Skill Banner — logged in -->
        <div id="add-skill-banner" class="hidden bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6 flex items-center justify-between">
            <div>
                <div class="font-bold text-gray-800">🌟 Kya aap koi kaam jaante hain?</div>
                <div class="text-sm text-gray-500">Apna Hunar add karein — bilkul free mein</div>
            </div>
            <button onclick="showAddService()"
                class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600 transition whitespace-nowrap ml-3">
                + Add My Skill
            </button>
        </div>

        <!-- Login Banner — guests -->
        <div id="login-banner" class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-center justify-between">
            <div>
                <div class="font-bold text-gray-800">🔐 Login to add your skill or contact providers</div>
                <div class="text-sm text-gray-500">Browse is free — Login to contact or list your skill</div>
            </div>
            <a href="/login" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600 transition whitespace-nowrap ml-3">
                Login →
            </a>
        </div>

        <!-- Categories Grid -->
        <div id="categories-section">
            <h2 class="font-bold text-gray-800 text-lg mb-4">Browse by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-8">
                @foreach($categories as $category)
                <button onclick="searchByCategory({{ $category->id }}, '{{ $category->name }}')"
                    class="category-card bg-white border border-gray-200 rounded-xl p-4 text-center hover:border-orange-400 hover:bg-orange-50 transition"
                    data-id="{{ $category->id }}">
                    <div class="text-3xl mb-2">{{ $category->icon }}</div>
                    <div class="font-semibold text-gray-800 text-sm">{{ $category->name }}</div>
                    <div class="text-xs text-gray-400">{{ $category->hindi_name }}</div>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Service Types -->
        <div id="types-section" class="hidden mb-6">
            <div class="flex items-center gap-3 mb-4">
                <button onclick="showCategories()" class="text-orange-500 text-sm hover:underline">← Back</button>
                <h2 class="font-bold text-gray-800 text-lg" id="types-heading">Select Service Type</h2>
            </div>
            <div id="types-grid" class="grid grid-cols-2 md:grid-cols-4 gap-3"></div>
        </div>

        <!-- Search Results -->
        <div id="results-section" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <button onclick="showCategories()" class="text-orange-500 text-sm hover:underline">← Back</button>
                    <h2 class="font-bold text-gray-800 text-lg mt-1" id="results-title">Results</h2>
                    <p class="text-xs text-gray-400" id="results-subtitle"></p>
                </div>
                <div class="flex items-center gap-2">
                    <span id="results-count" class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full"></span>
                    <button onclick="showAddService()" id="add-btn-results"
                        class="hidden bg-orange-500 text-white text-xs px-3 py-1.5 rounded-lg font-semibold">
                        + Add My Skill
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex gap-2 flex-wrap mb-4">
                <select id="availability-filter" onchange="doSearch()"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs outline-none">
                    <option value="">All Availability</option>
                    <option value="available_now">✅ Available Now</option>
                    <option value="available_today">📅 Available Today</option>
                    <option value="by_appointment">📞 By Appointment</option>
                </select>
                <select id="price-filter" onchange="doSearch()"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-xs outline-none">
                    <option value="">All Prices</option>
                    <option value="free">Free</option>
                    <option value="negotiable">Negotiable</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                </select>
            </div>

            <!-- Loading -->
            <div id="results-loading" class="hidden text-center py-12">
                <div class="inline-block w-8 h-8 border-4 border-orange-500 border-t-transparent rounded-full animate-spin mb-3"></div>
                <p class="text-gray-400 text-sm" id="loading-text">Dhundh rahe hain...</p>
            </div>

            <!-- Results Grid -->
            <div id="results-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

            <!-- No Results -->
            <div id="no-results" class="hidden text-center py-12">
                <div class="text-4xl mb-3">😔</div>
                <div class="font-semibold text-gray-700">Koi nahi mila aapke aaspaas</div>
                <div class="text-sm text-gray-400 mt-1 mb-4">Apna hunar add karein aur pehle listed hon!</div>
                <button onclick="showAddService()" class="bg-orange-500 text-white px-6 py-2 rounded-lg text-sm font-semibold">
                    + Add My Skill
                </button>
            </div>
        </div>

        <!-- My Listed Skills -->
        <div id="my-services-section" class="hidden mt-8">
            <h2 class="font-bold text-gray-800 text-lg mb-4">🛠️ My Listed Skills</h2>
            <div id="my-services-list" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
        </div>

    </div>

    <!-- Add Service Modal -->
    <div id="add-service-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 text-lg">Add Your Skill</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">✕</button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select id="add-category" onchange="loadModalTypes(this.value)"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-orange-400">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Type *</label>
                    <select id="add-type"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-orange-400">
                        <option value="">Select Category First</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="add-description" rows="2" maxlength="200"
                        placeholder="Apne kaam ke baare mein batayein..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-orange-400 resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability *</label>
                        <select id="add-availability"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                            <option value="available_now">✅ Available Now</option>
                            <option value="available_today">📅 Today</option>
                            <option value="by_appointment">📞 Appointment</option>
                            <option value="weekdays_only">💼 Weekdays</option>
                            <option value="weekends_only">🏖️ Weekends</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                        <select id="add-price"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                            <option value="free">🆓 Free</option>
                            <option value="negotiable" selected>🤝 Negotiable</option>
                            <option value="low">💰 Low (₹100-500)</option>
                            <option value="medium">💰💰 Medium</option>
                            <option value="high">💰💰💰 High</option>
                        </select>
                    </div>
                </div>

                <!-- Profile Location Preview -->
                <div id="profile-location-preview" class="bg-blue-50 border border-blue-100 rounded-xl p-3">
                    <p class="text-xs text-gray-500 font-medium mb-1">📍 Your Location (from profile)</p>
                    <p class="text-sm font-semibold text-gray-800" id="profile-location-text">Loading...</p>
                    <a href="/profile/setup" class="text-xs text-orange-500 hover:underline">Update location →</a>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Extra Detail (optional)</label>
                    <input type="text" id="add-area" maxlength="150"
                        placeholder="e.g. Also serve nearby villages"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-orange-400">
                </div>

                <div id="add-error" class="hidden text-red-500 text-sm p-2 bg-red-50 rounded-lg"></div>

                <button onclick="submitService()"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition">
                    Publish My Skill →
                </button>
                <p class="text-xs text-gray-400 text-center">Free mein listed • NagrikSathi verified users only</p>
            </div>
        </div>
    </div>

    <script>
        var token          = window.nagrik ? window.nagrik.token : localStorage.getItem('nagrik_token');
        var isGuest        = !token;
        var currentCatId   = null;
        var currentCatName = '';
        var currentTypeId  = null;
        var currentQ       = '';
        var currentPincode  = null;
        var currentBlock    = null;
        var currentDistrict = null;
        var currentState    = null;
        var currentStateId  = null;
        var currentDistrictId = null;
        var blockSearchTimer  = null;

        // Show appropriate banner
        if (token) {
            document.getElementById('add-skill-banner').classList.remove('hidden');
            document.getElementById('login-banner').classList.add('hidden');
            document.getElementById('add-btn-results').classList.remove('hidden');
            loadMyServices();
        }

        // ── Location Search Functions ──────────────────────────────

        async function initLocationSearch() {
            // Load states
            var res  = await fetch('/api/v1/location/states');
            var data = await res.json();
            var sel  = document.getElementById('loc-state');
            (data.data || []).forEach(function(s) {
                sel.innerHTML += '<option value="' + s.id + '" data-name="' + s.name + '">' + s.name + '</option>';
            });

            // Auto-fill from profile if logged in
            if (token) {
                fetch('/api/v1/profile', {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                }).then(function(r) { return r.json(); }).then(function(d) {
                    var p = d.profile;
                    if (!p) return;
                    if (p.pincode)  { document.getElementById('loc-pincode').value = p.pincode; currentPincode = p.pincode; }
                    if (p.block)    { document.getElementById('loc-block').value = p.block; currentBlock = p.block; }
                    if (p.district) currentDistrict = p.district;
                    if (p.state)    currentState = p.state;

                    // Select state
                    if (p.state) {
                        var opts = document.getElementById('loc-state').options;
                        for (var i = 0; i < opts.length; i++) {
                            if (opts[i].getAttribute('data-name') === p.state) {
                                document.getElementById('loc-state').value = opts[i].value;
                                currentStateId = opts[i].value;
                                loadDistricts(opts[i].value, p.district);
                                break;
                            }
                        }
                    }
                }).catch(function() {});
            }
        }

        async function onStateChange() {
            var sel       = document.getElementById('loc-state');
            currentStateId = sel.value;
            currentState   = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].getAttribute('data-name') : null;
            currentDistrict = null;
            currentBlock    = null;
            document.getElementById('loc-district').innerHTML = '<option value="">Select District</option>';
            document.getElementById('loc-block').value = '';
            if (currentStateId) loadDistricts(currentStateId, null);
        }

        async function loadDistricts(stateId, selectName) {
            var res  = await fetch('/api/v1/location/districts/' + stateId);
            var data = await res.json();
            var sel  = document.getElementById('loc-district');
            sel.innerHTML = '<option value="">Select District</option>';
            (data.data || []).forEach(function(d) {
                var opt = '<option value="' + d.id + '" data-name="' + d.name + '">' + d.name + '</option>';
                sel.innerHTML += opt;
                if (selectName && d.name === selectName) {
                    sel.value = d.id;
                    currentDistrictId = d.id;
                }
            });
        }

        function onDistrictChange() {
            var sel = document.getElementById('loc-district');
            currentDistrictId = sel.value;
            currentDistrict   = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].getAttribute('data-name') : null;
            currentBlock = null;
            document.getElementById('loc-block').value = '';
        }

        function onBlockSearch(q) {
            clearTimeout(blockSearchTimer);
            if (q.length < 2) { document.getElementById('block-suggestions').classList.add('hidden'); return; }
            blockSearchTimer = setTimeout(async function() {
                var url = '/api/v1/location/blocks-search?q=' + encodeURIComponent(q);
                if (currentDistrictId) url += '&district_id=' + currentDistrictId;
                else if (currentStateId) url += '&state_id=' + currentStateId;

                var res  = await fetch(url);
                var data = await res.json();
                var box  = document.getElementById('block-suggestions');
                var suggestions = data.data || [];

                if (suggestions.length === 0) { box.classList.add('hidden'); return; }

                box.innerHTML = suggestions.map(function(b) {
                    return '<div onclick="selectBlock(\'' + b.name + '\')" ' +
                        'class="px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 cursor-pointer border-b border-gray-100 last:border-0">' +
                        b.name + (b.hindi_name ? ' <span class="text-xs text-gray-400">(' + b.hindi_name + ')</span>' : '') +
                        '</div>';
                }).join('');
                box.classList.remove('hidden');
            }, 300);
        }

        function selectBlock(name) {
            document.getElementById('loc-block').value = name;
            document.getElementById('block-suggestions').classList.add('hidden');
            currentBlock = name;
        }

        function onPincodeEntered() {
            var pin = document.getElementById('loc-pincode').value;
            if (pin.length !== 6) return;
            currentPincode = pin;

            // Fetch block/district/state from pincode API
            fetch('/api/v1/location/pincode/' + pin)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        if (data.block)    { document.getElementById('loc-block').value = data.block; currentBlock = data.block; }
                        if (data.district) currentDistrict = data.district;
                        if (data.state)    currentState = data.state;
                        showStatus('📍 ' + [data.block, data.district, data.state].filter(Boolean).join(', '));
                    }
                }).catch(function() {});
        }

        // ── Smart Search with Auto-Fallback ───────────────────────

        async function doSearch() {
            // Read current form values
            var pin  = document.getElementById('loc-pincode').value.trim();
            var blk  = document.getElementById('loc-block').value.trim();
            var distSel  = document.getElementById('loc-district');
            var stateSel = document.getElementById('loc-state');

            if (pin.length === 6) currentPincode = pin;
            if (blk)              currentBlock   = blk;
            if (distSel.value)    currentDistrict = distSel.options[distSel.selectedIndex]?.getAttribute('data-name');
            if (stateSel.value)   currentState    = stateSel.options[stateSel.selectedIndex]?.getAttribute('data-name');

            currentQ = document.getElementById('hunar-search').value.trim();

            showResults('Skills Near You', 'Searching...');
            document.getElementById('results-loading').classList.remove('hidden');
            document.getElementById('results-grid').innerHTML = '';
            document.getElementById('no-results').classList.add('hidden');

            var providers = await smartSearch();

            document.getElementById('results-loading').classList.add('hidden');
            document.getElementById('results-count').textContent = providers.length + ' found';

            if (providers.length === 0) {
                document.getElementById('no-results').classList.remove('hidden');
                return;
            }

            renderProviders(providers);
        }

        async function smartSearch() {
            var headers = { 'Accept': 'application/json' };
            if (token) headers['Authorization'] = 'Bearer ' + token;

            var extra = '';
            if (currentCatId)  extra += '&category=' + currentCatId;
            if (currentTypeId) extra += '&type_id=' + currentTypeId;
            if (currentQ)      extra += '&q=' + encodeURIComponent(currentQ);

            var avail = document.getElementById('availability-filter')?.value;
            var price = document.getElementById('price-filter')?.value;
            if (avail) extra += '&availability=' + avail;
            if (price) extra += '&price_range=' + price;

            // Level 1 — Pincode
            if (currentPincode && currentPincode.length === 6) {
                showStatus('🔍 Searching in pincode ' + currentPincode + '...');
                var result = await fetchHunar('pincode=' + currentPincode + extra, headers);
                if (result.length > 0) {
                    showStatus('📍 Results for pincode ' + currentPincode);
                    document.getElementById('results-title').textContent = 'Skills in Pincode ' + currentPincode;
                    return result;
                }
                showStatus('No results in pincode ' + currentPincode + '. Expanding to block...');
                await sleep(400);
            }

            // Level 2 — Block
            if (currentBlock) {
                showStatus('🔍 Searching in block: ' + currentBlock + '...');
                var result = await fetchHunar('block=' + encodeURIComponent(currentBlock) + extra, headers);
                if (result.length > 0) {
                    showStatus('📍 Results from ' + currentBlock + ' block');
                    document.getElementById('results-title').textContent = 'Skills in ' + currentBlock;
                    return result;
                }
                showStatus('No results in ' + currentBlock + '. Expanding to district...');
                await sleep(400);
            }

            // Level 3 — District
            if (currentDistrict) {
                showStatus('🔍 Searching in district: ' + currentDistrict + '...');
                var result = await fetchHunar('district=' + encodeURIComponent(currentDistrict) + extra, headers);
                if (result.length > 0) {
                    showStatus('📍 Results from ' + currentDistrict + ' district');
                    document.getElementById('results-title').textContent = 'Skills in ' + currentDistrict;
                    return result;
                }
                showStatus('No results in ' + currentDistrict + '. Expanding to state...');
                await sleep(400);
            }

            // Level 4 — State
            if (currentState) {
                showStatus('🔍 Searching in state: ' + currentState + '...');
                var result = await fetchHunar('state=' + encodeURIComponent(currentState) + extra, headers);
                if (result.length > 0) {
                    showStatus('📍 Results from ' + currentState + ' state');
                    document.getElementById('results-title').textContent = 'Skills in ' + currentState;
                    return result;
                }
                showStatus('No results in ' + currentState + '. Showing all India...');
                await sleep(400);
            }

            // Level 5 — All India
            showStatus('🔍 Searching all India...');
            var result = await fetchHunar(extra.replace(/^&/, ''), headers);
            if (result.length > 0) {
                showStatus('📍 Showing results from all India');
                document.getElementById('results-title').textContent = 'Skills — All India';
            }
            return result;
        }

        async function fetchHunar(params, headers) {
            try {
                var res  = await fetch('/api/v1/hunar/search?' + params, { headers });
                var data = await res.json();
                return data.data || [];
            } catch(e) { return []; }
        }

        function showStatus(msg) {
            var el = document.getElementById('search-status-msg');
            if (el) { el.textContent = msg; el.classList.remove('hidden'); }
            var lt = document.getElementById('loading-text');
            if (lt) lt.textContent = msg;
        }

        function sleep(ms) { return new Promise(function(r) { setTimeout(r, ms); }); }

        // ── Category / Type Browse ─────────────────────────────────

        function searchByCategory(catId, catName) {
            currentCatId   = catId;
            currentCatName = catName;
            currentTypeId  = null;
            currentQ       = '';

            document.getElementById('categories-section').classList.add('hidden');
            document.getElementById('types-section').classList.remove('hidden');
            document.getElementById('types-heading').textContent = catName;

            fetch('/api/v1/hunar/categories/' + catId + '/types', { headers: { 'Accept': 'application/json' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    document.getElementById('types-grid').innerHTML = (data.data || []).map(function(t) {
                        return '<button onclick="selectType(' + t.id + ', \'' + t.name + '\')" ' +
                            'class="bg-white border-2 border-gray-200 hover:border-orange-400 hover:bg-orange-50 rounded-xl p-4 text-center transition">' +
                            '<div class="font-semibold text-gray-800 text-sm">' + t.name + '</div>' +
                            '<div class="text-xs text-gray-400 mt-0.5">' + t.hindi_name + '</div>' +
                            '</button>';
                    }).join('');
                });
        }

        function selectType(typeId, typeName) {
            currentTypeId = typeId;
            document.getElementById('types-section').classList.add('hidden');
            showResults(typeName + ' — Near You', currentCatName);
            doSearch();
        }

        function showResults(heading, subtitle) {
            document.getElementById('categories-section').classList.add('hidden');
            document.getElementById('types-section').classList.add('hidden');
            document.getElementById('results-section').classList.remove('hidden');
            document.getElementById('results-title').textContent = heading;
            document.getElementById('results-subtitle').textContent = subtitle;
        }

        function showCategories() {
            document.getElementById('results-section').classList.add('hidden');
            document.getElementById('types-section').classList.add('hidden');
            document.getElementById('categories-section').classList.remove('hidden');
            currentCatId  = null;
            currentTypeId = null;
        }

        // ── Render Providers ──────────────────────────────────────

        function renderProviders(providers) {
            document.getElementById('results-grid').innerHTML = providers.map(function(s) {
                var availColor = s.availability === 'available_now' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                var availText  = s.availability === 'available_now' ? '✅ Now' :
                                 s.availability === 'available_today' ? '📅 Today' : '📞 Appt';

                var phoneBtn = '';
                if (s.provider.has_phone) {
                    if (isGuest) {
                        phoneBtn = '<button onclick="loginToCall()" class="bg-green-100 text-green-800 text-xs px-3 py-1.5 rounded-lg font-semibold">📞 ' + (s.provider.masked_phone || 'Login') + '</button>';
                    } else if (s.provider.phone) {
                        phoneBtn = '<a href="tel:' + s.provider.phone + '" onclick="logContact(' + s.id + ',\'phone_call\')" class="bg-green-500 text-white text-xs px-3 py-1.5 rounded-lg font-semibold">📞 Call</a>';
                    } else {
                        phoneBtn = '<button onclick="revealPhone(' + s.id + ')" class="bg-green-100 text-green-800 text-xs px-3 py-1.5 rounded-lg font-semibold">📞 Get Number</button>';
                    }
                }

                return '<div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition">' +
                    '<div class="flex items-start justify-between mb-3">' +
                    '<div>' +
                    '<div class="font-bold text-gray-800">' + (s.provider.name || 'Provider') + '</div>' +
                    '<div class="text-xs text-gray-400 mt-0.5">📍 ' + (s.provider.location || '') + '</div>' +
                    '</div>' +
                    '<span class="text-xs px-2 py-1 rounded-full ' + availColor + ' shrink-0">' + availText + '</span>' +
                    '</div>' +
                    '<div class="flex flex-wrap gap-1.5 mb-3">' +
                    '<span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">' + s.service_type + '</span>' +
                    '<span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">💰 ' + s.price_range + '</span>' +
                    (s.is_verified ? '<span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">✓ Verified</span>' : '') +
                    '</div>' +
                    (s.description ? '<p class="text-xs text-gray-500 mb-3">' + s.description + '</p>' : '') +
                    '<div class="flex items-center justify-between pt-3 border-t border-gray-100">' +
                    '<div class="text-xs text-gray-400">' +
                    (s.review_count >= 1 ? '⭐ ' + s.rating + ' (' + s.review_count + ')' : '<span class="text-blue-500">New</span>') +
                    '</div>' +
                    '<div class="flex gap-2">' + phoneBtn +
                    '<button onclick="logContact(' + s.id + ',\'in_app_chat\')" class="bg-orange-500 text-white text-xs px-3 py-1.5 rounded-lg font-semibold">💬 Contact</button>' +
                    '</div></div></div>';
            }).join('');
        }

        function loginToCall() {
            if (confirm('Login karein provider ka full number dekhne ke liye?')) window.location.href = '/login';
        }

        async function revealPhone(serviceId) {
            if (!token) { loginToCall(); return; }
            var res  = await fetch('/api/v1/hunar/providers/' + serviceId + '/contact', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' },
                body: JSON.stringify({ contact_method: 'phone_call' })
            });
            var data = await res.json();
            if (data.phone) window.location.href = 'tel:' + data.phone;
            else alert('Provider ne phone number share nahi kiya hai.');
        }

        async function logContact(serviceId, method) {
            if (!token) { loginToCall(); return; }
            await fetch('/api/v1/hunar/providers/' + serviceId + '/contact', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' },
                body: JSON.stringify({ contact_method: method || 'in_app_chat' })
            });
        }

        // ── Add Service ───────────────────────────────────────────

        async function showAddService() {
            if (!token) { window.location.href = '/login'; return; }
            try {
                var res  = await fetch('/api/v1/profile', { headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token } });
                var data = await res.json();
                var p    = data.profile;
                if (!p || !p.district) {
                    alert('Pehle apna profile complete karein!\nProfile mein district/location add karein.');
                    window.location.href = '/profile/setup';
                    return;
                }
                var loc = [p.village, p.block, p.district, p.state].filter(Boolean).join(', ');
                document.getElementById('profile-location-text').textContent = loc;
            } catch(e) { document.getElementById('profile-location-text').textContent = 'Could not load'; }
            document.getElementById('add-service-modal').classList.remove('hidden');
        }

        function closeModal() { document.getElementById('add-service-modal').classList.add('hidden'); }

        async function loadModalTypes(categoryId) {
            if (!categoryId) return;
            var headers = { 'Accept': 'application/json' };
            if (token) headers['Authorization'] = 'Bearer ' + token;
            var res  = await fetch('/api/v1/hunar/categories/' + categoryId + '/types', { headers });
            var data = await res.json();
            var sel  = document.getElementById('add-type');
            sel.innerHTML = '<option value="">Select Service Type</option>';
            (data.data || []).forEach(function(t) {
                sel.innerHTML += '<option value="' + t.id + '">' + t.name + ' — ' + t.hindi_name + '</option>';
            });
        }

        async function submitService() {
            var catId  = document.getElementById('add-category').value;
            var typeId = document.getElementById('add-type').value;
            var errEl  = document.getElementById('add-error');

            if (!catId || !typeId) {
                errEl.textContent = 'Category aur Service Type choose karein';
                errEl.classList.remove('hidden');
                return;
            }
            errEl.classList.add('hidden');

            try {
                var res  = await fetch('/api/v1/hunar/my-services', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        category_id:     parseInt(catId),
                        service_type_id: parseInt(typeId),
                        description:     document.getElementById('add-description').value,
                        availability:    document.getElementById('add-availability').value,
                        price_range:     document.getElementById('add-price').value,
                        service_area:    document.getElementById('add-area').value,
                    })
                });
                var data = await res.json();
                if (data.success) {
                    closeModal();
                    alert('🎉 Aapka Hunar publish ho gaya!');
                    loadMyServices();
                } else {
                    if (data.redirect) { alert(data.message); window.location.href = data.redirect; return; }
                    errEl.textContent = data.message || 'Error. Please try again.';
                    errEl.classList.remove('hidden');
                }
            } catch(e) {
                errEl.textContent = 'Network error. Please try again.';
                errEl.classList.remove('hidden');
            }
        }

        // ── My Services ───────────────────────────────────────────

        async function loadMyServices() {
            if (!token) return;
            try {
                var res  = await fetch('/api/v1/hunar/my-services', { headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token } });
                var data = await res.json();
                var list = data.data || [];
                if (list.length === 0) return;
                document.getElementById('my-services-section').classList.remove('hidden');
                document.getElementById('my-services-list').innerHTML = list.map(function(s) {
                    return '<div class="bg-white border border-gray-200 rounded-xl p-4">' +
                        '<div class="flex items-center justify-between">' +
                        '<div><div class="font-semibold text-gray-800 text-sm">' + s.category.name + ' → ' + s.type.name + '</div>' +
                        '<div class="text-xs text-gray-400 mt-0.5">' + (s.service_area || '') + '</div></div>' +
                        '<span class="text-xs px-2 py-1 rounded-full ' + (s.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700') + '">' + s.status + '</span>' +
                        '</div>' +
                        '<div class="flex gap-2 mt-3">' +
                        (s.status === 'active'
                            ? '<button onclick="pauseService(' + s.id + ')" class="text-xs border border-gray-300 text-gray-600 px-3 py-1 rounded-lg">⏸ Pause</button>'
                            : '<button onclick="resumeService(' + s.id + ')" class="text-xs border border-green-400 text-green-600 px-3 py-1 rounded-lg">▶ Resume</button>') +
                        '<button onclick="deleteService(' + s.id + ')" class="text-xs border border-red-300 text-red-500 px-3 py-1 rounded-lg">🗑 Remove</button>' +
                        '</div></div>';
                }).join('');
            } catch(e) {}
        }

        async function pauseService(id) {
            await fetch('/api/v1/hunar/my-services/' + id + '/pause', { method: 'PUT', headers: { 'Authorization': 'Bearer ' + token } });
            loadMyServices();
        }
        async function resumeService(id) {
            await fetch('/api/v1/hunar/my-services/' + id + '/resume', { method: 'PUT', headers: { 'Authorization': 'Bearer ' + token } });
            loadMyServices();
        }
        async function deleteService(id) {
            if (!confirm('Remove this listing?')) return;
            await fetch('/api/v1/hunar/my-services/' + id, { method: 'DELETE', headers: { 'Authorization': 'Bearer ' + token } });
            loadMyServices();
        }

        // Close block suggestions on outside click
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#loc-block') && !e.target.closest('#block-suggestions')) {
                var box = document.getElementById('block-suggestions');
                if (box) box.classList.add('hidden');
            }
        });

        // Init location search
        initLocationSearch();
    </script>

</x-app-layout>