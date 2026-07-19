  
<x-app-layout title="Document Vault">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold mb-1">📄 Document Vault</h1>
                <p class="text-blue-200 text-sm">Store and manage all your important government documents securely</p>
            </div>
            <button onclick="document.getElementById('upload-modal').classList.remove('hidden')"
                class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg font-semibold text-sm transition">
                + Upload Document
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- Not logged in -->
        <div id="not-logged-in" class="hidden bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
            <div class="text-3xl mb-2">🔐</div>
            <h3 class="font-bold text-gray-800 mb-2">Please Login to Access Document Vault</h3>
            <a href="/login" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold text-sm">Login Now →</a>
        </div>

        <div id="vault-content">

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-blue-700" id="total-docs">0</div>
                    <div class="text-xs text-gray-500 mt-1">Total Documents</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-green-600" id="verified-docs">0</div>
                    <div class="text-xs text-gray-500 mt-1">Verified</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-orange-500" id="expiring-docs">0</div>
                    <div class="text-xs text-gray-500 mt-1">Expiring Soon</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-gray-600" id="doc-limit">5</div>
                    <div class="text-xs text-gray-500 mt-1">Storage Limit</div>
                </div>
            </div>

            <!-- Document Types Guide -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                <h3 class="font-semibold text-blue-800 text-sm mb-3">📋 Recommended Documents to Upload</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach([
                        ['Aadhaar Card', '🪪'],
                        ['PAN Card', '💳'],
                        ['Voter ID', '🗳️'],
                        ['Income Certificate', '📜'],
                        ['Caste Certificate', '📋'],
                        ['BPL Card', '🟡'],
                        ['Land Records', '🌾'],
                        ['Bank Passbook', '🏦'],
                    ] as $doc)
                    <div class="bg-white rounded-lg px-3 py-2 text-xs text-gray-600 flex items-center gap-2">
                        <span>{{ $doc[1] }}</span>
                        <span>{{ $doc[0] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Documents Grid -->
            <div id="docs-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="text-center py-16 col-span-3 text-gray-400">
                    <div class="text-5xl mb-3">📂</div>
                    <div class="font-semibold text-gray-600 mb-1">No documents yet</div>
                    <div class="text-sm mb-4">Upload your first document to get started</div>
                    <button onclick="document.getElementById('upload-modal').classList.remove('hidden')"
                        class="bg-orange-500 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600 transition">
                        + Upload Document
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="upload-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Upload Document</h3>
                <button onclick="document.getElementById('upload-modal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Type *</label>
                    <select id="doc-type" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                        <option value="">Select Document Type</option>
                        <option value="aadhaar">Aadhaar Card</option>
                        <option value="pan">PAN Card</option>
                        <option value="voter_id">Voter ID</option>
                        <option value="income_cert">Income Certificate</option>
                        <option value="caste_cert">Caste Certificate</option>
                        <option value="bpl_card">BPL Card</option>
                        <option value="land_record">Land Records</option>
                        <option value="bank_passbook">Bank Passbook</option>
                        <option value="driving_license">Driving License</option>
                        <option value="passport">Passport</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date (if applicable)</label>
                    <input type="date" id="expiry-date"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-orange-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-400 transition cursor-pointer"
                        onclick="document.getElementById('file-input').click()">
                        <div class="text-3xl mb-2">📤</div>
                        <div class="text-sm text-gray-600">Click to upload or drag & drop</div>
                        <div class="text-xs text-gray-400 mt-1">PDF, JPG, PNG — Max 10MB</div>
                        <input type="file" id="file-input" class="hidden" accept=".pdf,.jpg,.jpeg,.png"
                            onchange="fileSelected(this)">
                    </div>
                    <div id="file-name" class="hidden mt-2 text-xs text-green-600 font-medium"></div>
                </div>

                <div id="upload-error" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-xs"></div>

                <button onclick="uploadDocument()" id="upload-btn"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg transition text-sm">
                    Upload Document
                </button>
            </div>
        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
        var user = window.nagrik?.user || JSON.parse(localStorage.getItem('nagrik_user') || 'null');

        if (!token) {
            document.getElementById('not-logged-in').classList.remove('hidden');
            document.getElementById('vault-content').classList.add('hidden');
        } else {
            loadDocuments();
            const limit = user?.subscription_tier === 'pro' ? '∞' : user?.subscription_tier === 'plus' ? '30' : '5';
            document.getElementById('doc-limit').textContent = limit;
        }

        function fileSelected(input) {
            if (input.files[0]) {
                const el = document.getElementById('file-name');
                el.textContent = '✅ ' + input.files[0].name;
                el.classList.remove('hidden');
            }
        }

        async function loadDocuments() {
            try {
                const res = await fetch('/api/v1/documents', {
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
                });
                const data = await res.json();
                const docs = data.data || [];

                document.getElementById('total-docs').textContent = docs.length;
                document.getElementById('verified-docs').textContent = docs.filter(d => d.verified_at).length;
                document.getElementById('expiring-docs').textContent = docs.filter(d => {
                    if (!d.expiry_date) return false;
                    const days = (new Date(d.expiry_date) - new Date()) / (1000 * 60 * 60 * 24);
                    return days <= 90 && days > 0;
                }).length;

                if (docs.length > 0) {
                    document.getElementById('docs-grid').innerHTML = docs.map(doc => `
                        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-xl">📄</div>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium ${doc.verified_at ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}">
                                    ${doc.verified_at ? '✅ Verified' : '⏳ Pending'}
                                </span>
                            </div>
                            <h3 class="font-semibold text-gray-800 text-sm capitalize mb-1">${doc.doc_type.replace('_', ' ')}</h3>
                            <div class="text-xs text-gray-400 mb-3">
                                ${doc.expiry_date ? '⚠️ Expires: ' + new Date(doc.expiry_date).toLocaleDateString('en-IN') : 'No expiry'}
                            </div>
                            <div class="flex gap-2">
                                <a href="${doc.file_url}" target="_blank"
                                   class="flex-1 text-center border border-gray-300 text-gray-600 text-xs py-1.5 rounded-lg hover:bg-gray-50 transition">
                                    View
                                </a>
                                <button class="flex-1 text-center border border-blue-300 text-blue-600 text-xs py-1.5 rounded-lg hover:bg-blue-50 transition">
                                    OCR Extract
                                </button>
                            </div>
                        </div>
                    `).join('');
                }
            } catch(e) {}
        }

        async function uploadDocument() {
            const docType = document.getElementById('doc-type').value;
            const file = document.getElementById('file-input').files[0];
            const errorEl = document.getElementById('upload-error');

            if (!docType) { errorEl.textContent = 'Please select document type'; errorEl.classList.remove('hidden'); return; }
            if (!file) { errorEl.textContent = 'Please select a file'; errorEl.classList.remove('hidden'); return; }

            errorEl.classList.add('hidden');
            const btn = document.getElementById('upload-btn');
            btn.textContent = 'Uploading...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('doc_type', docType);
            formData.append('file', file);
            if (document.getElementById('expiry-date').value) {
                formData.append('expiry_date', document.getElementById('expiry-date').value);
            }

            try {
                const res = await fetch('/api/v1/documents', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    document.getElementById('upload-modal').classList.add('hidden');
                    loadDocuments();
                } else {
                    errorEl.textContent = data.message || 'Upload failed';
                    errorEl.classList.remove('hidden');
                }
            } catch(e) {
                errorEl.textContent = 'Network error. Please try again.';
                errorEl.classList.remove('hidden');
            }

            btn.textContent = 'Upload Document';
            btn.disabled = false;
        }
    </script>

</x-app-layout>
