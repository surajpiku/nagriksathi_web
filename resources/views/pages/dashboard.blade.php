<x-app-layout title="My Dashboard">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between flex-wrap gap-4">
            <div>
                <div class="text-blue-300 text-sm mb-1">Welcome back 👋</div>
                <h1 class="text-2xl font-bold" id="user-name">Loading...</h1>
                <div class="text-blue-200 text-sm mt-1" id="user-phone"></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white/10 rounded-xl px-4 py-2 text-center">
                    <div class="text-xs text-blue-300">Subscription</div>
                    <div class="font-bold text-orange-400 uppercase text-sm" id="user-tier">Free</div>
                </div>
                <a href="/profile/setup"
                   class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Complete Profile →
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- Not logged in warning -->
        <div id="not-logged-in" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center mb-6">
            <div class="text-3xl mb-2">🔐</div>
            <h3 class="font-bold text-gray-800 mb-2">Please Login to View Dashboard</h3>
            <p class="text-gray-500 text-sm mb-4">Login with your mobile number to see your personalised benefits</p>
            <a href="/login" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-orange-600 transition">
                Login Now →
            </a>
        </div>

        <!-- Dashboard Content -->
        <div id="dashboard-content">

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-green-700" id="stat-benefit">₹0</div>
                    <div class="text-xs text-gray-500 mt-1">Total Benefits Eligible</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-blue-700" id="stat-schemes">0</div>
                    <div class="text-xs text-gray-500 mt-1">Matched Schemes</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-orange-500" id="stat-score">0</div>
                    <div class="text-xs text-gray-500 mt-1">Nagrik Score</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold text-purple-600" id="stat-messages">0</div>
                    <div class="text-xs text-gray-500 mt-1">Sathi Messages Left</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Nagrik Score -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-bold text-gray-800">🏆 Nagrik Score</h2>
                            <span class="text-xs text-gray-400">Out of 1000</span>
                        </div>
                        <div class="flex items-center gap-6">
                            <!-- Score Ring -->
                            <div class="relative w-24 h-24 flex-shrink-0">
                                <svg class="w-24 h-24 -rotate-90" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="#f3f4f6" stroke-width="10"/>
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="#f97316" stroke-width="10"
                                        stroke-dasharray="251.2"
                                        stroke-dashoffset="200"
                                        id="score-ring"
                                        stroke-linecap="round"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xl font-bold text-gray-800" id="score-number">0</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="space-y-2">
                                    <div>
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Profile Complete</span>
                                            <span id="score-profile">0/400</span>
                                        </div>
                                        <div class="h-1.5 bg-gray-100 rounded-full">
                                            <div class="h-1.5 bg-orange-500 rounded-full" style="width: 0%" id="bar-profile"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Documents Uploaded</span>
                                            <span id="score-docs">0/200</span>
                                        </div>
                                        <div class="h-1.5 bg-gray-100 rounded-full">
                                            <div class="h-1.5 bg-blue-500 rounded-full" style="width: 0%" id="bar-docs"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Schemes Claimed</span>
                                            <span id="score-claimed">0/200</span>
                                        </div>
                                        <div class="h-1.5 bg-gray-100 rounded-full">
                                            <div class="h-1.5 bg-green-500 rounded-full" style="width: 0%" id="bar-claimed"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Matched Schemes -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="font-bold text-gray-800">🎯 Your Matched Schemes</h2>
                            <a href="/schemes?matched=true" class="text-xs text-orange-500 hover:underline">View All →</a>
                        </div>
                        <div id="schemes-list" class="space-y-3">
                            <!-- Loaded via JS -->
                            <div class="text-center py-8 text-gray-400">
                                <div class="text-2xl mb-2">⏳</div>
                                <div class="text-sm">Loading your matched schemes...</div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="space-y-4">

                    <!-- Profile Card -->
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <h3 class="font-bold text-gray-800 mb-4">👤 My Profile</h3>
                        <div class="space-y-2 text-sm" id="profile-details">
                            <div class="flex justify-between py-1 border-b border-gray-50">
                                <span class="text-gray-500">Name</span>
                                <span class="font-medium text-gray-700" id="p-name">—</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-50">
                                <span class="text-gray-500">State</span>
                                <span class="font-medium text-gray-700" id="p-state">—</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-50">
                                <span class="text-gray-500">Occupation</span>
                                <span class="font-medium text-gray-700" id="p-occupation">—</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-50">
                                <span class="text-gray-500">Income</span>
                                <span class="font-medium text-gray-700" id="p-income">—</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500">Category</span>
                                <span class="font-medium text-gray-700 uppercase" id="p-category">—</span>
                            </div>
                        </div>
                        <a href="/profile/setup"
                           class="block mt-4 w-full text-center border border-orange-400 text-orange-500 hover:bg-orange-50 py-2 rounded-lg text-sm font-semibold transition">
                            Edit Profile →
                        </a>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <h3 class="font-bold text-gray-800 mb-3">⚡ Quick Actions</h3>
                        <!-- Matched Opportunities -->
<div class="bg-gradient-to-br from-blue-700 to-blue-900 text-white rounded-xl p-5">
    <div class="text-blue-200 text-xs mb-1">Sarkari Awasar — Jobs & Exams</div>
    <div class="text-3xl font-bold mb-1" id="opp-count">0</div>
    <div class="text-blue-200 text-xs mb-4">Opportunities matched to your profile</div>
    <a href="/awasar?matched=true"
       class="block w-full bg-white text-blue-700 text-center font-bold py-2 rounded-lg text-sm hover:bg-blue-50 transition">
        View Matched Jobs →
    </a>
</div>
                        <div class="space-y-2">
                            <a href="/sathi" class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                <span class="text-xl">💬</span>
                                <div>
                                    <div class="text-sm font-semibold text-gray-700">Chat with Sathi</div>
                                    <div class="text-xs text-gray-400">Ask anything about schemes</div>
                                </div>
                            </a>
                            <a href="/documents" class="flex items-center gap-3 p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                <span class="text-xl">📄</span>
                                <div>
                                    <div class="text-sm font-semibold text-gray-700">Document Vault</div>
                                    <div class="text-xs text-gray-400">Upload & manage documents</div>
                                </div>
                            </a>
                            <a href="/applications" class="flex items-center gap-3 p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                                <span class="text-xl">📋</span>
                                <div>
                                    <div class="text-sm font-semibold text-gray-700">Track Applications</div>
                                    <div class="text-xs text-gray-400">Check application status</div>
                                </div>
                            </a>
                            <a href="/schemes" class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                                <span class="text-xl">🔍</span>
                                <div>
                                    <div class="text-sm font-semibold text-gray-700">Browse Schemes</div>
                                    <div class="text-xs text-gray-400">Explore all 500+ schemes</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Benefit Total -->
                    <div class="bg-gradient-to-br from-green-700 to-green-900 text-white rounded-xl p-5">
                        <div class="text-green-200 text-xs mb-1">Total Benefits You Can Claim</div>
                        <div class="text-3xl font-bold mb-1" id="benefit-total">₹0</div>
                        <div class="text-green-200 text-xs mb-4">Across all eligible schemes</div>
                        <a href="/schemes?matched=true"
                           class="block w-full bg-white text-green-700 text-center font-bold py-2 rounded-lg text-sm hover:bg-green-50 transition">
                            Start Claiming →
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
        console.log(token);
        var user = window.nagrik?.user || JSON.parse(localStorage.getItem('nagrik_user') || 'null');
         console.log(user);

        if (!token || !user) {
            document.getElementById('not-logged-in').classList.remove('hidden');
            document.getElementById('dashboard-content').classList.add('hidden');
        } else {
            loadDashboard();
        }

        async function loadDashboard() {
            const headers = {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token
            };

            // Set basic user info
            document.getElementById('user-name').textContent = user.name || 'NagrikSathi User';
            document.getElementById('user-phone').textContent = '+91 ' + user.phone;
            document.getElementById('user-tier').textContent = user.subscription_tier || 'Free';

            // Load profile
            try {
                const profileRes = await fetch('/api/v1/profile', { headers });
                const profileData = await profileRes.json();
                const profile = profileData.profile;

                if (profile) {
                    document.getElementById('p-name').textContent = profile.name || '—';
                    document.getElementById('p-state').textContent = profile.state || '—';
                    document.getElementById('p-occupation').textContent = profile.occupation || '—';
                    document.getElementById('p-income').textContent = profile.annual_income ? '₹' + Number(profile.annual_income).toLocaleString('en-IN') + '/yr' : '—';
                    document.getElementById('p-category').textContent = profile.caste_category || '—';
                }
            } catch(e) {}

            // Load Nagrik Score
            try {
                const scoreRes = await fetch('/api/v1/profile/nagrik-score', { headers });
                const scoreData = await scoreRes.json();
                const score = scoreData.score || 0;

                document.getElementById('score-number').textContent = score;
                document.getElementById('stat-score').textContent = score;

                // Animate ring
                const offset = 251.2 - (251.2 * score / 1000);
                document.getElementById('score-ring').style.strokeDashoffset = offset;
                document.getElementById('score-ring').style.stroke = score > 600 ? '#16a34a' : score > 300 ? '#f97316' : '#dc2626';
            } catch(e) {}

            // Load benefit total
            try {
                const benefitRes = await fetch('/api/v1/schemes/benefit-total', { headers });
                const benefitData = await benefitRes.json();
                const total = benefitData.total || 0;
                const formatted = '₹' + Number(total).toLocaleString('en-IN');
                document.getElementById('stat-benefit').textContent = formatted;
                document.getElementById('benefit-total').textContent = formatted;
            } catch(e) {}

            // Load matched schemes
            try {
                const schemesRes = await fetch('/api/v1/schemes/matched', { headers });
                const schemesData = await schemesRes.json();
                const schemes = schemesData.data || [];

                document.getElementById('stat-schemes').textContent = schemes.length;

                const list = document.getElementById('schemes-list');
                if (schemes.length === 0) {
                    list.innerHTML = `
                        <div class="text-center py-8 text-gray-400">
                            <div class="text-2xl mb-2">📋</div>
                            <div class="text-sm">Complete your profile to see matched schemes</div>
                            <a href="/profile/setup" class="text-orange-500 text-xs hover:underline mt-1 inline-block">Setup Profile →</a>
                        </div>`;
                } else {
                    list.innerHTML = schemes.slice(0, 5).map(match => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-orange-50 transition">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800 text-sm">${match.scheme.name}</div>
                                <div class="text-xs text-gray-400">${match.scheme.category?.name || ''}</div>
                            </div>
                            <div class="text-right ml-3">
                                <div class="font-bold text-green-700 text-sm">₹${Number(match.benefit_value).toLocaleString('en-IN')}</div>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Eligible</span>
                            </div>
                        </div>
                    `).join('');
                }

                // Set messages left
                document.getElementById('stat-messages').textContent =
    user.subscription_tier === 'sathi-pro' ? '∞' :
    user.subscription_tier === 'sathi-plus' ? '200' : '20';
            } catch(e) {
                document.getElementById('schemes-list').innerHTML =
                    '<div class="text-center py-4 text-gray-400 text-sm">Could not load schemes</div>';
            }

                 // Load matched opportunities count
try {
    const oppRes  = await fetch('/api/v1/opportunities/matched', { headers });
    const oppData = await oppRes.json();
    const opps    = oppData.data || [];
    document.getElementById('opp-count').textContent = opps.length;
} catch(e) {}
        }

   
    </script>

</x-app-layout>
