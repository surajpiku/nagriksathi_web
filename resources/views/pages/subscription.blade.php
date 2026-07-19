<x-app-layout title="Subscription Plans — NagrikSathi">

    <div class="bg-blue-900 text-white py-10 text-center">
        <h1 class="text-3xl font-bold mb-2">Choose Your Plan</h1>
        <p class="text-blue-200 text-sm">Apna sahi plan chunein — kabhi bhi cancel karein</p>

        <!-- Billing Toggle -->
        <div class="flex items-center justify-center gap-4 mt-6">
            <span id="monthly-label" class="text-sm font-semibold text-white">Monthly</span>
            <button onclick="toggleBilling()" class="relative w-14 h-7 bg-orange-500 rounded-full transition">
                <span id="toggle-dot" class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition-transform duration-300"></span>
            </button>
            <span id="yearly-label" class="text-sm font-semibold text-blue-300">
                Yearly <span class="bg-green-500 text-white text-xs px-2 py-0.5 rounded-full ml-1">Save 15%</span>
            </span>
        </div>
    </div>

    <!-- Citizen Plans -->
    <div class="max-w-5xl mx-auto px-4 py-10">
        <h2 class="text-center font-bold text-gray-800 text-xl mb-6">🏠 Citizen Plans</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12" id="citizen-plans">
            <div class="col-span-3 text-center py-8 text-gray-400 animate-pulse">Loading plans...</div>
        </div>

        <h2 class="text-center font-bold text-gray-800 text-xl mb-6">🏢 Seva Mitra Plans</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="csc-plans">
            <div class="col-span-3 text-center py-8 text-gray-400 animate-pulse">Loading plans...</div>
        </div>
    </div>

    <!-- Comparison Table -->
    <div class="max-w-4xl mx-auto px-4 pb-10">
        <h2 class="text-center font-bold text-gray-800 text-xl mb-6">📊 Full Comparison</h2>
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="text-left px-4 py-3">Feature</th>
                        <th class="text-center px-4 py-3">Free</th>
                        <th class="text-center px-4 py-3 bg-orange-500">Plus ₹99</th>
                        <th class="text-center px-4 py-3">Pro ₹299</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">Scheme Matching</td>
                        <td class="px-4 py-3 text-center text-gray-500">✅ Unlimited</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">✅ Unlimited</td>
                        <td class="px-4 py-3 text-center text-gray-700">✅ Unlimited</td>
                    </tr>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">Sathi AI Messages</td>
                        <td class="px-4 py-3 text-center text-gray-500">20/month</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">200/month</td>
                        <td class="px-4 py-3 text-center text-gray-700">♾️ Unlimited</td>
                    </tr>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">Document Vault</td>
                        <td class="px-4 py-3 text-center text-gray-500">5 docs</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">30 docs</td>
                        <td class="px-4 py-3 text-center text-gray-700">♾️ Unlimited</td>
                    </tr>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">Family Members</td>
                        <td class="px-4 py-3 text-center text-gray-500">2 members</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">5 members</td>
                        <td class="px-4 py-3 text-center text-gray-700">♾️ Unlimited</td>
                    </tr>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">OCR Extraction</td>
                        <td class="px-4 py-3 text-center text-gray-500">2/month</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">15/month</td>
                        <td class="px-4 py-3 text-center text-gray-700">♾️ Unlimited</td>
                    </tr>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">AI Form Filling</td>
                        <td class="px-4 py-3 text-center text-gray-500">❌</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">10/month</td>
                        <td class="px-4 py-3 text-center text-gray-700">♾️ Unlimited</td>
                    </tr>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">Human Sathi Agent</td>
                        <td class="px-4 py-3 text-center text-gray-500">❌</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">2 sessions</td>
                        <td class="px-4 py-3 text-center text-gray-700">♾️ Unlimited</td>
                    </tr>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">Specialist Access</td>
                        <td class="px-4 py-3 text-center text-gray-500">❌</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">❌</td>
                        <td class="px-4 py-3 text-center text-gray-700">✅ Included</td>
                    </tr>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">Alerts & Deadlines</td>
                        <td class="px-4 py-3 text-center text-gray-500">✅ Always Free</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">✅ Always Free</td>
                        <td class="px-4 py-3 text-center text-gray-700">✅ Always Free</td>
                    </tr>
                    <tr class="border-t border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-700">Grievance Filing</td>
                        <td class="px-4 py-3 text-center text-gray-500">✅ Always Free</td>
                        <td class="px-4 py-3 text-center text-orange-600 font-semibold bg-orange-50">✅ Always Free</td>
                        <td class="px-4 py-3 text-center text-gray-700">✅ Always Free</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FAQ -->
    <div class="max-w-3xl mx-auto px-4 pb-12">
        <h2 class="text-center font-bold text-gray-800 text-xl mb-6">❓ Frequently Asked Questions</h2>
        <div class="space-y-3">

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full text-left px-5 py-4 font-semibold text-gray-800 flex items-center justify-between hover:bg-gray-50 transition">
                    <span>Kya main kabhi bhi cancel kar sakta hoon?</span>
                    <span class="text-orange-500 text-xl font-bold faq-icon">+</span>
                </button>
                <div class="hidden px-5 pb-4 text-gray-600 text-sm">
                    Haan! Aap kabhi bhi cancel kar sakte hain. Current period khatam hone tak access milta rahega. Koi hidden charges nahi.
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full text-left px-5 py-4 font-semibold text-gray-800 flex items-center justify-between hover:bg-gray-50 transition">
                    <span>Payment safe hai?</span>
                    <span class="text-orange-500 text-xl font-bold faq-icon">+</span>
                </button>
                <div class="hidden px-5 pb-4 text-gray-600 text-sm">
                    Bilkul! Hum Razorpay use karte hain jo India ka sabse trusted payment gateway hai. UPI, Card, Net Banking sab supported hai.
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full text-left px-5 py-4 font-semibold text-gray-800 flex items-center justify-between hover:bg-gray-50 transition">
                    <span>Refund policy kya hai?</span>
                    <span class="text-orange-500 text-xl font-bold faq-icon">+</span>
                </button>
                <div class="hidden px-5 pb-4 text-gray-600 text-sm">
                    Pehle 24 ghante mein full refund milega. Uske baad refund nahi hoga. Technical error ya double charge mein full refund guaranteed.
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full text-left px-5 py-4 font-semibold text-gray-800 flex items-center justify-between hover:bg-gray-50 transition">
                    <span>Free plan mein kya milta hai?</span>
                    <span class="text-orange-500 text-xl font-bold faq-icon">+</span>
                </button>
                <div class="hidden px-5 pb-4 text-gray-600 text-sm">
                    Free plan mein scheme matching, search aur 20 AI messages per month milte hain. Sab kuch lifetime free!
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full text-left px-5 py-4 font-semibold text-gray-800 flex items-center justify-between hover:bg-gray-50 transition">
                    <span>Annual plan mein kitna bachega?</span>
                    <span class="text-orange-500 text-xl font-bold faq-icon">+</span>
                </button>
                <div class="hidden px-5 pb-4 text-gray-600 text-sm">
                    Plus plan mein Rs.189 aur Pro plan mein Rs.589 per year bachega vs monthly billing.
                </div>
            </div>

        </div>
    </div>

    <!-- Payment Modal -->
    <div id="payment-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 text-lg">Complete Payment</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">x</button>
            </div>
            <div class="text-center">
                <div class="font-bold text-gray-800 text-xl" id="modal-plan-name"></div>
                <div class="text-orange-500 font-bold text-2xl mt-1" id="modal-plan-price"></div>
                <div class="text-gray-400 text-xs mt-1" id="modal-billing"></div>
                <div class="mt-6 space-y-3">
                    <button onclick="initiatePayment()"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition">
                        Pay Now with Razorpay
                    </button>
                    <button onclick="closeModal()"
                        class="w-full border border-gray-300 text-gray-600 py-3 rounded-xl text-sm hover:bg-gray-50 transition">
                        Continue without upgrading
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-3">Secured by Razorpay • Cancel anytime</p>
            </div>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const token      = localStorage.getItem('nagrik_token');
        const user       = JSON.parse(localStorage.getItem('nagrik_user') || 'null');
        let isYearly     = false;
        let selectedPlan = null;
        let allPlans     = [];

        loadPlans();

        async function loadPlans() {
            try {
                const headers = { 'Accept': 'application/json' };
                const [c, a]  = await Promise.all([
                    fetch('/api/v1/plans?type=citizen',   { headers }),
                    fetch('/api/v1/plans?type=seva_mitra', { headers }),
                ]);
                const citizenData = await c.json();
                const cscData     = await a.json();

                allPlans = [...(citizenData.data || []), ...(cscData.data || [])];
                renderPlans(citizenData.data || [], 'citizen-plans');
                renderPlans(cscData.data    || [], 'csc-plans');
            } catch(e) {
                console.error('Plans load error:', e);
            }
        }

        function renderPlans(plans, containerId) {
            const container = document.getElementById(containerId);
            if (!plans.length) {
                container.innerHTML = '<div class="col-span-3 text-center py-8 text-gray-400">No plans available</div>';
                return;
            }

            container.innerHTML = plans.map(plan => {
                const price     = isYearly ? plan.price_yearly : plan.price_monthly;
                const isFree    = price === 0;
                const isPopular = plan.is_popular;
                const yearSave  = isYearly && !isFree ? (plan.price_monthly * 12) - plan.price_yearly : 0;

                return `
                    <div class="relative bg-white border-2 ${isPopular ? 'border-orange-500 shadow-lg' : 'border-gray-200'} rounded-2xl p-6">
                        ${isPopular ? '<div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-orange-500 text-white text-xs font-bold px-4 py-1 rounded-full whitespace-nowrap">Most Popular</div>' : ''}
                        <div class="text-center mb-4">
                            <div class="font-bold text-gray-800 text-lg">${plan.name}</div>
                            <div class="text-sm text-gray-400">${plan.hindi_name || ''}</div>
                            <div class="mt-3">
                                <span class="text-3xl font-bold ${isPopular ? 'text-orange-500' : 'text-blue-900'}">
                                    ${isFree ? 'Free' : '&#8377;' + price}
                                </span>
                                ${!isFree ? '<span class="text-gray-400 text-sm">/' + (isYearly ? 'year' : 'month') + '</span>' : ''}
                            </div>
                            ${yearSave > 0 ? '<div class="text-xs text-green-600 font-semibold mt-1">Save &#8377;' + yearSave + '/year</div>' : ''}
                        </div>
                        <ul class="space-y-2 mb-6">
                            ${(plan.features_json || []).slice(0, 6).map(f =>
                                '<li class="flex items-start gap-2 text-xs text-gray-600"><span class="text-green-500 mt-0.5 flex-shrink-0">✓</span><span>' + f + '</span></li>'
                            ).join('')}
                        </ul>
                        <button onclick="selectPlan(${plan.id}, '${plan.name}', ${price})"
                            class="w-full py-2.5 rounded-xl font-semibold text-sm transition ${isFree
                                ? 'border border-gray-300 text-gray-600 hover:bg-gray-50 cursor-default'
                                : isPopular
                                    ? 'bg-orange-500 hover:bg-orange-600 text-white'
                                    : 'bg-blue-900 hover:bg-blue-800 text-white'}">
                            ${isFree ? 'Current Free Plan' : 'Upgrade Now →'}
                        </button>
                    </div>
                `;
            }).join('');
        }

        function toggleBilling() {
            isYearly = !isYearly;
            document.getElementById('toggle-dot').style.transform = isYearly ? 'translateX(28px)' : 'translateX(0)';
            document.getElementById('monthly-label').className = isYearly ? 'text-sm font-semibold text-blue-300' : 'text-sm font-semibold text-white';
            document.getElementById('yearly-label').className  = isYearly ? 'text-sm font-semibold text-white'    : 'text-sm font-semibold text-blue-300';

            renderPlans(allPlans.filter(p => p.type === 'citizen'),   'citizen-plans');
            renderPlans(allPlans.filter(p => p.type === 'seva_mitra'), 'csc-plans');
        }

        function selectPlan(planId, name, price) {
            if (price === 0) return;
            if (!token) { window.location.href = '/login'; return; }

            selectedPlan = { id: planId, name, price };
            document.getElementById('modal-plan-name').textContent  = name;
            document.getElementById('modal-plan-price').textContent = '&#8377;' + price;
            document.getElementById('modal-billing').textContent    = isYearly ? 'per year' : 'per month';
            document.getElementById('payment-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('payment-modal').classList.add('hidden');
        }

        async function initiatePayment() {
            if (!selectedPlan) return;

            try {
                const res  = await fetch('/api/v1/subscriptions/create-order', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
                    body:    JSON.stringify({ plan_id: selectedPlan.id, billing_cycle: isYearly ? 'yearly' : 'monthly' }),
                });
                const data = await res.json();

                if (!data.success) { alert(data.message); return; }

                closeModal();

                const options = {
                    key:         data.key_id,
                    amount:      data.amount,
                    currency:    data.currency,
                    name:        'NagrikSathi',
                    description: selectedPlan.name + ' Plan',
                    order_id:    data.order_id,
                    prefill: {
                        name:    user?.name    || '',
                        email:   user?.email   || '',
                        contact: user?.phone   ? '91' + user.phone : '',
                    },
                    theme: { color: '#F97316' },
                    handler: async function(response) {
    const verifyRes  = await fetch('/api/v1/subscriptions/verify-payment', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
        body:    JSON.stringify({
            razorpay_order_id:   response.razorpay_order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_signature:  response.razorpay_signature,
        }),
    });
    const verifyData = await verifyRes.json();

    if (verifyData.success) {
        // ✅ Refresh user in localStorage so dashboard shows correct tier
        const meRes  = await fetch('/api/v1/auth/me', {
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
        });
        const meData = await meRes.json();
        if (meData.user) {
            localStorage.setItem('nagrik_user', JSON.stringify(meData.user));
        }

        alert('Payment successful! Your ' + selectedPlan.name + ' plan is now active till ' + verifyData.ends_at);
        window.location.reload();
    } else {
        alert('Payment verification failed. Please contact support.');
    }
},
                };

                const rzp = new Razorpay(options);
                rzp.open();

            } catch(e) {
                alert('Error: ' + e.message);
            }
        }

        function toggleFaq(btn) {
            const content = btn.nextElementSibling;
            const icon    = btn.querySelector('.faq-icon');
            content.classList.toggle('hidden');
            icon.textContent = content.classList.contains('hidden') ? '+' : '−';
        }
    </script>

</x-app-layout>
