  
<x-app-layout title="Application Tracker">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-2xl font-bold mb-1">📋 Application Tracker</h1>
            <p class="text-blue-200 text-sm">Track all your scheme applications in one place</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <div id="not-logged-in" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
            <div class="text-3xl mb-2">🔐</div>
            <h3 class="font-bold text-gray-800 mb-2">Please Login to Track Applications</h3>
            <a href="/login" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold text-sm">Login Now →</a>
        </div>

        <div id="apps-content">

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-blue-700" id="total-apps">0</div>
                    <div class="text-xs text-gray-500 mt-1">Total Applications</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-orange-500" id="pending-apps">0</div>
                    <div class="text-xs text-gray-500 mt-1">Pending</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-green-600" id="approved-apps">0</div>
                    <div class="text-xs text-gray-500 mt-1">Approved</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-red-500" id="rejected-apps">0</div>
                    <div class="text-xs text-gray-500 mt-1">Rejected</div>
                </div>
            </div>

            <!-- Applications List -->
            <div id="apps-list" class="space-y-4">
                <div class="text-center py-16 text-gray-400">
                    <div class="text-5xl mb-3">📋</div>
                    <div class="font-semibold text-gray-600 mb-1">No applications yet</div>
                    <div class="text-sm mb-4">Apply for schemes and track them here</div>
                    <a href="/schemes" class="bg-orange-500 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600 transition">
                        Browse Schemes →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');

        if (!token) {
            document.getElementById('not-logged-in').classList.remove('hidden');
            document.getElementById('apps-content').classList.add('hidden');
        } else {
            loadApplications();
        }

        async function loadApplications() {
            try {
                const res = await fetch('/api/v1/applications', {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                });
                const data = await res.json();
                const apps = data.data || [];

                document.getElementById('total-apps').textContent = apps.length;
                document.getElementById('pending-apps').textContent = apps.filter(a => a.status === 'submitted' || a.status === 'processing').length;
                document.getElementById('approved-apps').textContent = apps.filter(a => a.status === 'approved').length;
                document.getElementById('rejected-apps').textContent = apps.filter(a => a.status === 'rejected').length;

                if (apps.length > 0) {
                    document.getElementById('apps-list').innerHTML = apps.map(app => `
                        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition">
                            <div class="flex items-start justify-between flex-wrap gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-bold text-gray-800">${app.scheme?.name || 'Scheme Application'}</h3>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium ${
                                            app.status === 'approved' ? 'bg-green-100 text-green-700' :
                                            app.status === 'rejected' ? 'bg-red-100 text-red-700' :
                                            'bg-orange-100 text-orange-700'
                                        }">
                                            ${app.status.charAt(0).toUpperCase() + app.status.slice(1)}
                                        </span>
                                    </div>
                                    ${app.reference_number ? `<div class="text-xs text-gray-400 mb-2">Ref: ${app.reference_number}</div>` : ''}
                                    <div class="text-xs text-gray-500">
                                        Applied: ${new Date(app.submitted_at || app.created_at).toLocaleDateString('en-IN')}
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="/schemes/${app.scheme_id}"
                                       class="border border-gray-300 text-gray-600 text-xs px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">
                                        View Scheme
                                    </a>
                                    <button onclick="fileGrievance(${app.id})"
                                        class="border border-red-300 text-red-500 text-xs px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                        File Grievance
                                    </button>
                                </div>
                            </div>

                            <!-- Status Timeline -->
                            <div class="mt-4 flex items-center gap-0">
                                ${['Submitted', 'Processing', 'Verified', 'Approved'].map((s, i) => `
                                    <div class="flex items-center flex-1 last:flex-none">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                            ${i === 0 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500'}">
                                            ${i + 1}
                                        </div>
                                        <div class="text-xs text-gray-400 ml-1 hidden md:block">${s}</div>
                                        ${i < 3 ? '<div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>' : ''}
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `).join('');
                }
            } catch(e) {}
        }

        async function fileGrievance(appId) {
            const description = prompt('Describe your grievance:');
            if (!description) return;

            try {
                const res = await fetch(`/api/v1/applications/${appId}/grievance`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify({ description })
                });
                const data = await res.json();
                if (data.success) alert('Grievance filed successfully!');
            } catch(e) {}
        }
    </script>

</x-app-layout>
