<x-app-layout title="Join as Seva Mitra">

    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-2xl font-bold mb-1">🏢 Join as Seva Mitra</h1>
            <p class="text-blue-200 text-sm">Become a verified NagrikSathi partner and earn by helping citizens</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">

        <!-- Benefits Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-2xl mb-1">💰</div>
                <div class="font-bold text-gray-800 text-sm">Earn ₹500-2000</div>
                <div class="text-xs text-gray-400">Per day potential</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-2xl mb-1">🎯</div>
                <div class="font-bold text-gray-800 text-sm">Task Assignment</div>
                <div class="text-xs text-gray-400">We send customers to you</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-2xl mb-1">📱</div>
                <div class="font-bold text-gray-800 text-sm">Digital Tools</div>
                <div class="text-xs text-gray-400">AI-powered toolkit</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-2xl mb-1">🏆</div>
                <div class="font-bold text-gray-800 text-sm">Verified Badge</div>
                <div class="text-xs text-gray-400">Build trust & reputation</div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-800 text-lg mb-1">Agent Registration Form</h2>
            <p class="text-gray-500 text-sm mb-6">Fill all details. Admin will review in 24-48 hours.</p>

            <!-- Not logged in warning -->
            <div id="not-logged-in" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 text-center">
                <p class="text-yellow-700 text-sm font-medium">Please login first to submit your application</p>
                <a href="/login" class="mt-2 inline-block bg-orange-500 text-white px-6 py-2 rounded-lg text-sm font-semibold">Login Now →</a>
            </div>

            <div id="form-content">

                <!-- Agent Type -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Select Agent Type *</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <label class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-green-400 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                            <input type="radio" name="agent_type" value="official_vle" class="hidden">
                            <div class="text-2xl mb-2">🟢</div>
                            <div class="font-bold text-gray-800 text-sm">Official VLE</div>
                            <div class="text-xs text-gray-500 mt-1">Registered CSC VLE with official CSC ID</div>
                        </label>
                        <label class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-blue-400 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="agent_type" value="sathi_partner" class="hidden">
                            <div class="text-2xl mb-2">🔵</div>
                            <div class="font-bold text-gray-800 text-sm">Sathi Partner</div>
                            <div class="text-xs text-gray-500 mt-1">NagrikSathi trained and verified partner</div>
                        </label>
                        <label class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-yellow-400 transition has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50">
                            <input type="radio" name="agent_type" value="partner_agent" class="hidden">
                            <div class="text-2xl mb-2">🟡</div>
                            <div class="font-bold text-gray-800 text-sm">Partner Agent</div>
                            <div class="text-xs text-gray-500 mt-1">Works with an existing VLE centre</div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Centre Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Centre Name</label>
                        <input type="text" id="centre_name" placeholder="e.g. Ram Kumar Seva Mitra Kendra"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                    </div>

                    <!-- CSC ID -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CSC ID (if Official VLE)</label>
                        <input type="text" id="csc_id" placeholder="Your official CSC VLE ID"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                    </div>

                    <!-- State -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                        <select id="reg_state" onchange="loadRegDistricts(this)"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                            <option value="">Select State</option>
                            @foreach($states as $state)
                            <option value="{{ $state->name }}" data-code="{{ $state->code }}" data-id="{{ $state->id }}">
                                {{ $state->name }} — {{ $state->hindi_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- District -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">District *</label>
                        <select id="reg_district"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                            <option value="">Select State First</option>
                        </select>
                    </div>

                    <!-- Pincode -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PIN Code *</label>
                        <input type="text" id="reg_pincode" maxlength="6" placeholder="6-digit PIN code"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                    </div>

                    <!-- UPI ID -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">UPI ID (for payments)</label>
                        <input type="text" id="upi_id" placeholder="yourname@upi"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                    </div>
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Address</label>
                    <textarea id="address" rows="2" placeholder="Shop/House number, street, landmark"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400"></textarea>
                </div>

                <!-- Languages -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Languages Spoken *</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Hindi', 'English', 'Bengali', 'Tamil', 'Telugu', 'Marathi', 'Gujarati', 'Kannada', 'Odia', 'Punjabi'] as $lang)
                        <label class="flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 cursor-pointer hover:border-orange-400 transition">
                            <input type="checkbox" name="languages" value="{{ strtolower($lang) }}" class="accent-orange-500">
                            <span class="text-sm text-gray-600">{{ $lang }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Services -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Services Offered *</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach([
                            'Aadhaar Services', 'PAN Card', 'Passport',
                            'Income Certificate', 'Caste Certificate', 'Domicile Certificate',
                            'Scholarship Forms', 'PM-KISAN', 'Ration Card',
                            'Banking Services', 'Insurance', 'RTI Filing',
                        ] as $service)
                        <label class="flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 cursor-pointer hover:border-orange-400 transition">
                            <input type="checkbox" name="services" value="{{ $service }}" class="accent-orange-500">
                            <span class="text-sm text-gray-600">{{ $service }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div id="submit-error" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm mb-4"></div>

                <button onclick="submitApplication()" id="submit-btn"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition">
                    Submit Application
                </button>

                <p class="text-xs text-gray-400 text-center mt-3">
                    By submitting, you agree to NagrikSathi's terms. Admin will review in 24-48 hours.
                </p>
            </div>

            <!-- Success State -->
            <div id="success-state" class="hidden text-center py-8">
                <div class="text-5xl mb-4">🎉</div>
                <h3 class="font-bold text-gray-800 text-xl mb-2">Application Submitted!</h3>
                <p class="text-gray-500 text-sm mb-6">Our team will review your application in 24-48 hours. You'll receive an SMS once approved.</p>
                <a href="/dashboard" class="bg-orange-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-orange-600 transition">
                    Go to Dashboard →
                </a>
            </div>
        </div>
    </div>

    <script>
          var token = window.nagrik ? window.nagrik.token : localStorage.getItem('nagrik_token');

        if (!token) {
            document.getElementById('not-logged-in').classList.remove('hidden');
            document.getElementById('form-content').classList.add('hidden');
        }

     async function loadRegDistricts(select) {
    const selectedOption = select.options[select.selectedIndex];
    const stateId        = selectedOption ? selectedOption.getAttribute('data-id') : null;
    const distSel        = document.getElementById('reg_district');
    const manualInput    = document.getElementById('district-manual');

    if (!stateId) {
        distSel.innerHTML = '<option value="">Select State First</option>';
        return;
    }

    distSel.innerHTML = '<option value="">Loading...</option>';
    distSel.disabled  = true;

    try {
        const res  = await fetch('/api/v1/location/districts/' + stateId, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();

        distSel.disabled  = false;
        distSel.innerHTML = '<option value="">Select District</option>';

        if (data.data && data.data.length > 0) {
            data.data.forEach(function(d) {
                const opt       = document.createElement('option');
                opt.value       = d.name;
                opt.textContent = d.name;
                distSel.appendChild(opt);
            });
            if (manualInput) manualInput.classList.add('hidden');
        } else {
            distSel.innerHTML = '<option value="">Not available — type below</option>';
            if (manualInput) manualInput.classList.remove('hidden');
        }
    } catch(e) {
        distSel.disabled  = false;
        distSel.innerHTML = '<option value="">Error — try again</option>';
        console.error('District load error:', e);
    }
}
async function submitApplication() {
    var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
    console.log('Token at submit:', token); // ← add this
    console.log('window.nagrik:', window.nagrik); // ← add this
            
            const agentType = document.querySelector('input[name="agent_type"]:checked')?.value;
            const state     = document.getElementById('reg_state');
            const stateOption = state.options[state.selectedIndex];
            const errorEl   = document.getElementById('submit-error');

            if (!agentType) {
                errorEl.textContent = 'Please select agent type';
                errorEl.classList.remove('hidden');
                return;
            }
            if (!stateOption.value) {
                errorEl.textContent = 'Please select your state';
                errorEl.classList.remove('hidden');
                return;
            }

            errorEl.classList.add('hidden');
            const btn = document.getElementById('submit-btn');
            btn.textContent = 'Submitting...';
            btn.disabled = true;

            const languages = Array.from(document.querySelectorAll('input[name="languages"]:checked')).map(el => el.value);
            const services  = Array.from(document.querySelectorAll('input[name="services"]:checked')).map(el => el.value);

            const payload = {
                agent_type:     agentType,
                centre_name:    document.getElementById('centre_name').value,
                csc_id:         document.getElementById('csc_id').value || null,
                state:          stateOption.value,
                state_code:     stateOption.getAttribute('data-code'),
                district:       document.getElementById('reg_district').value,
                pincode:        document.getElementById('reg_pincode').value,
                address:        document.getElementById('address').value,
                upi_id:         document.getElementById('upi_id').value || null,
                languages_json: languages,
                services_json:  services,
            };

            try {
                const res  = await fetch('/api/v1/agents/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept':       'application/json',
                        'Authorization':'Bearer ' + token,
                    },
                    body: JSON.stringify(payload),
                });

                const data = await res.json();

                if (data.success) {
                    document.getElementById('form-content').classList.add('hidden');
                    document.getElementById('success-state').classList.remove('hidden');
                } else {
                    errorEl.textContent = data.message || 'Submission failed. Please try again.';
                    errorEl.classList.remove('hidden');
                    btn.textContent = 'Submit Application';
                    btn.disabled = false;
                }
            } catch(e) {
                errorEl.textContent = 'Network error. Please try again.';
                errorEl.classList.remove('hidden');
                btn.textContent = 'Submit Application';
                btn.disabled = false;
            }
        }
    </script>

</x-app-layout>

