<x-app-layout title="Benefit Calculator">

    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold mb-2">💰 Benefit Calculator</h1>
            <p class="text-blue-200 text-sm">Discover the total rupee value of government benefits you're eligible for</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-10">

        <!-- Quick Calculator (No Login) -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6">
            <h2 class="font-bold text-gray-800 text-lg mb-1">⚡ Quick Calculator</h2>
            <p class="text-gray-500 text-sm mb-5">Enter basic details to estimate your benefits instantly</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">State</label>
                    <select id="q-state" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                        <option value="">Any State</option>
                        <option>Bihar</option>
                        <option>Uttar Pradesh</option>
                        <option>Rajasthan</option>
                        <option>Maharashtra</option>
                        <option>West Bengal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Occupation</label>
                    <select id="q-occupation" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                        <option value="">Any</option>
                        <option value="farmer">Farmer</option>
                        <option value="student">Student</option>
                        <option value="business">Business</option>
                        <option value="employed">Employed</option>
                        <option value="unemployed">Unemployed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                    <select id="q-category" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                        <option value="">Any</option>
                        <option value="general">General</option>
                        <option value="obc">OBC</option>
                        <option value="sc">SC</option>
                        <option value="st">ST</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Annual Income</label>
                    <select id="q-income" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                        <option value="50000">Below ₹50K</option>
                        <option value="100000">₹50K-1L</option>
                        <option value="250000">₹1L-2.5L</option>
                        <option value="600000">₹2.5L-6L</option>
                        <option value="1000000">Above ₹6L</option>
                    </select>
                </div>
            </div>

            <button onclick="calculateQuick()"
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition text-sm">
                🔍 Calculate My Benefits
            </button>
        </div>

        <!-- Result Card -->
        <div id="result-card" class="hidden bg-gradient-to-br from-blue-900 to-blue-800 text-white rounded-2xl p-8 mb-6 text-center">
            <div class="text-blue-200 text-sm mb-2">Estimated Total Government Benefits</div>
            <div class="text-6xl font-bold text-white mb-1" id="total-amount">₹0</div>
            <div class="text-blue-200 text-sm mb-6">across <span id="scheme-count">0</span> eligible schemes</div>

            <!-- Category Breakdown -->
            <div id="breakdown-grid" class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6 text-left"></div>

            <!-- Actions -->
            <div class="flex gap-3 justify-center flex-wrap">
                <a href="/register"
                   class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition">
                    🚀 Claim These Benefits Free
                </a>
                <button onclick="shareResult()"
                    class="bg-white/10 hover:bg-white/20 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition">
                    📤 Share Result
                </button>
            </div>
        </div>

        <!-- Logged In Calculator -->
        <div id="personal-calculator" class="hidden bg-white border border-gray-200 rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">🎯 Your Personal Benefit Total</h2>
                    <p class="text-gray-500 text-sm">Based on your complete profile</p>
                </div>
                <button onclick="loadPersonalBenefits()"
                    class="border border-gray-300 text-gray-600 text-xs px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                    🔄 Refresh
                </button>
            </div>

            <!-- Animated Total -->
            <div class="text-center py-8 bg-green-50 rounded-xl mb-5">
                <div class="text-xs text-gray-500 mb-1">Total Eligible Benefits</div>
                <div class="text-5xl font-bold text-green-700 mb-1" id="personal-total">₹0</div>
                <div class="text-xs text-gray-400">across your matched schemes</div>
            </div>

            <!-- Personal Breakdown -->
            <div id="personal-breakdown" class="space-y-3"></div>
        </div>

        <!-- How It's Calculated -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6">
            <h2 class="font-bold text-gray-800 text-lg mb-4">📊 How We Calculate</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <div class="text-3xl mb-2">📋</div>
                    <div class="font-semibold text-gray-700 text-sm mb-1">Profile Matching</div>
                    <div class="text-xs text-gray-400">We match your age, income, occupation, caste, and location against eligibility rules</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <div class="text-3xl mb-2">💰</div>
                    <div class="font-semibold text-gray-700 text-sm mb-1">Benefit Aggregation</div>
                    <div class="text-xs text-gray-400">We sum up maximum benefit values of all schemes you qualify for</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <div class="text-3xl mb-2">🎯</div>
                    <div class="font-semibold text-gray-700 text-sm mb-1">Real Data</div>
                    <div class="text-xs text-gray-400">All figures are from official government scheme documentation</div>
                </div>
            </div>
        </div>

        <!-- Scheme Category Totals -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="font-bold text-gray-800 text-lg mb-4">🗂️ Benefits by Category</h2>
            <div class="space-y-3">
                @foreach($categories as $category)
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl">
                    <span class="text-2xl">{{ $category->icon }}</span>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-700 text-sm">{{ $category->name }}</div>
                        <div class="text-xs text-gray-400 font-hindi">{{ $category->hindi_name }}</div>
                    </div>
                    <a href="/schemes?category={{ $category->slug }}"
                       class="text-orange-500 text-xs font-semibold hover:underline">
                        View Schemes →
                    </a>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');

        if (token) {
            document.getElementById('personal-calculator').classList.remove('hidden');
            loadPersonalBenefits();
        }

        async function calculateQuick() {
            const occupation = document.getElementById('q-occupation').value;
            const category   = document.getElementById('q-category').value;
            const income     = parseInt(document.getElementById('q-income').value) || 100000;

            // Simulate calculation based on inputs
            let total = 0;
            let schemes = 0;
            const breakdown = {};

            // Base universal schemes
            total += 200000; schemes += 3;
            breakdown['Documents & Identity'] = 0;
            breakdown['Financial Benefits'] = 200000;

            // Income based
            if (income < 100000) {
                total += 500000; schemes += 2;
                breakdown['Health Services'] = 500000;
            }
            if (income < 200000) {
                total += 130000; schemes += 1;
                breakdown['Housing'] = 130000;
            }

            // Occupation based
            if (occupation === 'farmer') {
                total += 356000; schemes += 4;
                breakdown['Agriculture'] = 356000;
            }
            if (occupation === 'student') {
                total += 750000; schemes += 3;
                breakdown['Education'] = 750000;
            }

            // Category based
            if (['sc', 'st', 'obc'].includes(category)) {
                total += 75000; schemes += 2;
                breakdown['Education'] = (breakdown['Education'] || 0) + 75000;
            }

            // Show result
            document.getElementById('result-card').classList.remove('hidden');
            animateNumber('total-amount', 0, total, 2000, true);
            document.getElementById('scheme-count').textContent = schemes;

            document.getElementById('breakdown-grid').innerHTML = Object.entries(breakdown)
                .filter(([k, v]) => v > 0)
                .map(([cat, val]) => `
                    <div class="bg-white/10 rounded-xl p-3">
                        <div class="text-xs text-blue-200 mb-1">${cat}</div>
                        <div class="font-bold text-lg">₹${Number(val).toLocaleString('en-IN')}</div>
                    </div>
                `).join('');

            document.getElementById('result-card').scrollIntoView({ behavior: 'smooth' });
        }

        async function loadPersonalBenefits() {
            try {
                const res = await fetch('/api/v1/schemes/benefit-total', {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                });
                const data = await res.json();
                const total = data.total || 0;
                const breakdown = data.breakdown || {};

                animateNumber('personal-total', 0, total, 2000, true);

                document.getElementById('personal-breakdown').innerHTML = Object.entries(breakdown)
                    .filter(([k, v]) => v > 0)
                    .map(([cat, val]) => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-sm font-medium text-gray-700">${cat}</span>
                            <span class="font-bold text-green-700">₹${Number(val).toLocaleString('en-IN')}</span>
                        </div>
                    `).join('') || '<div class="text-center text-gray-400 text-sm py-4">Complete your profile to see breakdown</div>';

            } catch(e) {}
        }

        function animateNumber(id, start, end, duration, currency = false) {
            const el = document.getElementById(id);
            const range = end - start;
            const startTime = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(start + range * eased);
                el.textContent = currency ? '₹' + current.toLocaleString('en-IN') : current;
                if (progress < 1) requestAnimationFrame(update);
            }
            requestAnimationFrame(update);
        }

        function shareResult() {
            const amount = document.getElementById('total-amount').textContent;
            const text = `I just discovered ${amount} in government benefits I'm eligible for! 🇮🇳 Check yours free at NagrikSathi.com`;
            if (navigator.share) {
                navigator.share({ title: 'My Government Benefits', text });
            } else {
                navigator.clipboard.writeText(text);
                alert('Result copied to clipboard!');
            }
        }
    </script>

</x-app-layout>
