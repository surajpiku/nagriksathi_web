<x-app-layout title="Har Nagrik Ka Apna Sathi">

   

    <!-- Hero Search Section -->
    <section class="bg-gradient-to-b from-blue-900 to-blue-800 text-white py-14">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="flex justify-center mb-4">
                <img src="https://flagcdn.com/w40/in.png" alt="India" class="w-10 h-7 mx-auto">
            </div>
            <h1 class="text-3xl md:text-5xl font-bold mb-3">
                NagrikSathi
            </h1>
            <p class="text-blue-200 text-lg mb-2">Where Government Benefits Meet Every Citizen</p>
            <p class="text-blue-300 text-sm mb-8 font-hindi">हर नागरिक को उनका हक दिलाना हमारा लक्ष्य है</p>

            <!-- Search Bar -->
            <div class="bg-white rounded-lg shadow-2xl p-2 flex gap-2 max-w-2xl mx-auto mb-6">
                <input type="text"
                    placeholder="Search schemes, documents, services... | योजना खोजें"
                    class="flex-1 px-4 py-3 text-gray-700 outline-none text-sm rounded-l-lg">
                <button class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                    🔍 Search
                </button>
            </div>

            <!-- Trending -->
            <div class="text-sm text-blue-300">
                <span class="mr-2">Trending:</span>
                <span class="inline-flex gap-3 flex-wrap justify-center">
                    <a href="#" class="hover:text-white underline">PM-KISAN</a>
                    <a href="#" class="hover:text-white underline">Ayushman Bharat</a>
                    <a href="#" class="hover:text-white underline">MGNREGA</a>
                    <a href="#" class="hover:text-white underline">PM Awas Yojana</a>
                    <a href="#" class="hover:text-white underline">Scholarships</a>
                </span>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="bg-orange-500 text-white py-4">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="py-2">
                    <div class="text-2xl font-bold">500+</div>
                    <div class="text-orange-100 text-xs">Government Schemes</div>
                </div>
                <div class="py-2">
                    <div class="text-2xl font-bold">₹2.7 Lakh</div>
                    <div class="text-orange-100 text-xs">Avg Benefit Per Family</div>
                </div>
                <div class="py-2">
                    <div class="text-2xl font-bold">22</div>
                    <div class="text-orange-100 text-xs">Indian Languages</div>
                </div>
                <div class="py-2">
                    <div class="text-2xl font-bold">10,000+</div>
                    <div class="text-orange-100 text-xs">Citizens Helped</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Instant Eligibility Check -->
    <section class="py-10 bg-green-50 border-b border-green-100">
        <div class="max-w-5xl mx-auto px-4">
            <div class="bg-white rounded-xl shadow-sm border border-green-200 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-green-700 rounded-full flex items-center justify-center text-white font-bold">⚡</div>
                    <div>
                        <h2 class="font-bold text-lg text-gray-800">Check Your Benefits Instantly</h2>
                        <p class="text-sm text-gray-500">No registration required — see what you're eligible for in 30 seconds</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                    <select class="border border-gray-300 rounded px-3 py-2 text-sm text-gray-600 outline-none focus:border-green-500">
                        <option>Select State</option>
                        <option>Bihar</option>
                        <option>Uttar Pradesh</option>
                        <option>Rajasthan</option>
                        <option>Maharashtra</option>
                        <option>West Bengal</option>
                        <option>Madhya Pradesh</option>
                        <option>Tamil Nadu</option>
                        <option>Karnataka</option>
                    </select>
                    <select class="border border-gray-300 rounded px-3 py-2 text-sm text-gray-600 outline-none focus:border-green-500">
                        <option>Occupation</option>
                        <option>Farmer</option>
                        <option>Student</option>
                        <option>Business Owner</option>
                        <option>Employed</option>
                        <option>Unemployed</option>
                        <option>Self Employed</option>
                    </select>
                    <select class="border border-gray-300 rounded px-3 py-2 text-sm text-gray-600 outline-none focus:border-green-500">
                        <option>Category</option>
                        <option>General</option>
                        <option>OBC</option>
                        <option>SC</option>
                        <option>ST</option>
                        <option>EWS</option>
                    </select>
                    <select class="border border-gray-300 rounded px-3 py-2 text-sm text-gray-600 outline-none focus:border-green-500">
                        <option>Annual Income</option>
                        <option>Below ₹1 Lakh</option>
                        <option>₹1–3 Lakh</option>
                        <option>₹3–6 Lakh</option>
                        <option>Above ₹6 Lakh</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button class="bg-green-700 hover:bg-green-800 text-white px-8 py-2 rounded font-semibold text-sm transition">
                        Check My Eligibility
                    </button>
                    <button class="border border-green-700 text-green-700 hover:bg-green-50 px-6 py-2 rounded font-semibold text-sm transition">
                        Full Profile → More Schemes
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="py-12 max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Browse by Category</h2>
                <p class="text-sm text-gray-500">500+ schemes across 12 categories</p>
            </div>
            <a href="/schemes" class="text-sm text-blue-700 hover:underline font-medium">View All →</a>
        </div>
   <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    @foreach($categories as $category)
    <a href="/schemes?category={{ $category->slug }}"
       class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:border-orange-400 hover:shadow-md transition cursor-pointer group">
        <div class="text-5xl mb-3">{{ $category->icon }}</div>
        <div class="text-sm font-bold text-gray-700 group-hover:text-orange-600 mb-1">{{ $category->name }}</div>
        <div class="text-xs text-gray-400 font-hindi">{{ $category->hindi_name }}</div>
    </a>
    @endforeach
</div>
    </section>

    <!-- Featured Schemes -->
    <section class="py-10 bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Top Government Schemes</h2>
                    <p class="text-sm text-gray-500">Highest benefit value schemes for Indian citizens</p>
                </div>
                <a href="/schemes" class="text-sm text-blue-700 hover:underline font-medium">View All Schemes →</a>
            </div>
           <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($featuredSchemes as $scheme)
    <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md hover:border-orange-300 transition">
        <div class="flex items-start justify-between mb-3">
            <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full font-semibold">
                {{ $scheme->benefit_type }}
            </span>
            <span class="text-green-700 font-bold text-lg">
                ₹{{ number_format($scheme->benefit_value) }}
            </span>
        </div>
        <h3 class="font-bold text-gray-800 text-base mb-1">{{ $scheme->name }}</h3>
        <p class="text-sm text-gray-400 font-hindi mb-2">{{ $scheme->hindi_name }}</p>
        <p class="text-sm text-gray-500 mb-4">{{ Str::limit($scheme->description, 100) }}</p>
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <span class="text-sm text-gray-400 font-medium">{{ $scheme->category->name }}</span>
            <a href="{{ $scheme->portal_url }}" target="_blank"
               class="text-orange-500 text-sm font-bold hover:underline">
                Apply Now →
            </a>
        </div>
    </div>
    @endforeach
</div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-12 max-w-7xl mx-auto px-4">
        <h2 class="text-xl font-bold text-gray-800 mb-1 text-center">How NagrikSathi Works</h2>
        <p class="text-sm text-gray-500 text-center mb-8">3 simple steps to discover and claim your benefits</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-white border border-gray-200 rounded-lg">
                <div class="w-14 h-14 bg-blue-900 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-3">📱</div>
                <div class="text-blue-900 font-bold text-sm mb-1">Step 1</div>
                <h3 class="font-bold text-gray-800 mb-2">Create Your Profile</h3>
                <p class="text-gray-500 text-xs">Enter your basic details — state, income, occupation, family. Takes only 2 minutes.</p>
            </div>
            <div class="text-center p-6 bg-white border border-gray-200 rounded-lg">
                <div class="w-14 h-14 bg-orange-500 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-3">🎯</div>
                <div class="text-orange-500 font-bold text-sm mb-1">Step 2</div>
                <h3 class="font-bold text-gray-800 mb-2">Get Matched Instantly</h3>
                <p class="text-gray-500 text-xs">AI matches you to every scheme you're eligible for with exact benefit amounts.</p>
            </div>
            <div class="text-center p-6 bg-white border border-gray-200 rounded-lg">
                <div class="w-14 h-14 bg-green-700 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-3">✅</div>
                <div class="text-green-700 font-bold text-sm mb-1">Step 3</div>
                <h3 class="font-bold text-gray-800 mb-2">Claim Your Benefits</h3>
                <p class="text-gray-500 text-xs">Follow step-by-step guidance. Sathi AI helps fill forms and track applications.</p>
            </div>
        </div>
    </section>

    <!-- Sathi AI Banner -->
    <section class="bg-blue-900 text-white py-10">
        <div class="max-w-5xl mx-auto px-4 flex flex-col md:flex-row items-center gap-8">
            <div class="flex-1">
                <div class="text-orange-400 font-semibold text-sm mb-2">🤖 AI-Powered Assistant</div>
                <h2 class="text-2xl font-bold mb-3">Meet Sathi — Your Personal Government Advisor</h2>
                <p class="text-blue-200 text-sm mb-4">Ask anything in Hindi or English. Sathi knows every government scheme, form, and process. Available 24/7, completely free.</p>
                <a href="/sathi" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded font-semibold text-sm transition inline-block">
                    Chat with Sathi Free →
                </a>
            </div>
            <div class="bg-blue-800 rounded-xl p-4 w-full md:w-80 text-sm">
                <div class="bg-gray-100 text-gray-800 rounded-lg rounded-tl-none px-3 py-2 mb-2 inline-block">
                    PM Kisan ke liye eligible hoon kya main?
                </div>
                <div class="text-right">
                    <div class="bg-orange-500 text-white rounded-lg rounded-tr-none px-3 py-2 mb-1 inline-block text-left max-w-xs">
                        Haan! Aap PM-KISAN ke liye eligible hain. ₹6,000/year milega. Apply karne ke liye pmkisan.gov.in jaayein →
                    </div>
                </div>
                <div class="bg-gray-100 text-gray-800 rounded-lg rounded-tl-none px-3 py-2 inline-block">
                    Documents kya chahiye?
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
   <section class="bg-gray-800 text-white py-10 text-center">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-3">Start Claiming Your Benefits Today — It's Free</h2>
            <p class="text-green-100 text-sm mb-6">Join thousands of citizens who discovered benefits worth lakhs they never knew about.</p>
            <div class="flex gap-3 justify-center flex-wrap">
                <a href="/register" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded font-bold transition">
                    🚀 Register Free
                </a>
                <a href="/schemes" class="border border-white text-white hover:bg-white hover:text-green-700 px-8 py-3 rounded font-bold transition">
                    Browse Schemes
                </a>
            </div>
        </div>
    </section>

</x-app-layout>