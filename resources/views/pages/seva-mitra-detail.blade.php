<x-app-layout title="Seva Mitra Detail — NagrikSathi">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <a href="/seva-mitra" class="text-orange-500 text-sm hover:underline">← Back to Find Seva Mitra</a>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 mt-4">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">{{ $agent->centre_name ?? 'Seva Mitra Centre' }}</h1>
                    <p class="text-gray-500 text-sm mt-1">📍 {{ implode(', ', array_filter([$agent->block, $agent->district, $agent->state])) }}</p>
                    @if($agent->pincode)
                    <p class="text-gray-400 text-xs mt-0.5">PIN: {{ $agent->pincode }}</p>
                    @endif
                </div>
                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-semibold">✓ Verified</span>
            </div>

            @if($agent->address)
            <div class="bg-gray-50 rounded-xl p-3 mb-4">
                <p class="text-xs text-gray-500 font-medium mb-1">Address</p>
                <p class="text-sm text-gray-700">{{ $agent->address }}</p>
            </div>
            @endif

            @if($agent->services_json)
            <div class="mb-4">
                <p class="text-xs text-gray-500 font-medium mb-2">Services Offered</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($agent->services_json as $service)
                    <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">{{ $service }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="text-center bg-orange-50 rounded-xl p-3">
                    <div class="font-bold text-orange-600">⭐ {{ $agent->rating ?? '0.0' }}</div>
                    <div class="text-xs text-gray-400">Rating</div>
                </div>
                <div class="text-center bg-blue-50 rounded-xl p-3">
                    <div class="font-bold text-blue-600">{{ $agent->tasks_completed ?? 0 }}</div>
                    <div class="text-xs text-gray-400">Tasks Done</div>
                </div>
                <div class="text-center bg-green-50 rounded-xl p-3">
                    <div class="font-bold text-green-600">{{ $agent->customers_served ?? 0 }}</div>
                    <div class="text-xs text-gray-400">Customers</div>
                </div>
            </div>

            <div class="flex gap-3">
                @if($agent->user?->phone)
                <a href="tel:{{ $agent->user->phone }}"
                    class="flex-1 text-center bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition">
                    📞 Call Now
                </a>
                @endif
                <a href="/sathi?q=seva+mitra+help"
                    class="flex-1 text-center bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 rounded-xl transition">
                    💬 Ask Sathi AI
                </a>
            </div>
        </div>
    </div>
</x-app-layout>