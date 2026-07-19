<x-app-layout title="OCR Extractor — Seva Mitra Toolkit">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between">
            <div>
                <div class="text-blue-300 text-xs mb-1">Seva Mitra Toolkit — Tool 4</div>
                <h1 class="text-2xl font-bold">🔍 Document OCR Extractor</h1>
                <p class="text-blue-200 text-sm">Extract text and data from document photos automatically</p>
            </div>
            <a href="/csc/toolkit" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">← Toolkit</a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Left — Upload -->
            <div class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">📸 Upload Document Photo</h3>
                    <label id="upload-area" class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-orange-400 transition">
                        <div id="upload-placeholder">
                            <div class="text-4xl mb-3">📄</div>
                            <div class="text-sm font-medium text-gray-600">Click to upload or drag & drop</div>
                            <div class="text-xs text-gray-400 mt-1">Supports: Aadhaar, PAN, Voter ID, Certificates</div>
                            <div class="text-xs text-gray-400">JPG, PNG — Max 10MB</div>
                        </div>
                        <img id="preview-img" class="hidden w-full rounded-lg max-h-64 object-contain" />
                        <input type="file" accept="image/*" class="hidden" onchange="handleUpload(this)">
                    </label>

                    <div class="mt-4 space-y-2">
                        <div class="text-xs font-medium text-gray-500 mb-2">Supported documents:</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['🪪 Aadhaar Card', '💳 PAN Card', '🗳️ Voter ID', '📜 Caste Certificate', '📋 Income Certificate', '📗 Ration Card'] as $doc)
                            <span class="text-xs bg-blue-50 text-blue-700 border border-blue-200 px-2 py-1 rounded-full">{{ $doc }}</span>
                            @endforeach
                        </div>
                    </div>

                    <button onclick="extractText()" id="extract-btn"
                        class="hidden w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition">
                        🔍 Extract Data
                    </button>
                </div>
            </div>

            <!-- Right — Results -->
            <div class="space-y-4">

                <!-- Loading -->
                <div id="loading" class="hidden bg-white border border-gray-200 rounded-xl p-8 text-center">
                    <div class="text-4xl mb-3 animate-pulse">🔍</div>
                    <div class="font-semibold text-gray-700">Extracting text...</div>
                    <div class="text-xs text-gray-400 mt-1">Google Vision AI is reading the document</div>
                </div>

                <!-- Extracted Data -->
                <div id="result-section" class="hidden space-y-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <h3 class="font-bold text-gray-800 mb-4">✅ Extracted Information</h3>
                        <div id="extracted-fields" class="space-y-3"></div>
                        <button onclick="copyToProfile()" class="w-full mt-4 bg-green-700 hover:bg-green-800 text-white font-bold py-2.5 rounded-xl transition text-sm">
                            📋 Copy to Customer Profile
                        </button>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <h3 class="font-bold text-gray-800 mb-3">📝 Raw Text</h3>
                        <textarea id="raw-text" rows="6" readonly
                            class="w-full text-xs text-gray-600 bg-gray-50 border border-gray-200 rounded-lg p-3 font-mono resize-none"></textarea>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="empty-state" class="bg-white border border-gray-200 rounded-xl p-8 text-center">
                    <div class="text-4xl mb-3">🔍</div>
                    <div class="font-semibold text-gray-600">Upload a document to extract data</div>
                    <div class="text-xs text-gray-400 mt-1">AI will automatically detect and extract all text fields</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
        let selectedFile = null;

        function handleUpload(input) {
            selectedFile = input.files[0];
            if (!selectedFile) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('upload-placeholder').classList.add('hidden');
                const img = document.getElementById('preview-img');
                img.src = e.target.result;
                img.classList.remove('hidden');
                document.getElementById('extract-btn').classList.remove('hidden');
            };
            reader.readAsDataURL(selectedFile);
        }

        async function extractText() {
            if (!selectedFile) return;

            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('empty-state').classList.add('hidden');
            document.getElementById('result-section').classList.add('hidden');
            document.getElementById('extract-btn').disabled = true;

            const formData = new FormData();
            formData.append('image', selectedFile);

            try {
                const res  = await fetch('/api/v1/csc/toolkit/ocr/extract', {
                    method:  'POST',
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
                    body:    formData,
                });
                const data = await res.json();

                document.getElementById('loading').classList.add('hidden');
                document.getElementById('extract-btn').disabled = false;

                if (data.success) {
                   renderExtracted(data.structured, data.raw_text);
                } else {
                    alert('Extraction failed: ' + data.message);
                    document.getElementById('empty-state').classList.remove('hidden');
                }
            } catch(e) {
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('extract-btn').disabled = false;
                alert('Network error. Please try again.');
                document.getElementById('empty-state').classList.remove('hidden');
            }
        }

        function renderExtracted(extracted, rawText) {
            const labels = {
                name:        '👤 Name',
                dob:         '🎂 Date of Birth',
                aadhaar:     '🪪 Aadhaar Number',
                pan:         '💳 PAN Number',
                father_name: '👨 Father\'s Name',
                gender:      '⚥ Gender',
                pincode:     '📍 PIN Code',
                phone:       '📞 Phone',
            };

            const fields = document.getElementById('extracted-fields');
            fields.innerHTML = '';

            let count = 0;
            for (const [key, value] of Object.entries(extracted)) {
                if (!value) continue;
                count++;
                fields.innerHTML += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div>
                            <div class="text-xs text-gray-500">${labels[key] || key}</div>
                            <div class="font-semibold text-gray-800 text-sm">${value}</div>
                        </div>
                        <button onclick="copyField('${value}')"
                            class="text-xs text-blue-600 hover:text-blue-800 font-medium">Copy</button>
                    </div>
                `;
            }

            if (count === 0) {
                fields.innerHTML = '<div class="text-center text-gray-400 text-sm py-4">No structured data found. Check raw text below.</div>';
            }

            document.getElementById('raw-text').value = rawText;
            document.getElementById('result-section').classList.remove('hidden');
        }

        function copyField(value) {
            navigator.clipboard.writeText(value);
        }

        function copyToProfile() {
            alert('Feature coming soon — will auto-fill customer profile form!');
        }
    </script>

</x-app-layout>

