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

    <!-- Navbar -->
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
        <a href="/" class="flex items-center gap-3">
            <div class="flex flex-col w-1 h-10 rounded overflow-hidden">
                <div class="flex-1 bg-orange-500"></div>
                <div class="flex-1 bg-white border border-gray-200"></div>
                <div class="flex-1 bg-green-700"></div>
            </div>
            <div>
                <div class="font-bold text-xl text-blue-900">NagrikSathi</div>
                <div class="text-xs text-gray-500">Har Nagrik Ka Apna Sathi | हर नागरिक का अपना साथी</div>
            </div>
        </a>
        <div class="hidden md:flex items-center gap-1 text-sm font-medium">
            <a href="/schemes" class="px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded transition">Schemes</a>
            <a href="/search" class="px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded transition">Search</a>
            <a href="/documents" class="px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded transition">Documents</a>
            <a href="/sathi" class="px-4 py-2 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded transition">Sathi AI</a>
            <a href="/login" class="ml-2 bg-orange-500 text-white py-2 px-5 rounded hover:bg-orange-600 transition font-semibold">Login / Register</a>
        </div>
    </div>
</nav>
    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>


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

    @livewireScripts
</body>
</html>