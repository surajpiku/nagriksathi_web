<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NagrikSathi — {{ $title ?? 'Har Nagrik Ka Apna Sathi' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans">
    <script>
    // Run immediately before page renders
 window.nagrik = {
        token: localStorage.getItem('nagrik_token'),
        user:  JSON.parse(localStorage.getItem('nagrik_user') || 'null')
    };

    // Auto-refresh user from API if token exists but user is missing
    if (window.nagrik.token && !window.nagrik.user) {
        fetch('/api/v1/auth/me', {
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + window.nagrik.token }
        }).then(function(r) { return r.json(); }).then(function(d) {
            if (d.user) {
                localStorage.setItem('nagrik_user', JSON.stringify(d.user));
                window.nagrik.user = d.user;
                window.location.reload();
            }
        }).catch(function() {});
    }
    // Global BOM fix for all fetch calls
var originalFetch = window.fetch;
window.fetch = function() {
    return originalFetch.apply(this, arguments).then(function(res) {
        var originalJson = res.json.bind(res);
        res.json = function() {
            return res.text().then(function(text) {
                return JSON.parse(text.replace(/^\uFEFF/, ''));
            });
        };
        return res;
    });
};
</script>

    <!-- Top Utility Bar -->
    <div class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600">
        <div class="max-w-7xl mx-auto px-4 py-1 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span>Skip to main content</span>
                <span>|</span>
                <span class="flex gap-2">
                    <button class="font-bold text-base leading-none">A+</button>
                    <button class="leading-none">A</button>
                    <button class="text-xs leading-none">A-</button>
                </span>
            </div>
            <div class="flex items-center gap-3">
                <span>🌐</span>
                <select class="bg-transparent text-xs border-none outline-none">
                    <option>English</option>
                    <option>हिंदी</option>
                    <option>বাংলা</option>
                    <option>தமிழ்</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="bg-white border-b-4 border-orange-500 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-3">
                <img src="/images/logo.png"  width="40px" height="auto">
                <div>
                    <div class="font-bold text-xl text-blue-900">NagrikSathi</div>
                    <div class="text-xs text-gray-500 hidden md:block">Har Nagrik Ka Apna Sathi | हर नागरिक का अपना साथी</div>
                </div>
            </a>

            <!-- Desktop Nav -->
          <!-- Desktop Nav -->
<div class="hidden md:flex items-center gap-1 text-sm font-medium">
    @php $current = request()->path(); @endphp

    <a href="/schemes" class="px-3 py-2 rounded transition text-xs {{ str_starts_with($current, 'schemes') ? 'bg-orange-100 text-orange-600 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">Schemes</a>
    <a href="/awasar" class="px-3 py-2 rounded transition text-xs {{ str_starts_with($current, 'awasar') ? 'bg-orange-100 text-orange-600 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">Awasar</a>
    <a href="/sathi" class="px-3 py-2 rounded transition text-xs {{ str_starts_with($current, 'sathi') ? 'bg-orange-100 text-orange-600 font-semibold' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">Sathi AI</a>

    <!-- More Dropdown -->
    <div class="relative">
        <button onclick="toggleDropdown('more-menu')" class="px-3 py-2 rounded transition text-xs text-gray-700 hover:bg-orange-50 hover:text-orange-600 flex items-center gap-1">
            More ▾
        </button>
        <div id="more-menu" class="hidden absolute top-full left-0 mt-1 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-50">
            <a href="/search" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-orange-50 rounded-t-xl">🔍 Smart Search</a>
            <a href="/calculator" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-orange-50">💰 Calculator</a>
            <a href="/life-events" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-orange-50">🌟 Life Events</a>
          <a href="/hunar" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-orange-50">🔧 Hunar Directory</a>
<a href="/seva-mitra-banen" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-orange-50 rounded-b-xl">🏢 Join Seva Mitra</a>
</div>
    </div>

    <!-- Logged Out -->
    <div id="nav-logged-out" class="ml-2">
        <a href="/login" class="bg-orange-500 text-white py-2 px-4 rounded hover:bg-orange-600 transition font-semibold text-xs">Login</a>
    </div>

    <!-- Logged In -->
    <div id="nav-logged-in" class="ml-1 items-center gap-1" style="display:none;">
        <a href="/nagrik-score" class="px-2 py-1.5 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold hover:bg-orange-200 transition">
            🏆 <span id="nav-score">—</span>
        </a>

        <!-- Account Dropdown -->
        <div class="relative">
            <button onclick="toggleDropdown('account-menu')" class="flex items-center gap-1.5 px-3 py-2 text-blue-900 font-semibold hover:bg-blue-50 rounded transition text-xs">
                👤 <span id="nav-user-name">Account</span> ▾
            </button>
            <div id="account-menu" class="hidden absolute top-full right-0 mt-1 w-52 bg-white border border-gray-200 rounded-xl shadow-lg z-50">
                <a href="/dashboard" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-blue-50 rounded-t-xl font-semibold">📊 My Dashboard</a>
                <a href="/profile/setup" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-blue-50">👤 My Profile</a>
                <a href="/documents" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-blue-50">📄 Documents</a>
                <a href="/applications" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-blue-50">📋 Applications</a>
                <a href="/nagrik-score" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-blue-50">🏆 Nagrik Score</a>
                <a href="/subscription" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-blue-50">💳 Subscription</a>
                <a href="/csc/dashboard" id="nav-csc-link" style="display:none;" class="flex items-center gap-2 px-4 py-2.5 text-xs text-green-700 hover:bg-green-50 font-semibold border-t border-gray-100">🏢 Seva Mitra</a>
                <div class="border-t border-gray-100">
                    <button onclick="logout()" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs text-red-500 hover:bg-red-50 rounded-b-xl">🚪 Logout</button>
                </div>
            </div>
        </div>
    </div>
</div>
            <!-- Mobile Hamburger -->
            <button onclick="toggleOffcanvas()" class="md:hidden flex flex-col gap-1.5 p-2 rounded-lg hover:bg-gray-100 transition">
                <span class="w-6 h-0.5 bg-gray-700 block"></span>
                <span class="w-6 h-0.5 bg-gray-700 block"></span>
                <span class="w-6 h-0.5 bg-gray-700 block"></span>
            </button>

        </div>
    </nav>

    <!-- Mobile Offcanvas Overlay -->
    <div id="offcanvas-overlay" class="hidden fixed inset-0 bg-black/50 z-40" onclick="toggleOffcanvas()"></div>

    <!-- Mobile Offcanvas -->
    <div id="offcanvas" class="fixed top-0 left-0 h-full w-72 bg-white z-50 shadow-2xl transform -translate-x-full transition-transform duration-300">

        <!-- Header -->
        <div class="bg-blue-900 text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="flex flex-col w-1 h-8 rounded overflow-hidden">
                    <div class="flex-1 bg-orange-500"></div>
                    <div class="flex-1 bg-white"></div>
                    <div class="flex-1 bg-green-600"></div>
                </div>
                <span class="font-bold text-lg">NagrikSathi</span>
            </div>
            <button onclick="toggleOffcanvas()" class="text-white text-2xl leading-none">✕</button>
        </div>

        <!-- User Info -->
        <div id="offcanvas-user" class="hidden bg-blue-50 border-b border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">👤</div>
                <div>
                    <div class="font-semibold text-gray-800 text-sm" id="offcanvas-name">User</div>
                    <div class="text-xs text-gray-500" id="offcanvas-phone"></div>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <a href="/dashboard" class="flex-1 text-center bg-blue-900 text-white text-xs py-1.5 rounded-lg font-semibold">Dashboard</a>
                <a href="/nagrik-score" class="flex-1 text-center bg-orange-500 text-white text-xs py-1.5 rounded-lg font-semibold">My Score</a>
            </div>
        </div>

        <!-- Nav Links -->
        <div class="p-4 space-y-1 overflow-y-auto">
            <a href="/" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>🏠</span><span class="font-medium">Home</span></a>
            <a href="/schemes" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>📋</span><span class="font-medium">All Schemes</span></a>
            <a href="/awasar" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>💼</span><span class="font-medium">Sarkari Awasar</span></a>
            <a href="/search" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>🔍</span><span class="font-medium">Smart Search</span></a>
            <a href="/sathi" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>💬</span><span class="font-medium">Sathi AI Chat</span></a>
            <a href="/calculator" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>💰</span><span class="font-medium">Benefit Calculator</span></a>
            <a href="/life-events" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>🌟</span><span class="font-medium">Life Events</span></a>
            <a href="/documents" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>📄</span><span class="font-medium">Document Vault</span></a>
            <a href="/applications" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>📊</span><span class="font-medium">Applications</span></a>
            <a href="/nagrik-score" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>🏆</span><span class="font-medium">Nagrik Score</span></a>
            <a href="/hunar" class="flex items-center gap-3 px-3 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition"><span>🔧</span><span class="font-medium">Hunar Directory</span></a>
            <a href="/seva-mitra" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-700 hover:bg-orange-50">🏢 Find Seva Mitra</a>

            <!-- Seva Mitra Section -->
            <div id="offcanvas-csc-section" class="hidden">
                <div class="border-t border-gray-100 pt-2 mt-2">
                    <div class="px-3 py-1 text-xs font-bold text-gray-400 uppercase">Seva Mitra</div>
                    <a href="/csc/dashboard" class="flex items-center gap-3 px-3 py-3 text-green-700 hover:bg-green-50 rounded-lg transition"><span>🏢</span><span class="font-medium">Seva Mitra Dashboard</span></a>
                    <a href="/csc/toolkit" class="flex items-center gap-3 px-3 py-3 text-green-700 hover:bg-green-50 rounded-lg transition"><span>🛠️</span><span class="font-medium">Seva Mitra Toolkit</span></a>
                    <a href="/csc/portal-status" class="flex items-center gap-3 px-3 py-3 text-green-700 hover:bg-green-50 rounded-lg transition"><span>🌐</span><span class="font-medium">Portal Status</span></a>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100">
                <div id="offcanvas-login-btn">
                    <a href="/login" class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-bold transition">Login / Register</a>
                </div>
                <div id="offcanvas-logout-btn" class="hidden">
                    <button onclick="logout()" class="flex items-center justify-center gap-2 w-full border border-red-300 text-red-500 hover:bg-red-50 py-3 rounded-xl font-semibold transition">🚪 Logout</button>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400">© 2026 NagrikSathi</p>
        </div>
    </div>

    <!-- Page Content -->
    <main>{{ $slot }}</main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-0">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex flex-col w-1 h-8 rounded overflow-hidden">
                            <div class="flex-1 bg-orange-500"></div>
                            <div class="flex-1 bg-white"></div>
                            <div class="flex-1 bg-green-600"></div>
                        </div>
                        <span class="font-bold text-lg">NagrikSathi</span>
                    </div>
                    <p class="text-gray-400 text-sm">Your personal government advisor. Har Nagrik Ka Apna Sathi.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-orange-400">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="/schemes" class="hover:text-white transition">All Schemes</a></li>
                        <li><a href="/search" class="hover:text-white transition">Smart Search</a></li>
                        <li><a href="/calculator" class="hover:text-white transition">Benefit Calculator</a></li>
                        <li><a href="/sathi" class="hover:text-white transition">Sathi AI Chat</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-orange-400">Categories</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition">💰 Financial Benefits</a></li>
                        <li><a href="#" class="hover:text-white transition">🏥 Health Services</a></li>
                        <li><a href="#" class="hover:text-white transition">📚 Education</a></li>
                        <li><a href="#" class="hover:text-white transition">🌾 Agriculture</a></li>
                        <li><a href="#" class="hover:text-white transition">🏠 Housing</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-orange-400">Support</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li>📞 1800-XXX-XXXX</li>
                        <li>✉️ help@nagriksathi.com</li>
                        <li>⏰ Mon-Sat 9AM-6PM</li>
                    </ul>
                    <div class="flex gap-3 mt-4">
                        <a href="#" class="bg-gray-700 hover:bg-gray-600 text-white text-xs px-3 py-1 rounded transition">Android App</a>
                        <a href="#" class="bg-gray-700 hover:bg-gray-600 text-white text-xs px-3 py-1 rounded transition">iOS App</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 flex flex-col md:flex-row items-center justify-between text-gray-500 text-xs">
                <span>© 2026 NagrikSathi. Made with ❤️ for every Indian citizen.</span>
                <div class="flex gap-4 mt-2 md:mt-0">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Use</a>
                    <a href="#" class="hover:text-white transition">RTI</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
    // Safe token access
    var token = window.nagrik ? window.nagrik.token : localStorage.getItem('nagrik_token');
    var user  = window.nagrik ? window.nagrik.user  : JSON.parse(localStorage.getItem('nagrik_user') || 'null');

    if (token && user) {
        // Show logged-in nav
        document.getElementById('nav-logged-out').style.display = 'none';
        document.getElementById('nav-logged-in').style.display  = 'flex';

        // Offcanvas
        document.getElementById('offcanvas-user').classList.remove('hidden');
        document.getElementById('offcanvas-login-btn').classList.add('hidden');
        document.getElementById('offcanvas-logout-btn').classList.remove('hidden');
        document.getElementById('offcanvas-phone').textContent = '+91 ' + (user.phone || '');

        // Seva Mitra links — check csc_agent role
        var allRoles = user.all_roles || [];
        if (allRoles.includes('csc_agent') || allRoles.includes('seva_mitra')) {
            var navCsc = document.getElementById('nav-csc-link');
            if (navCsc) navCsc.style.display = 'flex';
            var offCsc = document.getElementById('offcanvas-csc-section');
            if (offCsc) offCsc.classList.remove('hidden');
        }

        // Load profile name
        fetch('/api/v1/profile', {
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
        }).then(function(r) { return r.json(); }).then(function(d) {
            var name = (d.profile && d.profile.name) ? d.profile.name : (user.name || 'Account');
            var el1  = document.getElementById('nav-user-name');
            var el2  = document.getElementById('offcanvas-name');
            if (el1) el1.textContent = name.split(' ')[0];
            if (el2) el2.textContent = name;
        }).catch(function() {
            var el = document.getElementById('nav-user-name');
            if (el) el.textContent = (user.name || 'Account').split(' ')[0];
        });

        // Load score
        fetch('/api/v1/profile/nagrik-score', {
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
        }).then(function(r) { return r.json(); }).then(function(d) {
            var el = document.getElementById('nav-score');
            if (el) el.textContent = d.score || 0;
        }).catch(function() {});
    }

    // Mobile sidebar toggle — global function accessible from all pages
    function toggleOffcanvas() {
        var c = document.getElementById('offcanvas');
        var o = document.getElementById('offcanvas-overlay');
        if (!c || !o) return;
        var isOpen = !c.classList.contains('-translate-x-full');
        if (isOpen) {
            c.classList.add('-translate-x-full');
            o.classList.add('hidden');
        } else {
            c.classList.remove('-translate-x-full');
            o.classList.remove('hidden');
        }
    }

    function logout() {
        fetch('/api/v1/auth/logout', {
            method:  'POST',
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
        }).finally(function() {
            localStorage.removeItem('nagrik_token');
            localStorage.removeItem('nagrik_user');
            window.location.href = '/';
        });
    }

    function toggleDropdown(id) {
    var menu = document.getElementById(id);
    var allMenus = ['more-menu', 'account-menu'];
    allMenus.forEach(function(m) {
        if (m !== id) document.getElementById(m).classList.add('hidden');
    });
    menu.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        document.getElementById('more-menu')?.classList.add('hidden');
        document.getElementById('account-menu')?.classList.add('hidden');
    }
});
</script>
    @livewireScripts
</body>
</html>
