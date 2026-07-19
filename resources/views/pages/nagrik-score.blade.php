<x-app-layout title="Nagrik Score">

    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold mb-2">🏆 Your Nagrik Score</h1>
            <p class="text-blue-200 text-sm">Your civic engagement score — how well you're using your government entitlements</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-10">

        <!-- Not logged in -->
        <div id="not-logged-in" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center mb-6">
            <div class="text-3xl mb-2">🔐</div>
            <h3 class="font-bold text-gray-800 mb-2">Please Login to See Your Score</h3>
            <a href="/login" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold text-sm">Login Now →</a>
        </div>

        <div id="score-content">

         <!-- Main Score Card -->
<div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6">
    <div class="flex flex-col md:flex-row items-center gap-8">

    <!-- Animated Score Ring -->
    <div class="relative flex-shrink-0" style="width:140px; height:140px;">
        <svg style="width:140px; height:140px;" class="-rotate-90" viewBox="0 0 200 200">
                        <!-- Background ring -->
                        <circle cx="100" cy="100" r="80" fill="none" stroke="#f3f4f6" stroke-width="20"/>
                        <!-- Score ring -->
                        <circle cx="100" cy="100" r="80" fill="none" stroke="#f97316" stroke-width="20"
                            stroke-dasharray="502.4"
                            stroke-dashoffset="502.4"
                            id="main-score-ring"
                            stroke-linecap="round"
                            style="transition: stroke-dashoffset 2s ease-in-out"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-bold text-gray-800" id="main-score-number">0</span>
                        <span class="text-sm text-gray-400">out of 1000</span>
                  </div>
            </div>

            <!-- Score Info -->
            <div class="flex-1 text-left">
                <div id="score-label" class="inline-block px-5 py-1.5 rounded-full text-white font-bold text-base mb-3">
                    Loading...
                </div>
                <p id="score-message" class="text-gray-500 text-sm mb-4"></p>
                <button onclick="shareScore()"
                    class="border border-orange-400 text-orange-500 hover:bg-orange-50 px-5 py-2 rounded-lg text-sm font-semibold transition">
                    📤 Share My Score
                </button>
            </div>

    </div>
</div>
            <!-- Score Breakdown -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6">
                <h2 class="font-bold text-gray-800 text-lg mb-5">📊 Score Breakdown</h2>

                <div class="space-y-5">

                    <!-- Profile -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">👤</div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">Profile Completeness</div>
                                    <div class="text-xs text-gray-400">Fill all profile fields to earn full points</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-800" id="profile-pts">0</span>
                                <span class="text-gray-400 text-sm">/400</span>
                            </div>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div id="profile-bar" class="h-3 bg-orange-500 rounded-full transition-all duration-1000" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">📄</div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">Documents Uploaded</div>
                                    <div class="text-xs text-gray-400">Upload key documents — 40pts each, max 200</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-800" id="docs-pts">0</span>
                                <span class="text-gray-400 text-sm">/200</span>
                            </div>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div id="docs-bar" class="h-3 bg-blue-500 rounded-full transition-all duration-1000" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Schemes Claimed -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">✅</div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">Schemes Claimed</div>
                                    <div class="text-xs text-gray-400">Claim eligible schemes — 50pts each, max 200</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-800" id="claimed-pts">0</span>
                                <span class="text-gray-400 text-sm">/200</span>
                            </div>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div id="claimed-bar" class="h-3 bg-green-500 rounded-full transition-all duration-1000" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Sathi Usage -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">💬</div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">Sathi AI Usage</div>
                                    <div class="text-xs text-gray-400">Use Sathi to ask questions — 20pts each, max 100</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-800" id="sathi-pts">0</span>
                                <span class="text-gray-400 text-sm">/100</span>
                            </div>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div id="sathi-bar" class="h-3 bg-purple-500 rounded-full transition-all duration-1000" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Family -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center">👨‍👩‍👧</div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">Family Members Added</div>
                                    <div class="text-xs text-gray-400">Add family members — 50pts each, max 100</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-800" id="family-pts">0</span>
                                <span class="text-gray-400 text-sm">/100</span>
                            </div>
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div id="family-bar" class="h-3 bg-pink-500 rounded-full transition-all duration-1000" style="width: 0%"></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- How to Improve -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6">
                <h2 class="font-bold text-gray-800 text-lg mb-4">🚀 How to Improve Your Score</h2>
                <div id="improvement-tips" class="space-y-3"></div>
            </div>

            <!-- Score Levels -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6">
                <h2 class="font-bold text-gray-800 text-lg mb-4">🏅 Score Levels</h2>
                <div class="space-y-3">
                    <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-xl">🥉</div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-700 text-sm">Beginner Nagrik</div>
                            <div class="text-xs text-gray-400">Score 0 — 300</div>
                        </div>
                        <div class="h-2 w-24 bg-gray-200 rounded-full">
                            <div class="h-2 bg-gray-400 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-3 bg-orange-50 rounded-lg">
                        <div class="w-12 h-12 bg-orange-400 rounded-full flex items-center justify-center text-xl">🥈</div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-700 text-sm">Active Nagrik</div>
                            <div class="text-xs text-gray-400">Score 301 — 600</div>
                        </div>
                        <div class="h-2 w-24 bg-gray-200 rounded-full">
                            <div class="h-2 bg-orange-400 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-3 bg-green-50 rounded-lg">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-xl">🥇</div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-700 text-sm">Super Nagrik</div>
                            <div class="text-xs text-gray-400">Score 601 — 1000</div>
                        </div>
                        <div class="h-2 w-24 bg-gray-200 rounded-full">
                            <div class="h-2 bg-green-500 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');

        if (!token) {
            document.getElementById('not-logged-in').classList.remove('hidden');
            document.getElementById('score-content').classList.add('hidden');
        } else {
            loadScore();
        }

        async function loadScore() {
            try {
                const res = await fetch('/api/v1/profile/nagrik-score', {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                });
                const data = await res.json();
                const score = data.score || 0;

                // Animate score number
                animateNumber('main-score-number', 0, score, 2000);

                // Animate ring
                setTimeout(() => {
                    const offset = 502.4 - (502.4 * score / 1000);
                    const ring = document.getElementById('main-score-ring');
                    ring.style.strokeDashoffset = offset;

                    if (score > 600) {
                        ring.style.stroke = '#16a34a';
                    } else if (score > 300) {
                        ring.style.stroke = '#f97316';
                    } else {
                        ring.style.stroke = '#dc2626';
                    }
                }, 100);

                // Score label
                const label = document.getElementById('score-label');
                const message = document.getElementById('score-message');
                if (score > 600) {
                    label.textContent = '🥇 Super Nagrik';
                    label.className = 'inline-block px-6 py-2 rounded-full text-white font-bold text-lg mb-3 bg-green-600';
                    message.textContent = 'Excellent! You are making great use of your government entitlements.';
                } else if (score > 300) {
                    label.textContent = '🥈 Active Nagrik';
                    label.className = 'inline-block px-6 py-2 rounded-full text-white font-bold text-lg mb-3 bg-orange-500';
                    message.textContent = 'Good progress! Complete your profile and upload documents to boost your score.';
                } else {
                    label.textContent = '🥉 Beginner Nagrik';
                    label.className = 'inline-block px-6 py-2 rounded-full text-white font-bold text-lg mb-3 bg-gray-500';
                    message.textContent = 'Just getting started! Complete your profile to unlock your full benefits.';
                }

                // Breakdown (estimated from score)
                const profilePts = Math.min(score, 400);
                const docsPts    = Math.min(Math.max(score - 400, 0), 200);
                const claimedPts = Math.min(Math.max(score - 600, 0), 200);
                const sathiPts   = Math.min(Math.max(score - 800, 0), 100);
                const familyPts  = Math.min(Math.max(score - 900, 0), 100);

                setTimeout(() => {
                    document.getElementById('profile-pts').textContent = profilePts;
                    document.getElementById('profile-bar').style.width = (profilePts / 400 * 100) + '%';

                    document.getElementById('docs-pts').textContent = docsPts;
                    document.getElementById('docs-bar').style.width = (docsPts / 200 * 100) + '%';

                    document.getElementById('claimed-pts').textContent = claimedPts;
                    document.getElementById('claimed-bar').style.width = (claimedPts / 200 * 100) + '%';

                    document.getElementById('sathi-pts').textContent = sathiPts;
                    document.getElementById('sathi-bar').style.width = (sathiPts / 100 * 100) + '%';

                    document.getElementById('family-pts').textContent = familyPts;
                    document.getElementById('family-bar').style.width = (familyPts / 100 * 100) + '%';
                }, 300);

                // Improvement tips
                const tips = [];
                if (profilePts < 400) tips.push({ icon: '👤', text: 'Complete your profile to earn up to 400 points', link: '/profile/setup', label: 'Complete Profile' });
                if (docsPts < 200) tips.push({ icon: '📄', text: 'Upload documents like Aadhaar, PAN, Income Certificate', link: '/documents', label: 'Upload Documents' });
                if (claimedPts < 200) tips.push({ icon: '✅', text: 'Apply for eligible schemes and mark them as claimed', link: '/schemes', label: 'Browse Schemes' });
                if (sathiPts < 100) tips.push({ icon: '💬', text: 'Chat with Sathi AI to ask questions about your schemes', link: '/sathi', label: 'Chat with Sathi' });
                if (familyPts < 100) tips.push({ icon: '👨‍👩‍👧', text: 'Add family members to get combined benefit matching', link: '/dashboard', label: 'Add Family' });

                document.getElementById('improvement-tips').innerHTML = tips.map(tip => `
                    <div class="flex items-center gap-4 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                        <span class="text-2xl">${tip.icon}</span>
                        <div class="flex-1">
                            <div class="text-sm text-gray-700">${tip.text}</div>
                        </div>
                        <a href="${tip.link}"
                           class="bg-blue-900 text-white text-xs px-4 py-2 rounded-lg font-semibold hover:bg-blue-800 transition whitespace-nowrap">
                            ${tip.label} →
                        </a>
                    </div>
                `).join('');

            } catch(e) {}
        }

        function animateNumber(id, start, end, duration) {
            const el = document.getElementById(id);
            const range = end - start;
            const startTime = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(start + range * eased);
                if (progress < 1) requestAnimationFrame(update);
            }
            requestAnimationFrame(update);
        }

        function shareScore() {
            const score = document.getElementById('main-score-number').textContent;
            const text = `My Nagrik Score is ${score}/1000 on NagrikSathi! 🇮🇳 I discovered ₹19,63,100 in government benefits I'm eligible for. Check yours free at nagriksathi.com`;
            if (navigator.share) {
                navigator.share({ title: 'My Nagrik Score', text });
            } else {
                navigator.clipboard.writeText(text);
                alert('Score copied to clipboard!');
            }
        }
    </script>

</x-app-layout>
