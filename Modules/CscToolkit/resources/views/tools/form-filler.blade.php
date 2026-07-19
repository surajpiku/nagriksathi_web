<x-app-layout title="Form Auto-Filler — Seva Mitra Toolkit">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between">
            <div>
                <div class="text-blue-300 text-xs mb-1">Seva Mitra Toolkit — Tool 6</div>
                <h1 class="text-2xl font-bold">📋 AI Form Auto-Filler</h1>
                <p class="text-blue-200 text-sm">Claude AI fills government forms using customer profile</p>
            </div>
            <a href="/csc/toolkit" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">← Toolkit</a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">

        <!-- Step 1 — Select Form -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6">
            <h3 class="font-bold text-gray-800 mb-4">Step 1 — Select Government Form</h3>
            @if(isset($forms) && $forms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($forms as $form)
                <label class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-orange-400 transition has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                    <input type="radio" name="form_id" value="{{ $form->form_id }}" class="hidden">
                    <div class="font-semibold text-gray-800 text-sm">{{ $form->form_name }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $form->portal_name ?? $form->portal_url }}</div>
                    <div class="text-xs text-blue-600 mt-1">{{ $form->total_fields }} fields</div>
                </label>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-3">📋</div>
                <div class="font-semibold text-gray-600 mb-2">No form templates yet</div>
                <div class="text-sm text-gray-400">Run the FormTemplateSeeder to add government form templates</div>
                <code class="block mt-2 text-xs bg-gray-100 px-3 py-2 rounded-lg text-gray-600">
                    php artisan module:seed CscToolkit --class=FormTemplateSeeder
                </code>
            </div>
            @endif
        </div>

        <!-- Step 2 — Customer Details -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6">
            <h3 class="font-bold text-gray-800 mb-4">Step 2 — Enter Customer Phone</h3>
            <div class="flex gap-3">
                <input type="tel" id="customer-phone" maxlength="10" placeholder="Customer's 10-digit phone number"
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                <button onclick="findCustomer()"
                    class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    Find Customer
                </button>
            </div>
            <div id="customer-info" class="hidden mt-3 bg-blue-50 border border-blue-100 rounded-xl p-3 flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">👤</div>
                <div>
                    <div class="font-semibold text-gray-800 text-sm" id="customer-name-display">—</div>
                    <div class="text-xs text-gray-500" id="customer-detail-display">—</div>
                </div>
            </div>
        </div>

        <!-- Step 3 — Auto Fill -->
        <div class="text-center mb-6">
            <button onclick="autoFill()" id="autofill-btn"
                class="bg-orange-500 hover:bg-orange-600 text-white font-bold px-12 py-4 rounded-2xl text-lg transition">
                🤖 Auto-Fill with AI
            </button>
            <p class="text-xs text-gray-400 mt-2">Claude AI will fill all fields using customer profile</p>
        </div>

        <!-- Result -->
        <div id="result-section" class="hidden bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">✅ Auto-Filled Form</h3>
                <span id="fill-count" class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold"></span>
            </div>
            <div id="filled-fields" class="space-y-3"></div>
            <div class="mt-4 flex gap-3">
                <button onclick="copyAll()"
                    class="flex-1 bg-green-700 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-green-800 transition">
                    📋 Copy All Fields
                </button>
                <a id="portal-link" href="#" target="_blank"
                    class="flex-1 bg-blue-900 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-800 transition text-center">
                    🌐 Open Portal →
                </a>
            </div>
        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
        let foundUserId = null;
        let filledData  = {};

        async function findCustomer() {
            const phone = document.getElementById('customer-phone').value.trim();
            if (phone.length !== 10) { alert('Enter valid 10-digit phone'); return; }

            try {
                const res  = await fetch(`/api/v1/agents/nearby?phone=${phone}`, {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                });

                // Simple search by phone via profile endpoint
                const res2 = await fetch('/api/v1/auth/me', {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                });
                const data = await res2.json();

                if (data.user) {
                    foundUserId = data.user.id;
                    document.getElementById('customer-name-display').textContent = data.user.profile?.name || 'Customer Found';
                    document.getElementById('customer-detail-display').textContent = phone + ' • ' + (data.user.profile?.state || '');
                    document.getElementById('customer-info').classList.remove('hidden');
                }
            } catch(e) {
                alert('Customer not found on NagrikSathi');
            }
        }

        async function autoFill() {
            const formId = document.querySelector('input[name="form_id"]:checked')?.value;
            if (!formId) { alert('Please select a form first'); return; }
            if (!foundUserId) { alert('Please find customer first'); return; }

            const btn = document.getElementById('autofill-btn');
            btn.textContent = '🤖 AI is filling...';
            btn.disabled    = true;

            try {
                const res  = await fetch(`/api/v1/csc/toolkit/forms/${formId}/autofill`, {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
                    body:    JSON.stringify({ user_id: foundUserId }),
                });
                const data = await res.json();

                btn.textContent = '🤖 Auto-Fill with AI';
                btn.disabled    = false;

                if (data.success) {
                    filledData = data.filled;
                    renderFilled(data.filled, data.form);
                } else {
                    alert('Auto-fill failed: ' + data.message);
                }
            } catch(e) {
                btn.textContent = '🤖 Auto-Fill with AI';
                btn.disabled    = false;
                alert('Error: ' + e.message);
            }
        }

        function renderFilled(filled, form) {
            const container = document.getElementById('filled-fields');
            container.innerHTML = '';

            let count = 0;
            for (const [key, value] of Object.entries(filled)) {
                if (!value) continue;
                count++;
                container.innerHTML += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div>
                            <div class="text-xs text-gray-500">${key}</div>
                            <div class="font-semibold text-gray-800 text-sm">${value}</div>
                        </div>
                        <button onclick="copyField('${value}')"
                            class="text-xs text-blue-600 hover:text-blue-800 font-medium">Copy</button>
                    </div>
                `;
            }

            document.getElementById('fill-count').textContent = count + ' fields filled';
            document.getElementById('portal-link').href = form.portal_url;
            document.getElementById('result-section').classList.remove('hidden');
        }

        function copyField(value) { navigator.clipboard.writeText(value); }

        function copyAll() {
            const text = Object.entries(filledData)
                .filter(([k, v]) => v)
                .map(([k, v]) => `${k}: ${v}`)
                .join('\n');
            navigator.clipboard.writeText(text);
            alert('All fields copied to clipboard!');
        }
    </script>

</x-app-layout>

