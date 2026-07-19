<x-app-layout title="Seva Mitra Dashboard">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between flex-wrap gap-4">
            <div>
                <div class="text-blue-300 text-sm mb-1">Seva Mitra Panel</div>
                <h1 class="text-2xl font-bold" id="agent-name">Loading...</h1>
                <div class="text-blue-200 text-sm" id="agent-centre"></div>
            </div>
            <div class="flex gap-3">
                <a href="/csc/toolkit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    🛠️ Toolkit
                </a>
                <a href="/csc/portal-status" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    🌐 Portal Status
                </a>
            </div>
        </div>
    </div>

    <!-- Not Seva Mitra Warning -->
    <div id="not-agent" class="hidden max-w-4xl mx-auto px-4 py-8">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
            <div class="text-4xl mb-3">🏢</div>
            <h3 class="font-bold text-gray-800 mb-2">Seva Mitra Access Required</h3>
            <p class="text-gray-500 text-sm mb-4">You need to be a verified Seva Mitra to access this dashboard.</p>
            <a href="/seva-mitra-banen" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-orange-600 transition">
                Apply as Seva Mitra →
            </a>
        </div>
    </div>

    <div id="dashboard-content" class="hidden max-w-7xl mx-auto px-4 py-8">

        <!-- Today Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-3xl font-bold text-blue-700" id="stat-customers">0</div>
                <div class="text-xs text-gray-500 mt-1">Today's Customers</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-3xl font-bold text-green-600" id="stat-done">0</div>
                <div class="text-xs text-gray-500 mt-1">Tasks Done</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-3xl font-bold text-orange-500" id="stat-earnings">₹0</div>
                <div class="text-xs text-gray-500 mt-1">Today's Earnings</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-3xl font-bold text-purple-600" id="stat-rating">0.0</div>
                <div class="text-xs text-gray-500 mt-1">Rating ⭐</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Queue -->
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-gray-800">📋 Customer Queue</h2>
                        <button onclick="showAddCustomer()"
                            class="bg-orange-500 hover:bg-orange-600 text-white text-xs px-4 py-2 rounded-lg font-semibold transition">
                            + Add Customer
                        </button>
                    </div>

                    <!-- Add Customer Form -->
                    <div id="add-customer-form" class="hidden bg-gray-50 rounded-xl p-4 mb-4">
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <input type="text" id="customer-name" placeholder="Customer Name *"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                            <input type="tel" id="customer-phone" placeholder="Phone (optional)"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                        </div>
                        <select id="task-type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400 mb-3">
                            <option value="">Select Task Type *</option>
                            <option>Aadhaar Services</option>
                            <option>PAN Card</option>
                            <option>Passport</option>
                            <option>Income Certificate</option>
                            <option>Caste Certificate</option>
                            <option>Scholarship Form</option>
                            <option>PM-KISAN</option>
                            <option>Ration Card</option>
                            <option>Banking Services</option>
                            <option>Insurance</option>
                            <option>RTI Filing</option>
                            <option>Other</option>
                        </select>
                        <div class="flex gap-2">
                            <button onclick="addCustomer()"
                                class="flex-1 bg-green-700 hover:bg-green-800 text-white py-2 rounded-lg text-sm font-semibold transition">
                                Add to Queue
                            </button>
                            <button onclick="hideAddCustomer()"
                                class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Queue List -->
                    <div id="queue-list">
                        <div class="text-center py-8 text-gray-400">
                            <div class="text-3xl mb-2">📋</div>
                            <div class="text-sm">No customers in queue</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">

                <!-- Monthly Summary -->
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-4">📊 This Month</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Tasks</span>
                            <span class="font-bold text-gray-800" id="month-tasks">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Earnings</span>
                            <span class="font-bold text-green-700" id="month-earnings">₹0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">All Time Tasks</span>
                            <span class="font-bold text-gray-800" id="total-tasks">0</span>
                        </div>
                    </div>
                    <a href="#" class="block mt-4 text-center border border-orange-400 text-orange-500 hover:bg-orange-50 py-2 rounded-lg text-sm font-semibold transition">
                        View Full Report →
                    </a>
                </div>

                <!-- Quick Tools -->
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">⚡ Quick Tools</h3>
                    <div class="space-y-2">
                        <a href="/csc/toolkit" class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <span class="text-xl">🛠️</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Seva Mitra Toolkit</div>
                                <div class="text-xs text-gray-400">Queue, vault, tools</div>
                            </div>
                        </a>
                        <a href="/csc/portal-status" class="flex items-center gap-3 p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <span class="text-xl">🌐</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Portal Status</div>
                                <div class="text-xs text-gray-400">Check portal availability</div>
                            </div>
                        </a>
                        <a href="/schemes" class="flex items-center gap-3 p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                            <span class="text-xl">📋</span>
                            <div>
                                <div class="text-sm font-semibold text-gray-700">Browse Schemes</div>
                                <div class="text-xs text-gray-400">Help customers find schemes</div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
        var user = window.nagrik?.user || JSON.parse(localStorage.getItem('nagrik_user') || 'null');

        if (!token) {
            window.location.href = '/login';
        } else {
            loadDashboard();
        }

        async function loadDashboard() {
            try {
                const res  = await fetch('/api/v1/csc/dashboard', {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                });
                const text = await res.text();
const clean = text.replace(/^\uFEFF/, '');
const data = JSON.parse(clean);

                if (!data.success) {
                    document.getElementById('not-agent').classList.remove('hidden');
                    return;
                }

                document.getElementById('dashboard-content').classList.remove('hidden');

                const agent = data.agent;
                document.getElementById('agent-name').textContent  = user?.name || 'Seva Mitra';
                document.getElementById('agent-centre').textContent = agent.centre_name || agent.district + ', ' + agent.state;
                document.getElementById('stat-customers').textContent = data.today.customers;
                document.getElementById('stat-done').textContent      = data.today.done;
                document.getElementById('stat-earnings').textContent  = '₹' + Number(data.today.earnings).toLocaleString('en-IN');
                document.getElementById('stat-rating').textContent    = data.rating || '0.0';
                document.getElementById('month-tasks').textContent    = data.monthly.tasks;
                document.getElementById('month-earnings').textContent = '₹' + Number(data.monthly.earnings).toLocaleString('en-IN');
                document.getElementById('total-tasks').textContent    = agent.tasks_completed;

                renderQueue(data.queue);

            } catch(e) {
                document.getElementById('not-agent').classList.remove('hidden');
            }
        }

        function renderQueue(queue) {
            const list = document.getElementById('queue-list');
            if (!queue || queue.length === 0) {
                list.innerHTML = `<div class="text-center py-8 text-gray-400">
                    <div class="text-3xl mb-2">✅</div>
                    <div class="text-sm">Queue is empty — all done!</div>
                </div>`;
                return;
            }

            list.innerHTML = queue.map(c => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-900 text-white rounded-full flex items-center justify-center font-bold text-sm">
                            #${c.token_number}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800 text-sm">${c.customer_name}</div>
                            <div class="text-xs text-gray-400">${c.task_type}</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="startTask(${c.id})"
                            class="bg-green-700 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-green-800 transition">
                            ▶ Start
                        </button>
                        <button onclick="cancelTask(${c.id})"
                            class="border border-red-300 text-red-500 text-xs px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                            ✕
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function showAddCustomer() {
            document.getElementById('add-customer-form').classList.remove('hidden');
        }

        function hideAddCustomer() {
            document.getElementById('add-customer-form').classList.add('hidden');
        }

        async function addCustomer() {
            const name = document.getElementById('customer-name').value.trim();
            const task = document.getElementById('task-type').value;

            if (!name || !task) { alert('Please fill name and task type'); return; }

            const res  = await fetch('/api/v1/csc/toolkit/queue', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
                body: JSON.stringify({
                    customer_name:  name,
                    customer_phone: document.getElementById('customer-phone').value || null,
                    task_type:      task,
                })
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('customer-name').value  = '';
                document.getElementById('customer-phone').value = '';
                document.getElementById('task-type').value      = '';
                hideAddCustomer();
                loadDashboard();
            }
        }

        async function startTask(id) {
            await fetch(`/api/v1/csc/toolkit/queue/${id}/start`, {
                method: 'PUT',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
            });

            // Show complete form
            const amount = prompt('Task completed! Enter amount charged (₹):');
            if (amount !== null) {
                const method = confirm('Cash payment?') ? 'cash' : 'upi';
                await fetch(`/api/v1/csc/toolkit/queue/${id}/complete`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
                    body: JSON.stringify({ amount_charged: parseFloat(amount) || 0, payment_method: method })
                });
            }
            loadDashboard();
        }

        async function cancelTask(id) {
            if (!confirm('Cancel this task?')) return;
            await fetch(`/api/v1/csc/toolkit/queue/${id}/cancel`, {
                method: 'PUT',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
            });
            loadDashboard();
        }
    </script>

</x-app-layout>

