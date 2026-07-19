<x-app-layout title="PDF Creator — Seva Mitra Toolkit">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between">
            <div>
                <div class="text-blue-300 text-xs mb-1">Seva Mitra Toolkit — Tool 3</div>
                <h1 class="text-2xl font-bold">📄 Multi-Page PDF Creator</h1>
                <p class="text-blue-200 text-sm">Merge multiple document photos into a single PDF instantly</p>
            </div>
            <a href="/csc/toolkit" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">← Toolkit</a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Upload -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">📸 Upload Document Pages</h3>
                    <label class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-orange-400 transition">
                        <div class="text-4xl mb-3">📁</div>
                        <div class="text-sm font-medium text-gray-600">Click to upload pages</div>
                        <div class="text-xs text-gray-400 mt-1">Upload in order — each image = one PDF page</div>
                        <div class="text-xs text-gray-400">JPG, PNG — Max 10MB each — Up to 20 pages</div>
                        <input type="file" multiple accept="image/*" class="hidden" onchange="handlePages(this)">
                    </label>
                </div>

                <!-- Page Order -->
                <div id="pages-section" class="hidden bg-white border border-gray-200 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-gray-800">📋 Page Order</h3>
                        <span class="text-xs text-gray-400" id="page-count"></span>
                    </div>
                    <div id="pages-grid" class="grid grid-cols-4 gap-3"></div>
                </div>
            </div>

            <!-- Controls -->
            <div class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">⚙️ PDF Settings</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-600 block mb-1">Page Size</label>
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                                <option>A4 (Standard)</option>
                                <option>Letter</option>
                                <option>A3</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 block mb-1">Quality</label>
                            <select id="quality" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                                <option value="high">High (Best for submission)</option>
                                <option value="medium" selected>Medium (Balanced)</option>
                                <option value="low">Low (Smallest size)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-600 block mb-1">Filename</label>
                            <input type="text" id="filename" value="document" placeholder="filename"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="text-xs font-bold text-blue-700 mb-2">💡 Tips</div>
                    <ul class="text-xs text-blue-600 space-y-1">
                        <li>• Upload pages in correct order</li>
                        <li>• Good lighting = better quality</li>
                        <li>• Max 20 pages per PDF</li>
                        <li>• Portal limit: usually 2-5MB</li>
                    </ul>
                </div>

                <button onclick="mergePdf()" id="merge-btn"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition">
                    📄 Create PDF
                </button>

                <div id="result-section" class="hidden bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                    <div class="text-2xl mb-2">✅</div>
                    <div class="font-bold text-green-700 text-sm">PDF Created!</div>
                    <a id="pdf-link" href="#" download
                        class="inline-block mt-2 bg-green-700 text-white text-xs px-4 py-2 rounded-lg hover:bg-green-800 transition">
                        ⬇️ Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
        let pages   = [];

        function handlePages(input) {
            pages = Array.from(input.files);
            document.getElementById('page-count').textContent = pages.length + ' pages';
            document.getElementById('pages-section').classList.remove('hidden');

            const grid = document.getElementById('pages-grid');
            grid.innerHTML = '';

            pages.forEach((file, i) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    grid.innerHTML += `
                        <div class="text-center">
                            <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border border-gray-200 mb-1">
                            <div class="text-xs text-gray-500">Page ${i + 1}</div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            });
        }

        async function mergePdf() {
            if (pages.length === 0) { alert('Please upload pages first'); return; }

            const btn = document.getElementById('merge-btn');
            btn.textContent = 'Creating PDF...';
            btn.disabled    = true;

            const formData = new FormData();
            pages.forEach(p => formData.append('images[]', p));

            try {
                const res  = await fetch('/api/v1/csc/toolkit/pdf/merge', {
                    method:  'POST',
                    headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
                    body:    formData,
                });
                const data = await res.json();

                btn.textContent = 'Create PDF';
                btn.disabled    = false;

                if (data.success) {
                    const link = document.getElementById('pdf-link');
                    link.href  = data.pdf_url;
                    link.download = (document.getElementById('filename').value || 'document') + '.pdf';
                    document.getElementById('result-section').classList.remove('hidden');
                } else {
                    alert('PDF creation failed: ' + data.message);
                }
            } catch(e) {
                btn.textContent = 'Create PDF';
                btn.disabled    = false;
                alert('Network error');
            }
        }
    </script>

</x-app-layout>

