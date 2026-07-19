<x-app-layout title="Login — NagrikSathi">
<div class="min-h-screen bg-gray-50 py-12 flex items-start justify-center">
    <div class="w-full max-w-md px-4">

        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="flex justify-center items-center gap-3 mb-3">
                <div class="flex flex-col w-1.5 h-12 rounded overflow-hidden">
                    <div class="flex-1 bg-orange-500"></div>
                    <div class="flex-1 bg-white border border-gray-200"></div>
                    <div class="flex-1 bg-green-700"></div>
                </div>
                <span class="text-3xl font-bold text-blue-900">NagrikSathi</span>
            </div>
            <p class="text-gray-500 text-sm">Har Nagrik Ka Apna Sathi</p>
        </div>

        <!-- Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden w-full">

            <!-- Header -->
            <div class="bg-blue-900 text-white p-5 text-center">
                <div class="text-3xl mb-1">🔐</div>
                <h2 class="font-bold text-lg">Login / Register</h2>
                <p class="text-blue-200 text-xs mt-1" id="header-sub">Choose how you'd like to continue</p>
            </div>

            <div class="p-6">

                <!-- Step 1 — Method choice + identifier input -->
                <div id="step-email">

                    <!-- Method toggle -->
                    <div class="flex bg-gray-100 rounded-xl p-1 mb-5">
                        <button type="button" id="tab-phone" onclick="switchMethod('phone')"
                            class="flex-1 py-2 rounded-lg text-sm font-semibold transition bg-blue-900 text-white">
                            📱 Mobile
                        </button>
                        <button type="button" id="tab-email" onclick="switchMethod('email')"
                            class="flex-1 py-2 rounded-lg text-sm font-semibold transition text-gray-500">
                            📧 Email
                        </button>
                    </div>

                    <!-- Phone input -->
                    <div id="phone-input-block" class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                        <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden focus-within:border-orange-400 focus-within:ring-1 focus-within:ring-orange-400">
                            <span class="px-3 text-sm text-gray-500 border-r border-gray-300 py-3">🇮🇳 +91</span>
                            <input type="tel" id="phone-input" maxlength="10" inputmode="numeric"
                                placeholder="98765 43210"
                                class="w-full px-3 py-3 text-sm outline-none"
                                onkeydown="if(event.key==='Enter') sendOtp()"
                                oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
                        </div>
                    </div>

                    <!-- Email input -->
                    <div id="email-input-block" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email-input"
                            placeholder="yourname@gmail.com"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400"
                            onkeydown="if(event.key==='Enter') sendOtp()">
                    </div>

                    <div id="identifier-error" class="hidden text-red-500 text-xs mt-1 mb-2"></div>

                    <button onclick="sendOtp()" id="send-otp-btn"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition text-sm">
                        Send OTP →
                    </button>

                    <div class="mt-4 p-3 bg-blue-50 rounded-xl">
                        <p class="text-xs text-blue-700 text-center" id="otp-note">
                            🔒 OTP valid for 10 minutes.
                        </p>
                    </div>
                </div>

                <!-- Step 2 — OTP Input -->
                <div id="step-otp" class="hidden">
                    <div class="text-center mb-5">
                        <div class="text-4xl mb-2" id="otp-step-icon">📱</div>
                        <h3 class="font-bold text-gray-800">Enter OTP</h3>
                        <p class="text-gray-500 text-sm mt-1">
                            Sent to <strong id="display-identifier"></strong>
                            <button onclick="goBack()" class="text-orange-500 hover:underline ml-1 text-xs">Change</button>
                        </p>
                    </div>

                    <div class="flex justify-center gap-2 mb-5">
                        @for($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1"
                            class="otp-box w-11 h-12 border-2 border-gray-300 rounded-xl text-center text-xl font-bold outline-none focus:border-orange-500 transition"
                            oninput="otpInput(this, {{ $i }})"
                            onkeydown="otpKeyDown(this, {{ $i }}, event)">
                        @endfor
                    </div>

                    <div id="otp-error" class="hidden text-red-500 text-xs text-center mb-3"></div>

                    <button onclick="verifyOtp()" id="verify-btn"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition text-sm">
                        Verify & Login →
                    </button>

                    <div class="text-center mt-4 text-xs text-gray-400">
                        <span id="countdown-text">Resend in <span id="countdown">60</span>s</span>
                        <button id="resend-btn" onclick="resendOtp()" class="hidden text-orange-500 hover:underline font-medium">
                            Resend OTP
                        </button>
                    </div>
                </div>

                <!-- Step 3 — Success -->
                <div id="step-success" class="hidden text-center py-4">
                    <div class="text-5xl mb-4">🎉</div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Welcome to NagrikSathi!</h2>
                    <p class="text-sm text-gray-500 mb-4">Redirecting to your dashboard...</p>
                    <div class="flex justify-center">
                        <div class="w-8 h-8 border-4 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Benefits -->
        <div class="mt-6 grid grid-cols-3 gap-3 text-center">
            <div class="bg-white border border-gray-200 rounded-xl p-3">
                <div class="text-xl mb-1">🆓</div>
                <div class="text-xs text-gray-600 font-medium">Always Free</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3">
                <div class="text-xl mb-1">🔒</div>
                <div class="text-xs text-gray-600 font-medium">100% Secure</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3">
                <div class="text-xl mb-1">🇮🇳</div>
                <div class="text-xs text-gray-600 font-medium">Made in India</div>
            </div>
        </div>

    </div>
</div>

<script>
    var currentMethod     = 'phone'; // 'phone' | 'email'
    var currentIdentifier = '';
    var countdownTimer    = null;

    function switchMethod(method) {
        currentMethod = method;

        document.getElementById('tab-phone').classList.toggle('bg-blue-900', method === 'phone');
        document.getElementById('tab-phone').classList.toggle('text-white', method === 'phone');
        document.getElementById('tab-phone').classList.toggle('text-gray-500', method !== 'phone');
        document.getElementById('tab-email').classList.toggle('bg-blue-900', method === 'email');
        document.getElementById('tab-email').classList.toggle('text-white', method === 'email');
        document.getElementById('tab-email').classList.toggle('text-gray-500', method !== 'email');

        document.getElementById('phone-input-block').classList.toggle('hidden', method !== 'phone');
        document.getElementById('email-input-block').classList.toggle('hidden', method !== 'email');
        document.getElementById('identifier-error').classList.add('hidden');

        document.getElementById('otp-note').textContent = method === 'phone'
            ? '🔒 OTP valid for 10 minutes.'
            : '🔒 OTP valid for 10 minutes. Check spam if not received.';
    }

    function currentIdentifierValue() {
        return currentMethod === 'phone'
            ? document.getElementById('phone-input').value.trim()
            : document.getElementById('email-input').value.trim();
    }

    function validIdentifier(value) {
        if (currentMethod === 'phone') {
            return /^[6-9]\d{9}$/.test(value);
        }
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }

    async function sendOtp() {
        var value   = currentIdentifierValue();
        var errorEl = document.getElementById('identifier-error');

        if (!validIdentifier(value)) {
            errorEl.textContent = currentMethod === 'phone'
                ? 'Please enter a valid 10-digit mobile number'
                : 'Please enter a valid email address';
            errorEl.classList.remove('hidden');
            return;
        }

        errorEl.classList.add('hidden');
        var btn = document.getElementById('send-otp-btn');
        btn.textContent = 'Sending...';
        btn.disabled    = true;

        var endpoint = currentMethod === 'phone' ? '/api/v1/auth/otp/send' : '/api/v1/auth/email/otp/send';
        var payload  = currentMethod === 'phone' ? { phone: value } : { email: value };

        try {
            var response = await fetch(endpoint, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body:    JSON.stringify(payload),
            });
            var text  = await response.text();
            var clean = text.replace(/^﻿/, ''); // Remove BOM
            var data  = JSON.parse(clean);

            if (data.success) {
                currentIdentifier = value;
                document.getElementById('display-identifier').textContent =
                    currentMethod === 'phone' ? ('+91 ' + value) : value;
                document.getElementById('otp-step-icon').textContent = currentMethod === 'phone' ? '📱' : '📧';
                document.getElementById('step-email').classList.add('hidden');
                document.getElementById('step-otp').classList.remove('hidden');
                startCountdown();
                document.querySelectorAll('.otp-box')[0].focus();
            } else {
                errorEl.textContent = data.message || 'Failed to send OTP. Try again.';
                errorEl.classList.remove('hidden');
            }
        } catch(e) {
            errorEl.textContent = 'Network error. Please try again.';
            errorEl.classList.remove('hidden');
        } finally {
            btn.textContent = 'Send OTP →';
            btn.disabled    = false;
        }
    }

    async function verifyOtp() {
        var boxes   = document.querySelectorAll('.otp-box');
        var otp     = Array.from(boxes).map(function(b) { return b.value; }).join('');
        var errorEl = document.getElementById('otp-error');

        if (otp.length !== 6) {
            errorEl.textContent = 'Please enter the complete 6-digit OTP';
            errorEl.classList.remove('hidden');
            return;
        }

        errorEl.classList.add('hidden');
        var btn = document.getElementById('verify-btn');
        btn.textContent = 'Verifying...';
        btn.disabled    = true;

        var endpoint = currentMethod === 'phone' ? '/api/v1/auth/otp/verify' : '/api/v1/auth/email/otp/verify';
        var payload  = currentMethod === 'phone'
            ? { phone: currentIdentifier, otp: otp }
            : { email: currentIdentifier, otp: otp };

        try {
            var response = await fetch(endpoint, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body:    JSON.stringify(payload),
            });
            var text  = await response.text();
            var clean = text.replace(/^﻿/, ''); // Remove BOM
            var data  = JSON.parse(clean);

            if (data.success) {
                localStorage.setItem('nagrik_token', data.token);
                localStorage.setItem('nagrik_user', JSON.stringify({
                    id:                data.user.id,
                    name:              data.user.name,
                    email:             data.user.email,
                    phone:             data.user.phone,
                    language:          data.user.language,
                    subscription_tier: data.user.subscription_tier || 'free',
                    nagrik_score:      data.user.nagrik_score || 0,
                    role:              data.role,
                    all_roles:         data.all_roles,
                }));

                clearInterval(countdownTimer);
                document.getElementById('step-otp').classList.add('hidden');
                document.getElementById('step-success').classList.remove('hidden');

                var redirectMap = {
                    'csc_agent'   : '/csc/dashboard',
                    'sathi_agent' : '/dashboard',
                    'admin'       : '/admin',
                };
                var redirect = redirectMap[data.role] || '/dashboard';
                setTimeout(function() { window.location.href = redirect; }, 2000);

            } else {
                errorEl.textContent = data.message || 'Invalid OTP. Please try again.';
                errorEl.classList.remove('hidden');
                btn.textContent = 'Verify & Login →';
                btn.disabled    = false;
            }
        } catch(e) {
            errorEl.textContent = 'Network error. Please try again.';
            errorEl.classList.remove('hidden');
            btn.textContent = 'Verify & Login →';
            btn.disabled    = false;
        }
    }

    function otpInput(el, index) {
        el.value    = el.value.replace(/\D/, '');
        var boxes   = document.querySelectorAll('.otp-box');
        if (el.value && index < 5) boxes[index + 1].focus();
    }

    function otpKeyDown(el, index, event) {
        var boxes = document.querySelectorAll('.otp-box');
        if (event.key === 'Backspace' && !el.value && index > 0) {
            boxes[index - 1].focus();
        }
        if (event.key === 'Enter') verifyOtp();
    }

    function goBack() {
        clearInterval(countdownTimer);
        document.getElementById('step-otp').classList.add('hidden');
        document.getElementById('step-email').classList.remove('hidden');
        document.querySelectorAll('.otp-box').forEach(function(b) { b.value = ''; });
    }

    function startCountdown() {
        var seconds = 60;
        document.getElementById('countdown').textContent = seconds;
        document.getElementById('countdown-text').classList.remove('hidden');
        document.getElementById('resend-btn').classList.add('hidden');

        countdownTimer = setInterval(function() {
            seconds--;
            document.getElementById('countdown').textContent = seconds;
            if (seconds <= 0) {
                clearInterval(countdownTimer);
                document.getElementById('countdown-text').classList.add('hidden');
                document.getElementById('resend-btn').classList.remove('hidden');
            }
        }, 1000);
    }

    async function resendOtp() {
        document.getElementById('step-otp').classList.add('hidden');
        document.getElementById('step-email').classList.remove('hidden');
        if (currentMethod === 'phone') {
            document.getElementById('phone-input').value = currentIdentifier;
        } else {
            document.getElementById('email-input').value = currentIdentifier;
        }
        await sendOtp();
    }
</script>
</x-app-layout>
