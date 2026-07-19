<x-app-layout title="Find Seva Mitra — NagrikSathi">

    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex items-center gap-3 mb-4">
                <span class="text-3xl">🏢</span>
                <div>
                    <h1 class="text-2xl font-bold">Find Seva Mitra Near You</h1>
                    <p class="text-blue-200 text-sm">Verified helpers for government scheme applications</p>
                </div>
            </div>

            <!-- Smart Location Search -->
            <div class="bg-white rounded-2xl p-5">
                <h3 class="font-bold text-gray-700 text-sm mb-3">📍 Search by Location</h3>
                <div class="flex gap-2 mb-3">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-500 mb-1">Pincode *</label>
                        <input type="number" id="loc-pincode" placeholder="e.g. 842001" maxlength="6"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 outline-none focus:border-orange-400"
                            oninput="if(this.value.length==6) onPincodeEntered()">
                    </div>
                    <div class="flex items-end">
                        <button onclick="doSearch()"
                            class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-2.5 rounded-xl transition text-sm whitespace-nowrap">
                            Find Seva Mitra →
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400">OR search by location</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>
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
                <div class="relative">
                    <label class="block text-xs text-gray-500 mb-1">Block / Tehsil (optional)</label>
                    <input type="text" id="loc-block" placeholder="Type block name..."
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 outline-none focus:border-orange-400"
                        oninput="onBlockSearch(this.value)" autocomplete="off">
                    <div id="block-suggestions"
                        class="hidden absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-xl shadow-lg z-50 max-h-40 overflow-y-auto mt-1"></div>
                </div>
                <div id="search-status-msg" class="hidden text-xs text-blue-600 bg-blue-50 rounded-lg px-3 py-2 mt-2"></div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">

        <!-- What is Seva Mitra -->
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 mb-6">
            <h2 class="font-bold text-gray-800 mb-3">🤝 What can a Seva Mitra do for you?</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="text-center"><div class="text-2xl mb-1">📋</div><div class="text-xs font-semibold text-gray-700">Fill Government Forms</div></div>
                <div class="text-center"><div class="text-2xl mb-1">📄</div><div class="text-xs font-semibold text-gray-700">Document Help</div></div>
                <div class="text-center"><div class="text-2xl mb-1">💻</div><div class="text-xs font-semibold text-gray-700">Online Applications</div></div>
                <div class="text-center"><div class="text-2xl mb-1">🏦</div><div class="text-xs font-semibold text-gray-700">Banking Help</div></div>
            </div>
        </div>

        <!-- Loading -->
        <div id="loading" class="hidden text-center py-12">
            <div class="inline-block w-8 h-8 border-4 border-orange-500 border-t-transparent rounded-full animate-spin mb-3"></div>
            <p class="text-gray-400 text-sm" id="loading-text">Finding Seva Mitra near you...</p>
        </div>

        <!-- Results -->
        <div id="results-section" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-800" id="results-heading">Seva Mitra Near You</h2>
                    <p class="text-xs text-gray-400" id="results-subheading"></p>
                </div>
                <span id="results-count" class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full"></span>
            </div>
            <div id="agents-list" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
        </div>

        <!-- No Results -->
        <div id="no-agents" class="hidden text-center py-12">
            <div class="text-5xl mb-4">🏢</div>
            <h3 class="font-bold text-gray-700 text-xl mb-2">No Seva Mitra found in your area yet</h3>
            <p class="text-gray-500 text-sm mb-6">Be the first Seva Mitra in your area and start earning!</p>
            <div class="flex gap-4 justify-center flex-wrap">
                <a href="/seva-mitra-banen" class="bg-orange-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-600 transition">Register as Seva Mitra →</a>
                <a href="/sathi" class="border border-blue-500 text-blue-600 px-6 py-3 rounded-xl font-bold hover:bg-blue-50 transition">Ask Sathi AI</a>
            </div>
        </div>

        <!-- How it works -->
        <div class="mt-10 bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5 text-center">How Seva Mitra Works</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-lg mx-auto mb-2">1️⃣</div>
                    <div class="font-semibold text-gray-700 text-xs mb-1">Find Seva Mitra</div>
                    <div class="text-xs text-gray-400">Search by pincode or location</div>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-lg mx-auto mb-2">2️⃣</div>
                    <div class="font-semibold text-gray-700 text-xs mb-1">Visit or Call</div>
                    <div class="text-xs text-gray-400">Go to centre or call them</div>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-lg mx-auto mb-2">3️⃣</div>
                    <div class="font-semibold text-gray-700 text-xs mb-1">Get Help</div>
                    <div class="text-xs text-gray-400">They fill and apply forms</div>
                </div>
                <div class="text-center">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-lg mx-auto mb-2">4️⃣</div>
                    <div class="font-semibold text-gray-700 text-xs mb-1">Pay Small Fee</div>
                    <div class="text-xs text-gray-400">Nominal service charge only</div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="mt-6 bg-gradient-to-r from-blue-900 to-blue-700 text-white rounded-2xl p-6 text-center">
            <h2 class="text-xl font-bold mb-2">Earn ₹500-2000/day as Seva Mitra</h2>
            <p class="text-blue-200 text-sm mb-4">Help citizens in your area and earn good income</p>
            <a href="/seva-mitra-banen" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold px-8 py-3 rounded-xl transition">
                Register as Seva Mitra — Free →
            </a>
        </div>

    </div>

    <script>
        var token           = window.nagrik ? window.nagrik.token : localStorage.getItem('nagrik_token');
        var currentPincode  = null;
        var currentBlock    = null;
        var currentDistrict = null;
        var currentState    = null;
        var currentStateId  = null;
        var currentDistrictId = null;
        var blockSearchTimer  = null;

        // ── Location Search Functions ──────────────────────────────

        async function initLocationSearch() {
            var res  = await fetch('/api/v1/location/states');
            var data = await res.json();
            var sel  = document.getElementById('loc-state');
            (data.data || []).forEach(function(s) {
                sel.innerHTML += '<option value="' + s.id + '" data-name="' + s.name + '">' + s.name + '</option>';
            });

            // Auto-fill from profile
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
            var sel = document.getElementById('loc-state');
            currentStateId  = sel.value;
            currentState    = sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].getAttribute('data-name') : null;
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
                sel.innerHTML += '<option value="' + d.id + '" data-name="' + d.name + '">' + d.name + '</option>';
                if (selectName && d.name === selectName) { sel.value = d.id; currentDistrictId = d.id; }
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
            var pin  = document.getElementById('loc-pincode').value.trim();
            var blk  = document.getElementById('loc-block').value.trim();
            var distSel  = document.getElementById('loc-district');
            var stateSel = document.getElementById('loc-state');

            if (pin.length === 6) currentPincode = pin;
            if (blk)              currentBlock   = blk;
            if (distSel.value)    currentDistrict = distSel.options[distSel.selectedIndex]?.getAttribute('data-name');
            if (stateSel.value)   currentState    = stateSel.options[stateSel.selectedIndex]?.getAttribute('data-name');

            if (!currentPincode && !currentBlock && !currentDistrict && !currentState) {
                alert('Please enter pincode or select location');
                return;
            }

            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('results-section').classList.add('hidden');
            document.getElementById('no-agents').classList.add('hidden');

            var agents = await smartSearch();

            document.getElementById('loading').classList.add('hidden');

            if (agents.length === 0) {
                document.getElementById('no-agents').classList.remove('hidden');
                return;
            }

            document.getElementById('results-section').classList.remove('hidden');
            document.getElementById('results-count').textContent = agents.length + ' found';
            renderAgents(agents);
        }

        async function smartSearch() {
            var headers = { 'Accept': 'application/json' };
            if (token) headers['Authorization'] = 'Bearer ' + token;

            // Level 1 — Pincode
            if (currentPincode && currentPincode.length === 6) {
                showStatus('🔍 Searching in pincode ' + currentPincode + '...');
                var result = await fetchAgents('pincode=' + currentPincode, headers);
                if (result.length > 0) {
                    showStatus('📍 Results for pincode ' + currentPincode);
                    document.getElementById('results-heading').textContent = 'Seva Mitra in Pincode ' + currentPincode;
                    return result;
                }
                showStatus('No agents in pincode ' + currentPincode + '. Expanding to block...');
                await sleep(400);
            }

            // Level 2 — Block
            if (currentBlock) {
                showStatus('🔍 Searching in block: ' + currentBlock + '...');
                var result = await fetchAgents('block=' + encodeURIComponent(currentBlock), headers);
                if (result.length > 0) {
                    showStatus('📍 Results from ' + currentBlock + ' block');
                    document.getElementById('results-heading').textContent = 'Seva Mitra in ' + currentBlock;
                    document.getElementById('results-subheading').textContent = 'No agents found in your pincode — showing block results';
                    return result;
                }
                showStatus('No agents in ' + currentBlock + '. Expanding to district...');
                await sleep(400);
            }

            // Level 3 — District
            if (currentDistrict) {
                showStatus('🔍 Searching in district: ' + currentDistrict + '...');
                var result = await fetchAgents('district=' + encodeURIComponent(currentDistrict), headers);
                if (result.length > 0) {
                    showStatus('📍 Results from ' + currentDistrict + ' district');
                    document.getElementById('results-heading').textContent = 'Seva Mitra in ' + currentDistrict;
                    document.getElementById('results-subheading').textContent = 'No agents found nearby — showing district results';
                    return result;
                }
                showStatus('No agents in ' + currentDistrict + '. Expanding to state...');
                await sleep(400);
            }

            // Level 4 — State
            if (currentState) {
                showStatus('🔍 Searching in state: ' + currentState + '...');
                var result = await fetchAgents('state=' + encodeURIComponent(currentState), headers);
                if (result.length > 0) {
                    showStatus('📍 Results from ' + currentState);
                    document.getElementById('results-heading').textContent = 'Seva Mitra in ' + currentState;
                    document.getElementById('results-subheading').textContent = 'No agents found nearby — showing state results';
                    return result;
                }
            }

            return [];
        }

        async function fetchAgents(params, headers) {
            try {
                var res  = await fetch('/api/v1/agents/nearby?' + params + '&limit=20&status=verified', { headers });
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

        function renderAgents(agents) {
            document.getElementById('agents-list').innerHTML = agents.map(function(a) {
                var agentType = a.agent_type === 'official_vle' ? '🏛️ Official VLE' :
                               a.agent_type === 'sathi_partner' ? '🤝 Sathi Partner' : '🏪 Partner';
                var services  = (a.services_json || []).slice(0, 3).join(', ');

                return '<div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition">' +
                    '<div class="flex items-start justify-between mb-3">' +
                    '<div>' +
                    '<h3 class="font-bold text-gray-800 text-sm">' + (a.centre_name || 'Seva Mitra Centre') + '</h3>' +
                    '<p class="text-xs text-gray-500 mt-0.5">📍 ' + [a.block, a.district, a.state].filter(Boolean).join(', ') + '</p>' +
                    (a.pincode ? '<p class="text-xs text-gray-400">PIN: ' + a.pincode + '</p>' : '') +
                    '</div>' +
                    '<span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-semibold shrink-0">✓ Verified</span>' +
                    '</div>' +
                    '<div class="flex flex-wrap gap-2 mb-3">' +
                    '<span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">' + agentType + '</span>' +
                    (a.rating > 0 ? '<span class="text-xs text-yellow-600">⭐ ' + a.rating + '</span>' : '<span class="text-xs text-blue-500">New</span>') +
                    '</div>' +
                    (services ? '<p class="text-xs text-gray-500 mb-3">🛠️ ' + services + '</p>' : '') +
                    '<div class="flex gap-2 pt-3 border-t border-gray-100">' +
                    '<a href="/seva-mitra/' + a.id + '" class="flex-1 text-center border border-blue-300 text-blue-600 text-xs font-semibold py-2 rounded-lg hover:bg-blue-50">View Details</a>' +
                    (a.user && a.user.phone
                        ? '<a href="tel:' + a.user.phone + '" class="flex-1 bg-orange-500 text-white text-xs font-bold py-2 rounded-lg text-center hover:bg-orange-600">📞 Contact</a>'
                        : '<a href="/seva-mitra/' + a.id + '" class="flex-1 bg-orange-500 text-white text-xs font-bold py-2 rounded-lg text-center hover:bg-orange-600">📞 Contact</a>') +
                    '</div></div>';
            }).join('');
        }

        // Close block suggestions on outside click
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#loc-block') && !e.target.closest('#block-suggestions')) {
                var box = document.getElementById('block-suggestions');
                if (box) box.classList.add('hidden');
            }
        });

        initLocationSearch();
    </script>

</x-app-layout>