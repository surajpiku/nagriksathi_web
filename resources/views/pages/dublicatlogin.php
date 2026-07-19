<x-app-layout title="Login / Register">
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

                <!-- Tabs -->
                <div class="flex border-b border-gray-200">
                    <button id="tab-login"
                        onclick="switchTab('login')"
                        class="flex-1 py-4 text-sm font-semibold text-orange-500 border-b-2 border-orange-500 bg-orange-50">
                        Login
                    </button>
                    <button id="tab-register"
                        onclick="switchTab('register')"
                        class="flex-1 py-4 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700">
                        New Registration
                    </button>
                </div>

                <div class="p-6">
                    <!-- Auth Method Tabs -->
<div class="flex border-b border-gray-200 mb-6">
    <button id="tab-phone" onclick="switchAuthMethod('phone')"
        class="flex-1 py-3 text-sm font-semibold text-orange-500 border-b-2 border-orange-500">
        📱 Phone Number
    </button>
    <button id="tab-email" onclick="switchAuthMethod('email')"
        class="flex-1 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700">
        📧 Email Address
    </button>
</div>

<!-- Phone Input -->
<div id="phone-method">
    <!-- existing phone input here -->
</div>

<!-- Email Input -->
<div id="email-method" class="hidden">
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
        <input type="email" id="email-input" placeholder="yourname@gmail.com"
            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-orange-400">
        <div id="email-error" class="hidden text-red-500 text-xs mt-1"></div>
    </div>
    <button onclick="sendEmailOtp()" id="send-email-otp-btn"
        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition">
        Send OTP to Email
    </button>
</div>

                    <!-- Step 1 — Phone Input -->
                    <div id="step-phone">
                        <div class="text-center mb-6">
                            <div class="text-3xl mb-2">📱</div>
                            <h2 class="text-lg font-bold text-gray-800">Enter Your Mobile Number</h2>
                            <p class="text-sm text-gray-500 mt-1">We'll send a 6-digit OTP to verify your number</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                            <div class="flex gap-2">
                                <div class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-3 text-sm text-gray-600 font-semibold">
                                    🇮🇳 +91
                                </div>
                                <input type="tel"
                                    id="phone-input"
                                    maxlength="10"
                                    placeholder="Enter 10-digit mobile number"
                                    class="flex-1 border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400">
                            </div>
                        </div>

                        <div id="phone-error" class="text-red-500 text-xs mb-3 hidden"></div>

                        <button onclick="sendOtp()"
                            id="send-otp-btn"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg transition text-sm">
                            Send OTP
                        </button>

                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-700 text-center">
                                🔒 Your data is safe and encrypted. We never share your information.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 — OTP Input -->
                    <div id="step-otp" class="hidden">
                        <div class="text-center mb-6">
                            <div class="text-3xl mb-2">🔐</div>
                            <h2 class="text-lg font-bold text-gray-800">Enter OTP</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                Sent to <span id="display-phone" class="font-semibold text-gray-700"></span>
                                <button onclick="goBack()" class="text-orange-500 hover:underline ml-1">Change</button>
                            </p>
                        </div>

                        <!-- OTP Boxes -->
                        <div class="flex gap-2 justify-center mb-4">
                            <input type="text" maxlength="1" class="otp-box w-12 h-12 border-2 border-gray-300 rounded-lg text-center text-xl font-bold outline-none focus:border-orange-400" oninput="otpInput(this, 0)">
                            <input type="text" maxlength="1" class="otp-box w-12 h-12 border-2 border-gray-300 rounded-lg text-center text-xl font-bold outline-none focus:border-orange-400" oninput="otpInput(this, 1)">
                            <input type="text" maxlength="1" class="otp-box w-12 h-12 border-2 border-gray-300 rounded-lg text-center text-xl font-bold outline-none focus:border-orange-400" oninput="otpInput(this, 2)">
                            <input type="text" maxlength="1" class="otp-box w-12 h-12 border-2 border-gray-300 rounded-lg text-center text-xl font-bold outline-none focus:border-orange-400" oninput="otpInput(this, 3)">
                            <input type="text" maxlength="1" class="otp-box w-12 h-12 border-2 border-gray-300 rounded-lg text-center text-xl font-bold outline-none focus:border-orange-400" oninput="otpInput(this, 4)">
                            <input type="text" maxlength="1" class="otp-box w-12 h-12 border-2 border-gray-300 rounded-lg text-center text-xl font-bold outline-none focus:border-orange-400" oninput="otpInput(this, 5)">
                        </div>

                        <div id="otp-error" class="text-red-500 text-xs mb-3 text-center hidden"></div>

                        <button onclick="verifyOtp()"
                            id="verify-otp-btn"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg transition text-sm mb-3">
                            Verify & Login
                        </button>

                        <!-- Resend -->
                        <div class="text-center text-sm text-gray-500">
                            Didn't receive OTP?
                            <button id="resend-btn" onclick="resendOtp()" class="text-orange-500 hover:underline ml-1 hidden">Resend OTP</button>
                            <span id="countdown-text" class="text-gray-400">Resend in <span id="countdown">60</span>s</span>
                        </div>
                    </div>

                    <!-- Step 3 — Success -->
                    <div id="step-success" class="hidden text-center py-4">
                        <div class="text-5xl mb-4">🎉</div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Welcome to NagrikSathi!</h2>
                        <p class="text-sm text-gray-500 mb-6">Your account is ready. Let's set up your profile to discover your benefits.</p>
                        <a href="/dashboard"
                           class="block w-full bg-green-700 hover:bg-green-800 text-white font-semibold py-3 rounded-lg transition text-sm text-center">
                          🎯 Go to My Dashboard →
                        </a>
                    </div>

                </div>
            </div>

            <!-- Benefits -->
            <div class="mt-6 grid grid-cols-3 gap-3 text-center max-w-md mx-auto">
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

    let authMethod = 'phone';

function switchAuthMethod(method) {
    authMethod = method;
    if (method === 'phone') {
        document.getElementById('phone-method').classList.remove('hidden');
        document.getElementById('email-method').classList.add('hidden');
        document.getElementById('tab-phone').className = 'flex-1 py-3 text-sm font-semibold text-orange-500 border-b-2 border-orange-500';
        document.getElementById('tab-email').className = 'flex-1 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700';
    } else {
        document.getElementById('email-method').classList.remove('hidden');
        document.getElementById('phone-method').classList.add('hidden');
        document.getElementById('tab-email').className = 'flex-1 py-3 text-sm font-semibold text-orange-500 border-b-2 border-orange-500';
        document.getElementById('tab-phone').className = 'flex-1 py-3 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700';
    }
}

async function sendEmailOtp() {
    const email   = document.getElementById('email-input').value.trim();
    const errorEl = document.getElementById('email-error');

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errorEl.textContent = 'Please enter a valid email address';
        errorEl.classList.remove('hidden');
        return;
    }

    errorEl.classList.add('hidden');
    const btn = document.getElementById('send-email-otp-btn');
    btn.textContent = 'Sending...';
    btn.disabled    = true;

    try {
        const response = await fetch('/api/v1/auth/email/otp/send', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body:    JSON.stringify({ email }),
        });
        const data = await response.json();

        if (data.success) {
            currentPhone = email; // reuse currentPhone for email
            document.getElementById('display-phone').textContent = email;
            document.getElementById('step-phone').classList.add('hidden');
            document.getElementById('step-otp').classList.remove('hidden');
            startCountdown();

            // Dev OTP autofill
            if (data.dev_otp) {
                const otp   = data.dev_otp.toString();
                const boxes = document.querySelectorAll('.otp-box');
                boxes.forEach((box, i) => box.value = otp[i] || '');
            }
        } else {
            errorEl.textContent = data.message || 'Failed to send OTP';
            errorEl.classList.remove('hidden');
        }
    } catch(e) {
        errorEl.textContent = 'Network error. Please try again.';
        errorEl.classList.remove('hidden');
    } finally {
        btn.textContent = 'Send OTP to Email';
        btn.disabled    = false;
    }
}

// Update verifyOtp to handle email
// Find the existing verifyOtp function and update the fetch URL:
// Change from: '/api/v1/auth/otp/verify'
// The body should send email or phone based on authMethod:


    let currentPhone = '';
    let countdownTimer = null;



    function switchTab(tab) {
        const loginTab    = document.getElementById('tab-login');
        const registerTab = document.getElementById('tab-register');
        if (tab === 'login') {
            loginTab.className    = 'flex-1 py-4 text-sm font-semibold text-orange-500 border-b-2 border-orange-500 bg-orange-50';
            registerTab.className = 'flex-1 py-4 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700';
        } else {
            registerTab.className = 'flex-1 py-4 text-sm font-semibold text-orange-500 border-b-2 border-orange-500 bg-orange-50';
            loginTab.className    = 'flex-1 py-4 text-sm font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700';
        }
    }

    async function sendOtp() {
        const phone   = document.getElementById('phone-input').value.trim();
        const errorEl = document.getElementById('phone-error');

        if (phone.length !== 10 || !/^\d+$/.test(phone)) {
            errorEl.textContent = 'Please enter a valid 10-digit mobile number';
            errorEl.classList.remove('hidden');
            return;
        }

        errorEl.classList.add('hidden');
        const btn = document.getElementById('send-otp-btn');
        btn.textContent = 'Sending...';
        btn.disabled    = true;

        try {
            const response = await fetch('/api/v1/auth/otp/send', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body:    JSON.stringify({ phone }),
            });
            const data = await response.json();

            if (data.success) {
                currentPhone = phone;
                document.getElementById('display-phone').textContent = '+91 ' + phone;
                document.getElementById('step-phone').classList.add('hidden');
                document.getElementById('step-otp').classList.remove('hidden');
                startCountdown();

                // Dev mode — auto fill OTP
                if (data.dev_otp) {
                    const otp   = data.dev_otp.toString();
                    const boxes = document.querySelectorAll('.otp-box');
                    boxes.forEach((box, i) => box.value = otp[i] || '');
                }
            } else {
                errorEl.textContent = data.message || 'Failed to send OTP';
                errorEl.classList.remove('hidden');
                btn.textContent = 'Send OTP';
                btn.disabled    = false;
            }
        } catch(e) {
            errorEl.textContent = 'Network error. Please try again.';
            errorEl.classList.remove('hidden');
            btn.textContent = 'Send OTP';
            btn.disabled    = false;
        }
    }

    async function verifyOtp() {
        const boxes   = document.querySelectorAll('.otp-box');
        const otp     = Array.from(boxes).map(b => b.value).join('');
        const errorEl = document.getElementById('otp-error');

        if (otp.length !== 6) {
            errorEl.textContent = 'Please enter the complete 6-digit OTP';
            errorEl.classList.remove('hidden');
            return;
        }

        errorEl.classList.add('hidden');
        const btn = document.getElementById('verify-otp-btn');
        btn.textContent = 'Verifying...';
        btn.disabled    = true;
        const url = authMethod === 'email'
    ? '/api/v1/auth/email/otp/verify'
    : '/api/v1/auth/otp/verify';



        try {
            const response = await fetch(url, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: authMethod === 'email'
    ? JSON.stringify({ email: currentPhone, otp })
    : JSON.stringify({ phone: currentPhone, otp }),
            });
            const data = await response.json();

            if (data.success) {
                // Save token and user with roles
                localStorage.setItem('nagrik_token', data.token);
                localStorage.setItem('nagrik_user', JSON.stringify({
                   id:        data.user.id,
    name:      data.user.name,
    email:     data.user.email,
    phone:     data.user.phone,
    language:  data.user.language,
    role:      data.role,
    all_roles: data.all_roles,
                }));

                clearInterval(countdownTimer);

                // Show success
                document.getElementById('step-otp').classList.add('hidden');
                document.getElementById('step-success').classList.remove('hidden');

                // Redirect based on role after 2 seconds
                const redirectMap = {
                    'csc_agent'   : '/csc/dashboard',
                    'sathi_agent' : '/dashboard',
                    'specialist'  : '/dashboard',
                    'admin'       : '/admin',
                };
                const redirect = redirectMap[data.role] || '/dashboard';
                setTimeout(() => window.location.href = redirect, 2000);

            } else {
                errorEl.textContent = data.message || 'Invalid OTP. Please try again.';
                errorEl.classList.remove('hidden');
                btn.textContent = 'Verify & Login';
                btn.disabled    = false;
            }
        } catch(e) {
            errorEl.textContent = 'Network error. Please try again.';
            errorEl.classList.remove('hidden');
            btn.textContent = 'Verify & Login';
            btn.disabled    = false;
        }
    }

    function otpInput(el, index) {
        el.value      = el.value.replace(/\D/, '');
        const boxes   = document.querySelectorAll('.otp-box');
        if (el.value && index < 5) boxes[index + 1].focus();
        if (!el.value && index > 0) boxes[index - 1].focus();
    }

    function goBack() {
        document.getElementById('step-otp').classList.add('hidden');
        document.getElementById('step-phone').classList.remove('hidden');
        clearInterval(countdownTimer);
    }

    function startCountdown() {
        let seconds = 60;
        document.getElementById('countdown').textContent = seconds;
        document.getElementById('countdown-text').classList.remove('hidden');
        document.getElementById('resend-btn').classList.add('hidden');

        countdownTimer = setInterval(() => {
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
        document.getElementById('resend-btn').classList.add('hidden');
        document.getElementById('step-otp').classList.add('hidden');
        document.getElementById('step-phone').classList.remove('hidden');
        document.getElementById('phone-input').value = currentPhone;
        await sendOtp();
    }
</script>
</x-app-layout>
