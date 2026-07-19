 
<x-app-layout title="{{ $opportunity->name }}">

    <!-- Header -->
    <div class="bg-blue-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-sm text-blue-300 mb-2">
                <a href="/" class="hover:text-white">Home</a> →
                <a href="/awasar" class="hover:text-white">Sarkari Awasar</a> →
                <span>{{ $opportunity->name }}</span>
            </div>
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-3xl">{{ $opportunity->category->icon }}</span>
                        <span class="bg-orange-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                            {{ $opportunity->category->name }}
                        </span>
                        <span class="bg-blue-700 text-white text-xs px-3 py-1 rounded-full">
                            {{ $opportunity->level === 'central' ? 'Central Govt' : 'State Govt' }}
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-1">{{ $opportunity->name }}</h1>
                    <p class="text-blue-200">{{ $opportunity->conducting_body }} — {{ $opportunity->post_name }}</p>
                </div>
                @if($opportunity->vacancy_count)
                <div class="bg-white/10 rounded-2xl p-4 text-center">
                    <div class="text-xs text-blue-200 mb-1">Total Vacancies</div>
                    <div class="text-3xl font-bold text-orange-400">{{ number_format($opportunity->vacancy_count) }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-5">

                <!-- About -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-3">📋 About This Opportunity</h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $opportunity->description }}</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @if($opportunity->vacancy_count)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-400">Vacancies</div>
                            <div class="font-bold text-gray-800">{{ number_format($opportunity->vacancy_count) }}</div>
                        </div>
                        @endif
                        @if($opportunity->salary_range)
                        <div class="bg-green-50 rounded-lg p-3">
                            <div class="text-xs text-gray-400">Salary</div>
                            <div class="font-bold text-green-700 text-sm">{{ $opportunity->salary_range }}</div>
                        </div>
                        @endif
                        @if($opportunity->job_location)
                        <div class="bg-blue-50 rounded-lg p-3">
                            <div class="text-xs text-gray-400">Location</div>
                            <div class="font-bold text-gray-800 text-sm">{{ $opportunity->job_location }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Important Dates -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">📅 Important Dates</h2>
                    <div class="space-y-3">
                        @if($opportunity->apply_start)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Application Start</span>
                            <span class="font-semibold text-green-700 text-sm">{{ $opportunity->apply_start->format('d M Y') }}</span>
                        </div>
                        @endif
                        @if($opportunity->apply_end)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Last Date to Apply</span>
                            <span class="font-bold text-red-600 text-sm">
                                {{ $opportunity->apply_end->format('d M Y') }}
                                @if($opportunity->apply_end->isFuture())
                                <span class="text-xs bg-red-100 px-2 py-0.5 rounded-full ml-1">
                                    {{ $opportunity->apply_end->diffInDays(now()) }} days left
                                </span>
                                @endif
                            </span>
                        </div>
                        @endif
                        @if($opportunity->admit_card_date)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Admit Card</span>
                            <span class="font-semibold text-blue-700 text-sm">{{ $opportunity->admit_card_date->format('d M Y') }}</span>
                        </div>
                        @endif
                        @if($opportunity->exam_date)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Exam Date</span>
                            <span class="font-semibold text-purple-700 text-sm">{{ $opportunity->exam_date->format('d M Y') }}</span>
                        </div>
                        @endif
                        @if($opportunity->result_date)
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-600">Result Date</span>
                            <span class="font-semibold text-gray-700 text-sm">{{ $opportunity->result_date->format('d M Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Eligibility -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">✅ Eligibility Criteria</h2>
                    @php $rules = $opportunity->eligibility_rules_json ?? []; @endphp
                    <div class="space-y-3">
                        @if(isset($rules['min_age']) || isset($rules['max_age']))
                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                            <span class="text-xl">🎂</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Age Limit</div>
                                <div class="text-xs text-gray-500">
                                    @if(isset($rules['min_age']) && isset($rules['max_age']))
                                        {{ $rules['min_age'] }} to {{ $rules['max_age'] }} years
                                    @elseif(isset($rules['min_age']))
                                        Minimum {{ $rules['min_age'] }} years
                                    @else
                                        Maximum {{ $rules['max_age'] }} years
                                    @endif
                                    (Age relaxation for SC/ST/OBC as per govt rules)
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(isset($rules['min_education']))
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                            <span class="text-xl">🎓</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Education</div>
                                <div class="text-xs text-gray-500 capitalize">Minimum {{ $rules['min_education'] }} required</div>
                            </div>
                        </div>
                        @endif
                        @if(isset($rules['gender']))
                        <div class="flex items-center gap-3 p-3 bg-pink-50 rounded-lg">
                            <span class="text-xl">👤</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Gender</div>
                                <div class="text-xs text-gray-500 capitalize">{{ $rules['gender'] }} only</div>
                            </div>
                        </div>
                        @endif
                        @if(isset($rules['max_income']))
                        <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg">
                            <span class="text-xl">💰</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Income Limit</div>
                                <div class="text-xs text-gray-500">Annual income below ₹{{ number_format($rules['max_income']) }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Documents Required -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">📄 Documents Required</h2>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($opportunity->documents_required_json as $doc)
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg text-sm text-gray-700">
                            <span class="text-green-500">✅</span> {{ $doc }}
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- How to Apply -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">🗺️ How to Apply</h2>
                    <div class="space-y-4">
                        @foreach([
                            ['Visit Official Portal', 'Go to the official website to read the full notification'],
                            ['Register / Login', 'Create an account or login with your credentials'],
                            ['Fill Application Form', 'Enter all required details accurately'],
                            ['Upload Documents', 'Upload scanned copies of required documents'],
                            ['Pay Application Fee', 'Pay the fee online (if applicable)'],
                            ['Submit & Save', 'Submit the form and save your application number'],
                        ] as $i => $step)
                        <div class="flex gap-4">
                            <div class="w-7 h-7 bg-blue-900 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
                                {{ $i + 1 }}
                            </div>
                            <div class="flex-1 pb-3 {{ $i < 5 ? 'border-b border-gray-100' : '' }}">
                                <div class="font-semibold text-gray-800 text-sm">{{ $step[0] }}</div>
                                <div class="text-xs text-gray-500">{{ $step[1] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-4">

                <!-- Apply Card -->
                <div class="bg-white border-2 border-orange-400 rounded-xl p-5 sticky top-24">
                    @if($opportunity->apply_end && $opportunity->apply_end->isFuture())
                    <div class="bg-red-50 rounded-lg p-3 text-center mb-4">
                        <div class="text-xs text-red-500">Application Closes In</div>
                        <div class="text-2xl font-bold text-red-600">{{ $opportunity->apply_end->diffInDays(now()) }} Days</div>
                        <div class="text-xs text-red-400">{{ $opportunity->apply_end->format('d M Y') }}</div>
                    </div>
                    @endif

                    @if($opportunity->apply_url)
                    <a href="{{ $opportunity->apply_url }}" target="_blank"
                       class="block w-full bg-orange-500 hover:bg-orange-600 text-white text-center font-bold py-3 rounded-lg transition mb-3">
                        🚀 Apply Now — Official Portal
                    </a>
                    @endif

                    @if($opportunity->notification_url)
                    <a href="{{ $opportunity->notification_url }}" target="_blank"
                       class="block w-full border border-blue-600 text-blue-600 hover:bg-blue-50 text-center font-semibold py-2.5 rounded-lg transition mb-3 text-sm">
                        📥 Download Notification
                    </a>
                    @endif

                    @if($opportunity->syllabus_url)
                    <a href="{{ $opportunity->syllabus_url }}" target="_blank"
                       class="block w-full border border-gray-300 text-gray-600 hover:bg-gray-50 text-center font-semibold py-2.5 rounded-lg transition mb-3 text-sm">
                        📚 View Syllabus
                    </a>
                    @endif

                    <a href="/sathi"
                       class="block w-full border border-green-600 text-green-600 hover:bg-green-50 text-center font-semibold py-2.5 rounded-lg transition text-sm">
                        💬 Ask Sathi for Help
                    </a>

                    @if($opportunity->helpline)
                    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                        <div class="text-xs text-gray-400">Helpline</div>
                        <div class="font-bold text-gray-700 text-sm">📞 {{ $opportunity->helpline }}</div>
                    </div>
                    @endif
                </div>

                <!-- Share -->
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-700 text-sm mb-3">Share this Opportunity</h3>
                    <div class="flex gap-2">
                        <button onclick="navigator.clipboard.writeText(window.location.href)"
                            class="flex-1 border border-gray-300 text-gray-600 text-xs py-2 rounded-lg hover:bg-gray-50 transition">
                            📋 Copy Link
                        </button>
                        <a href="https://wa.me/?text={{ urlencode($opportunity->name . ' — Apply by ' . ($opportunity->apply_end ? $opportunity->apply_end->format('d M Y') : 'N/A') . ' | ' . url('/awasar/' . $opportunity->id)) }}"
                           target="_blank"
                           class="flex-1 bg-green-500 text-white text-xs py-2 rounded-lg hover:bg-green-600 transition text-center">
                            📱 WhatsApp
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>