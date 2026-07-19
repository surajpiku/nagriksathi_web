<x-app-layout title="{{ $scheme->name }}">

    <!-- Breadcrumb -->
    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-sm text-blue-300 mb-2">
                <a href="/" class="hover:text-white">Home</a> →
                <a href="/schemes" class="hover:text-white">Schemes</a> →
                <span>{{ $scheme->name }}</span>
            </div>
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-3xl">{{ $scheme->category->icon }}</span>
                        <span class="bg-orange-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                            {{ $scheme->benefit_type }}
                        </span>
                        @if($scheme->is_central)
                        <span class="bg-blue-700 text-white text-xs px-3 py-1 rounded-full">Central Govt</span>
                        @endif
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-1">{{ $scheme->name }}</h1>
                    <p class="text-blue-200 font-hindi text-lg">{{ $scheme->hindi_name }}</p>
                </div>
                <div class="bg-white/10 rounded-2xl p-4 text-center min-w-40">
                    <div class="text-xs text-blue-200 mb-1">Maximum Benefit</div>
                    <div class="text-3xl font-bold text-orange-400">
                        ₹{{ number_format($scheme->benefit_value) }}
                    </div>
                    <div class="text-xs text-blue-200 mt-1">{{ $scheme->benefit_type }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- About -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-3 flex items-center gap-2">
                        📋 About This Scheme
                    </h2>
                    <p class="text-gray-600 leading-relaxed">{{ $scheme->description }}</p>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-5">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-400 mb-1">Ministry</div>
                            <div class="text-sm font-semibold text-gray-700">{{ $scheme->ministry ?? 'Government of India' }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-400 mb-1">Category</div>
                            <div class="text-sm font-semibold text-gray-700">{{ $scheme->category->name }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-400 mb-1">Scheme Type</div>
                            <div class="text-sm font-semibold text-gray-700">{{ $scheme->is_central ? 'Central Government' : 'State Government' }}</div>
                        </div>
                        @if($scheme->deadline)
                        <div class="bg-red-50 rounded-lg p-3">
                            <div class="text-xs text-red-400 mb-1">⚠️ Last Date</div>
                            <div class="text-sm font-semibold text-red-700">{{ $scheme->deadline->format('d M Y') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Eligibility -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        ✅ Eligibility Criteria
                    </h2>
                    @php $rules = $scheme->eligibility_rules_json ?? []; @endphp
                    @if(count($rules) > 0)
                    <div class="space-y-3">
                        @if(isset($rules['min_age']) || isset($rules['max_age']))
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-green-600 text-xl">🎂</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Age Requirement</div>
                                <div class="text-xs text-gray-500">
                                    @if(isset($rules['min_age']) && isset($rules['max_age']))
                                        Between {{ $rules['min_age'] }} and {{ $rules['max_age'] }} years
                                    @elseif(isset($rules['min_age']))
                                        Minimum {{ $rules['min_age'] }} years
                                    @else
                                        Maximum {{ $rules['max_age'] }} years
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($rules['max_income']))
                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <span class="text-blue-600 text-xl">💰</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Income Limit</div>
                                <div class="text-xs text-gray-500">Annual income below ₹{{ number_format($rules['max_income']) }}</div>
                            </div>
                        </div>
                        @endif

                        @if(isset($rules['bpl_status']) && $rules['bpl_status'])
                        <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <span class="text-yellow-600 text-xl">📄</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">BPL Card Required</div>
                                <div class="text-xs text-gray-500">Must be a Below Poverty Line cardholder</div>
                            </div>
                        </div>
                        @endif

                        @if(isset($rules['gender']))
                        <div class="flex items-center gap-3 p-3 bg-pink-50 rounded-lg border border-pink-100">
                            <span class="text-pink-600 text-xl">👤</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Gender</div>
                                <div class="text-xs text-gray-500 capitalize">Only for {{ $rules['gender'] }}</div>
                            </div>
                        </div>
                        @endif

                        @if(isset($rules['occupation']))
                        <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg border border-purple-100">
                            <span class="text-purple-600 text-xl">💼</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Occupation</div>
                                <div class="text-xs text-gray-500 capitalize">Must be a {{ $rules['occupation'] }}</div>
                            </div>
                        </div>
                        @endif

                        @if(isset($rules['caste_category']))
                        <div class="flex items-center gap-3 p-3 bg-orange-50 rounded-lg border border-orange-100">
                            <span class="text-orange-600 text-xl">🏷️</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Category</div>
                                <div class="text-xs text-gray-500 uppercase">
                                    {{ is_array($rules['caste_category']) ? implode(', ', $rules['caste_category']) : $rules['caste_category'] }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <span class="text-2xl">🌟</span>
                        <p class="text-green-700 font-semibold mt-1">Open for All Citizens</p>
                        <p class="text-green-600 text-xs mt-1">No specific eligibility restrictions</p>
                    </div>
                    @endif
                </div>

                <!-- How to Apply Steps -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                        🗺️ How to Apply — Step by Step
                    </h2>
                    @if($scheme->steps && $scheme->steps->count() > 0)
                    <div class="space-y-4">
                        @foreach($scheme->steps->sortBy('step_number') as $step)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-900 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $step->step_number }}
                            </div>
                            <div class="flex-1 pb-4 border-b border-gray-100 last:border-0">
                                <div class="font-semibold text-gray-800 text-sm mb-1">{{ $step->title }}</div>
                                <p class="text-xs text-gray-500 mb-2">{{ $step->description }}</p>
                                @if($step->link)
                                <a href="{{ $step->link }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-xs text-orange-500 font-semibold hover:underline">
                                    {{ $step->link_label ?? 'Open Link' }} →
                                </a>
                                @endif
                                @if($step->is_online)
                                <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Online</span>
                                @else
                                <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">Offline</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="space-y-3">
                        <div class="flex gap-4">
                            <div class="w-8 h-8 bg-blue-900 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">1</div>
                            <div class="flex-1 pb-3 border-b border-gray-100">
                                <div class="font-semibold text-gray-800 text-sm">Visit the Official Portal</div>
                                <p class="text-xs text-gray-500">Go to the official government portal to start your application</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 bg-blue-900 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">2</div>
                            <div class="flex-1 pb-3 border-b border-gray-100">
                                <div class="font-semibold text-gray-800 text-sm">Register / Login</div>
                                <p class="text-xs text-gray-500">Create an account or login with your Aadhaar / mobile number</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 bg-blue-900 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">3</div>
                            <div class="flex-1 pb-3 border-b border-gray-100">
                                <div class="font-semibold text-gray-800 text-sm">Fill the Application Form</div>
                                <p class="text-xs text-gray-500">Enter your personal, income and family details accurately</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 bg-blue-900 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">4</div>
                            <div class="flex-1 pb-3 border-b border-gray-100">
                                <div class="font-semibold text-gray-800 text-sm">Upload Documents</div>
                                <p class="text-xs text-gray-500">Upload required documents — Aadhaar, income certificate, bank passbook</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 bg-green-700 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">5</div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800 text-sm">Submit & Track</div>
                                <p class="text-xs text-gray-500">Submit and note your application reference number for tracking</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-4">

                <!-- Apply Now Card -->
                <div class="bg-white border-2 border-orange-400 rounded-xl p-5 sticky top-24">
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold text-green-700 mb-1">
                            ₹{{ number_format($scheme->benefit_value) }}
                        </div>
                        <div class="text-xs text-gray-400">Maximum benefit amount</div>
                    </div>

                    @if($scheme->portal_url)
                    <a href="{{ $scheme->portal_url }}" target="_blank"
                       class="block w-full bg-orange-500 hover:bg-orange-600 text-white text-center font-bold py-3 rounded-lg transition mb-3">
                        🚀 Apply on Official Portal
                    </a>
                    @endif

                    @if($scheme->form_url)
                    <a href="{{ $scheme->form_url }}" target="_blank"
                       class="block w-full border border-blue-600 text-blue-600 hover:bg-blue-50 text-center font-semibold py-2.5 rounded-lg transition mb-3 text-sm">
                        📥 Download Application Form
                    </a>
                    @endif

                   <button onclick="goToSathi()"
    class="block w-full border border-green-600 text-green-600 hover:bg-green-50 text-center font-semibold py-2.5 rounded-lg transition text-sm">
    💬 Get Help from Sathi AI
</button>
<!-- Seva Mitra Help -->
<div class="bg-green-50 border border-green-200 rounded-xl p-4 mt-4">
    <h3 class="font-bold text-gray-800 mb-2">🏢 Need Help Applying?</h3>
    <p class="text-xs text-gray-500 mb-3">Find a verified Seva Mitra near you who can help you apply for this scheme.</p>
    <a href="/seva-mitra" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2.5 rounded-lg transition">
        Find Seva Mitra Near You →
    </a>
</div>

                    @if($scheme->helpline)
                    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                        <div class="text-xs text-gray-400 mb-1">Helpline Number</div>
                        <div class="font-bold text-gray-700">📞 {{ $scheme->helpline }}</div>
                    </div>
                    @endif
                </div>

                <!-- Share Card -->
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-700 text-sm mb-3">Share this Scheme</h3>
                    <div class="flex gap-2">
                        <button onclick="navigator.clipboard.writeText(window.location.href)"
                            class="flex-1 border border-gray-300 text-gray-600 text-xs py-2 rounded-lg hover:bg-gray-50 transition">
                            📋 Copy Link
                        </button>
                        <a href="https://wa.me/?text={{ urlencode($scheme->name . ' - ' . url('/schemes/' . $scheme->id)) }}"
                           target="_blank"
                           class="flex-1 bg-green-500 text-white text-xs py-2 rounded-lg hover:bg-green-600 transition text-center">
                            📱 WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Related Info -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <h3 class="font-semibold text-blue-800 text-sm mb-2">💡 Did You Know?</h3>
                    <p class="text-xs text-blue-600">
                        Create your NagrikSathi profile to instantly see all schemes you're eligible for —
                        including this one — with your personalised benefit amount.
                    </p>
                   
<button onclick="checkEligibility()" 
    class="block mt-3 w-full bg-blue-900 text-white text-xs text-center py-2 rounded-lg font-semibold hover:bg-blue-800 transition">
    Check My Eligibility Free →
</button>
                </div>

            </div>
        </div>
    </div>
<script>
   
    var token = window.nagrik ? window.nagrik.token : localStorage.getItem('nagrik_token');

function checkEligibility() {
    if (!token) {
        window.location.href = '/login';
        return;
    }
    window.location.href = '/profile/setup';
}

function goToSathi() {
    if (!token) {
        window.location.href = '/login';
        return;
    }
    window.location.href = '/sathi';
}
    </script>
</x-app-layout>
