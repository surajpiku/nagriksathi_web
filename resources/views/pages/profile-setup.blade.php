<x-app-layout title="Setup Your Profile">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-3xl mx-auto px-4">
            <h1 class="text-2xl font-bold mb-1">Complete Your Civic Profile</h1>
            <p class="text-blue-200 text-sm">Help us find every government scheme you're eligible for</p>

            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="flex justify-between text-xs text-blue-300 mb-2">
                    <span>Step <span id="current-step-label">1</span> of 4</span>
                    <span id="progress-percent">25%</span>
                </div>
                <div class="h-2 bg-blue-800 rounded-full">
                    <div id="progress-bar" class="h-2 bg-orange-500 rounded-full transition-all duration-500" style="width: 25%"></div>
                </div>
                <div class="flex justify-between mt-2">
                    <span class="text-xs text-blue-300">Personal Info</span>
                    <span class="text-xs text-blue-300">Location</span>
                    <span class="text-xs text-blue-300">Economic</span>
                    <span class="text-xs text-blue-300">Assets</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">

        <!-- Not logged in -->
        <div id="not-logged-in" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
            <div class="text-3xl mb-2">🔐</div>
            <h3 class="font-bold text-gray-800 mb-2">Please Login First</h3>
            <a href="/login" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold text-sm">Login Now →</a>
        </div>

        <div id="setup-content">

            <!-- Step 1 — Personal Info -->
            <div id="step-1" class="bg-white border border-gray-200 rounded-2xl p-6">
                <h2 class="font-bold text-gray-800 text-lg mb-1">👤 Personal Information</h2>
                <p class="text-gray-500 text-sm mb-6">Basic details about yourself</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" id="name" placeholder="Enter your full name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                        <input type="date" id="dob"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                        <select id="gender" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Caste Category *</label>
                        <select id="caste_category" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                            <option value="">Select Category</option>
                            <option value="general">General</option>
                            <option value="obc">OBC</option>
                            <option value="sc">SC</option>
                            <option value="st">ST</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button onclick="nextStep(1)" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2.5 rounded-lg font-semibold text-sm transition">
                        Next →
                    </button>
                </div>
            </div>

          <!-- Step 2 — Location -->
<div id="step-2" class="hidden bg-white border border-gray-200 rounded-2xl p-6">
    <h2 class="font-bold text-gray-800 text-lg mb-1">📍 Location Details</h2>
    <p class="text-gray-500 text-sm mb-6">Your location helps us find state-specific schemes</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
            <select id="state" onchange="loadDistricts(this.value)"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                <option value="">Select State</option>
                
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
            <select id="district"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                <option value="">Select State First</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">PIN Code</label>
            <input type="text" id="pincode" maxlength="6" placeholder="Enter 6-digit PIN code"
                oninput="lookupPincode(this.value)"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
            <div id="pincode-result" class="text-xs text-green-600 mt-1 hidden"></div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Area Type</label>
            <select id="area_type"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                <option value="">Select Area Type</option>
                <option value="rural">🏘️ Rural</option>
                <option value="urban">🏙️ Urban</option>
                <option value="semi_urban">🏗️ Semi-Urban</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">City / Village</label>
            <input type="text" id="city" placeholder="Enter your city or village"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
        </div>
    </div>

    <div class="flex justify-between mt-6">
        <button onclick="prevStep(2)" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
            ← Back
        </button>
        <button onclick="nextStep(2)" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2.5 rounded-lg font-semibold text-sm transition">
            Next →
        </button>
    </div>
</div>
            <!-- Step 3 — Economic Info -->
            <div id="step-3" class="hidden bg-white border border-gray-200 rounded-2xl p-6">
                <h2 class="font-bold text-gray-800 text-lg mb-1">💰 Economic Information</h2>
                <p class="text-gray-500 text-sm mb-6">This helps match you to income-based schemes</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Occupation *</label>
                        <select id="occupation" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                            <option value="">Select Occupation</option>
                            <option value="farmer">Farmer</option>
                            <option value="student">Student</option>
                            <option value="business">Business Owner</option>
                            <option value="employed">Employed (Private)</option>
                            <option value="government">Government Employee</option>
                            <option value="unemployed">Unemployed</option>
                            <option value="self-employed">Self Employed</option>
                            <option value="daily-wage">Daily Wage Worker</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Annual Income (₹) *</label>
                        <input type="number" id="annual_income" placeholder="e.g. 120000"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Do you have a BPL Card?</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="bpl" value="1" class="accent-orange-500">
                                <span class="text-sm text-gray-600">Yes, I have a BPL card</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="bpl" value="0" checked class="accent-orange-500">
                                <span class="text-sm text-gray-600">No</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <button onclick="prevStep(3)" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                        ← Back
                    </button>
                    <button onclick="nextStep(3)" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2.5 rounded-lg font-semibold text-sm transition">
                        Next →
                    </button>
                </div>
            </div>

            <!-- Step 4 — Assets -->
            <div id="step-4" class="hidden bg-white border border-gray-200 rounded-2xl p-6">
                <h2 class="font-bold text-gray-800 text-lg mb-1">🏠 Assets & Property</h2>
                <p class="text-gray-500 text-sm mb-6">Helps match housing and agriculture schemes</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">House Type</label>
                        <select id="house_type" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                            <option value="">Select House Type</option>
                            <option value="pucca">Pucca (Permanent)</option>
                            <option value="kutcha">Kutcha (Temporary)</option>
                            <option value="semi-pucca">Semi-Pucca</option>
                            <option value="rented">Rented</option>
                            <option value="none">No House</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Land Owned (Acres)</label>
                        <input type="number" id="land_acres" placeholder="e.g. 2.5" step="0.1"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Do you own a vehicle?</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="vehicle" value="1" class="accent-orange-500">
                                <span class="text-sm text-gray-600">Yes</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="vehicle" value="0" checked class="accent-orange-500">
                                <span class="text-sm text-gray-600">No</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div id="save-error" class="hidden mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm"></div>

                <div class="flex justify-between mt-6">
                    <button onclick="prevStep(4)" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                        ← Back
                    </button>
                    <button onclick="saveProfile()" id="save-btn"
                        class="bg-green-700 hover:bg-green-800 text-white px-8 py-2.5 rounded-lg font-semibold text-sm transition">
                        Save & Find My Schemes 🎯
                    </button>
                </div>
            </div>

            <!-- Step 5 — Success -->
            <div id="step-success" class="hidden bg-white border border-gray-200 rounded-2xl p-8 text-center">
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Profile Complete!</h2>
                <p class="text-gray-500 mb-2">We found schemes matching your profile</p>
                <div class="text-4xl font-bold text-green-700 my-4" id="success-total">Calculating...</div>
                <p class="text-gray-400 text-sm mb-6">in government benefits you may be eligible for</p>
                <a href="/dashboard"
                   class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-xl font-bold transition">
                    View My Dashboard →
                </a>
            </div>

        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');

        if (!token) {
            document.getElementById('not-logged-in').classList.remove('hidden');
            document.getElementById('setup-content').classList.add('hidden');
        }

        function nextStep(current) {
            document.getElementById('step-' + current).classList.add('hidden');
            document.getElementById('step-' + (current + 1)).classList.remove('hidden');
            updateProgress(current + 1);
        }

        function prevStep(current) {
            document.getElementById('step-' + current).classList.add('hidden');
            document.getElementById('step-' + (current - 1)).classList.remove('hidden');
            updateProgress(current - 1);
        }

        function updateProgress(step) {
            const percent = (step / 4) * 100;
            document.getElementById('progress-bar').style.width = percent + '%';
            document.getElementById('progress-percent').textContent = percent + '%';
            document.getElementById('current-step-label').textContent = step;
        }

        async function saveProfile() {
            const btn = document.getElementById('save-btn');
            const errorEl = document.getElementById('save-error');
            btn.textContent = 'Saving...';
            btn.disabled = true;
            errorEl.classList.add('hidden');

            const bpl = document.querySelector('input[name="bpl"]:checked')?.value || '0';
            const vehicle = document.querySelector('input[name="vehicle"]:checked')?.value || '0';

            const payload = {
                name:           document.getElementById('name').value,
                dob:            document.getElementById('dob').value,
                gender:         document.getElementById('gender').value,
                caste_category: document.getElementById('caste_category').value,
               state:      document.getElementById('state').value,
district:   document.getElementById('district').value,
city:       document.getElementById('city').value,
pincode:    document.getElementById('pincode').value,
area_type:  document.getElementById('area_type').value,
              
                occupation:     document.getElementById('occupation').value,
                annual_income:  document.getElementById('annual_income').value,
                bpl_status:     bpl === '1',
                house_type:     document.getElementById('house_type').value,
                land_acres:     document.getElementById('land_acres').value || 0,
                has_vehicle:    vehicle === '1',
            };

            try {
                const response = await fetch('/api/v1/profile', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success) {
                    // Get benefit total
                    const benefitRes = await fetch('/api/v1/schemes/benefit-total', {
                        headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                    });
                    const benefitData = await benefitRes.json();
                    const total = benefitData.total || 0;

                    document.getElementById('success-total').textContent =
                        '₹' + Number(total).toLocaleString('en-IN');

                    document.getElementById('step-4').classList.add('hidden');
                    document.getElementById('step-success').classList.remove('hidden');
                    document.getElementById('progress-bar').style.width = '100%';
                    document.getElementById('progress-percent').textContent = '100%';
                } else {
                    errorEl.textContent = data.message || 'Failed to save profile. Please try again.';
                    errorEl.classList.remove('hidden');
                    btn.textContent = 'Save & Find My Schemes 🎯';
                    btn.disabled = false;
                }
            } catch(e) {
                errorEl.textContent = 'Network error. Please check your connection.';
                errorEl.classList.remove('hidden');
                btn.textContent = 'Save & Find My Schemes 🎯';
                btn.disabled = false;
            }
        }


async function loadStates() {
    try {
        const res  = await fetch('/api/v1/location/states');
        const data = await res.json();
        const sel  = document.getElementById('state');

        data.data.forEach(s => {
            const opt = document.createElement('option');
            opt.value              = s.name;
            opt.setAttribute('data-code', s.code);
            opt.setAttribute('data-id', s.id);
            opt.textContent        = `${s.name} — ${s.hindi_name}`;
            sel.appendChild(opt);
        });
    } catch(e) {
        console.error('States load error:', e);
    }
}
async function loadDistricts(stateName) {
    const sel     = document.getElementById('state');
    const option  = sel.options[sel.selectedIndex];
    const stateId = option.getAttribute('data-id');
    const distSel = document.getElementById('district');

    console.log('State selected:', stateName, 'ID:', stateId);

    if (!stateId) {
        distSel.innerHTML = '<option value="">Select State First</option>';
        return;
    }

    distSel.innerHTML = '<option value="">Loading districts...</option>';

    try {
        const res  = await fetch(`/api/v1/location/districts/${stateId}`);
        const data = await res.json();

        console.log('Districts response:', data);

        distSel.innerHTML = '<option value="">Select District</option>';

        if (data.data && data.data.length > 0) {
            data.data.forEach(d => {
                const opt       = document.createElement('option');
                opt.value       = d.name;
                opt.textContent = d.hindi_name ? `${d.name} — ${d.hindi_name}` : d.name;
                distSel.appendChild(opt);
            });
        } else {
    distSel.innerHTML = '<option value="">Type district name manually below</option>';
    // Show manual input
    document.getElementById('district-manual').classList.remove('hidden');
}
    } catch(e) {
        console.error('District load error:', e);
        distSel.innerHTML = '<option value="">Error loading districts</option>';
    }
}

// Pincode auto-lookup
let pincodeTimer = null;
async function lookupPincode(value) {
    clearTimeout(pincodeTimer);
    if (value.length !== 6) return;

    pincodeTimer = setTimeout(async () => {
        const res  = await fetch(`/api/v1/location/pincode/${value}`);
        const data = await res.json();

        if (data.success) {
            // Auto-fill state
            const stateSel = document.getElementById('state');
            for (let opt of stateSel.options) {
                if (opt.dataset.code === data.state_code) {
                    stateSel.value = opt.value;
                    await loadDistricts(opt.value);
                    break;
                }
            }

            // Auto-fill district
            setTimeout(() => {
                const distSel = document.getElementById('district');
                for (let opt of distSel.options) {
                    if (opt.value.toLowerCase().includes(data.district.toLowerCase())) {
                        distSel.value = opt.value;
                        break;
                    }
                }
            }, 500);

            document.getElementById('pincode-result').textContent =
                `✅ ${data.district}, ${data.state}`;
            document.getElementById('pincode-result').classList.remove('hidden');
        }
    }, 500);
}
/////
// Load existing profile data
async function loadExistingProfile() {
    const token = localStorage.getItem('nagrik_token');
    if (!token) return;

    try {
        const res  = await fetch('/api/v1/profile', {
            headers: {
                'Accept':        'application/json',
                'Authorization': 'Bearer ' + token,
            }
        });
        const data = await res.json();

        if (!data.profile) return;
        const p = data.profile;

        // Step 1 — Personal Info
        if (p.name)   setVal('name', p.name);
        if (p.dob)    setVal('dob', p.dob?.split('T')[0]);
        if (p.gender) setVal('gender', p.gender);

        // Step 2 — Location
        if (p.state) {
            const stateSel = document.getElementById('state');
            for (let opt of stateSel.options) {
                if (opt.value === p.state) { stateSel.value = p.state; break; }
            }
            if (p.state) await loadDistricts(p.state);
            if (p.district) setVal('district', p.district);
        }
        if (p.city)     setVal('city', p.city);
        if (p.pincode)  setVal('pincode', p.pincode);
        if (p.area_type) setVal('area_type', p.area_type);

        // Step 3 — Economic
        if (p.occupation)     setVal('occupation', p.occupation);
        if (p.annual_income)  setVal('annual_income', p.annual_income);
        if (p.caste_category) setVal('caste_category', p.caste_category);
        if (p.bpl_status)     setCheck('bpl_status', p.bpl_status);
        if (p.house_type)     setVal('house_type', p.house_type);
        if (p.land_acres)     setVal('land_acres', p.land_acres);

    } catch(e) {
        console.error('Profile load error:', e);
    }
}

function setVal(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value;
}

function setCheck(id, value) {
    const el = document.getElementById(id);
    if (el) el.checked = !!value;
}


// Call on page load
loadStates();
// Call on page load
loadExistingProfile();
    </script>

</x-app-layout>
