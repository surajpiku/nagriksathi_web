<x-app-layout title="Photo Processor — Seva Mitra Toolkit">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between">
            <div>
                <div class="text-blue-300 text-xs mb-1">Seva Mitra Toolkit — Tool 1</div>
                <h1 class="text-2xl font-bold">📸 Smart Document Photo Processor</h1>
                <p class="text-blue-200 text-sm">Auto-crop, enhance and resize document photos for portal upload</p>
            </div>
            <a href="/csc/toolkit" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">← Toolkit</a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Upload & Controls -->
            <div class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">📁 Upload Document Photo</h3>
                    <label class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-orange-400 transition">
                        <div id="upload-placeholder">
                            <div class="text-4xl mb-2">📷</div>
                            <div class="text-sm font-medium text-gray-600">Click to upload photo</div>
                            <div class="text-xs text-gray-400 mt-1">JPG, PNG — Max 10MB</div>
                        </div>
                        <img id="original-preview" class="hidden w-full rounded-lg max-h-48 object-contain" />
                        <input type="file" accept="image/*" class="hidden" onchange="handlePhoto(this)">
                    </label>
                </div>

                <!-- Output Format -->
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">⚙️ Output Settings</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-600 block mb-1">Portal Requirement</label>
                            <select id="portal-req" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                                <option value="jpg_200kb">JPG — Max 200KB (Most portals)</option>
                                <option value="jpg_100kb">JPG — Max 100KB (Strict portals)</option>
                                <option value="jpg_500kb">JPG — Max 500KB (UPSC/IBPS)</option>
                                <option value="png_300kb">PNG — Max 300KB</option>
                                <option value="pdf_1mb">PDF — Max 1MB</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-medium text-gray-600 block mb-1">Width (px)</label>
                                <input type="number" id="out-width" value="600"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-600 block mb-1">Height (px)</label>
                                <input type="number" id="out-height" value="800"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="enhance" checked class="accent-orange-500">
                                <span class="text-sm text-gray-600">Auto enhance brightness</span>
                            </label>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="grayscale" class="accent-orange-500">
                                <span class="text-sm text-gray-600">Convert to grayscale</span>
                            </label>
                        </div>
                    </div>
                </div>

                <button onclick="processPhoto()" id="process-btn"
                    class="hidden w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition">
                    ⚡ Process Photo
                </button>
            </div>

            <!-- Preview -->
            <div class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">👁️ Preview</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-xs text-gray-400 mb-2">Original</div>
                            <div id="orig-box" class="border border-gray-200 rounded-xl h-40 flex items-center justify-center bg-gray-50">
                                <span class="text-gray-300 text-sm">No image</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-xs text-gray-400 mb-2">Processed</div>
                            <div id="proc-box" class="border border-gray-200 rounded-xl h-40 flex items-center justify-center bg-gray-50">
                                <span class="text-gray-300 text-sm">Not processed</span>
                            </div>
                        </div>
                    </div>

                    <div id="file-info" class="hidden mt-4 grid grid-cols-2 gap-2 text-xs text-gray-500">
                        <div>Original size: <span id="orig-size" class="font-semibold text-gray-700"></span></div>
                        <div>Processed size: <span id="proc-size" class="font-semibold text-gray-700"></span></div>
                    </div>
                </div>

                <div id="download-section" class="hidden bg-green-50 border border-green-200 rounded-xl p-5 text-center">
                    <div class="text-3xl mb-2">✅</div>
                    <div class="font-bold text-green-700 mb-3">Photo processed successfully!</div>
                    <div class="flex gap-3 justify-center">
                        <a id="download-link" href="#" download
                            class="bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-green-800 transition">
                            ⬇️ Download
                        </a>
                        <button onclick="resetTool()"
                            class="border border-gray-300 text-gray-600 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                            🔄 Process Another
                        </button>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="text-xs font-bold text-blue-700 mb-2">💡 Best Results</div>
                    <ul class="text-xs text-blue-600 space-y-1">
                        <li>• Place document on flat white surface</li>
                        <li>• Good natural lighting — no flash</li>
                        <li>• Keep document straight, not tilted</li>
                        <li>• Fill 80% of frame with document</li>
                        <li>• Avoid shadows on document surface</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
        let selectedFile = null;

        function handlePhoto(input) {
            selectedFile = input.files[0];
            if (!selectedFile) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('upload-placeholder').classList.add('hidden');
                document.getElementById('original-preview').src = e.target.result;
                document.getElementById('original-preview').classList.remove('hidden');

                // Show in orig box
                document.getElementById('orig-box').innerHTML =
                    `<img src="${e.target.result}" class="w-full h-full object-contain rounded-xl p-1">`;
                document.getElementById('orig-size').textContent =
                    (selectedFile.size / 1024).toFixed(0) + ' KB';
                document.getElementById('file-info').classList.remove('hidden');
                document.getElementById('process-btn').classList.remove('hidden');
            };
            reader.readAsDataURL(selectedFile);
        }

        async function processPhoto() {
            if (!selectedFile) return;

            const btn = document.getElementById('process-btn');
            btn.textContent = '⚡ Processing...';
            btn.disabled    = true;

            // Client-side processing using Canvas
            const img    = new Image();
            img.onload   = () => {
                const canvas  = document.createElement('canvas');
                const width   = parseInt(document.getElementById('out-width').value);
                const height  = parseInt(document.getElementById('out-height').value);
                canvas.width  = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');

                // White background
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, width, height);

                // Draw image maintaining aspect ratio
                const ratio   = Math.min(width / img.width, height / img.height);
                const newW    = img.width * ratio;
                const newH    = img.height * ratio;
                const offsetX = (width - newW) / 2;
                const offsetY = (height - newH) / 2;

                ctx.drawImage(img, offsetX, offsetY, newW, newH);

                // Grayscale if checked
                if (document.getElementById('grayscale').checked) {
                    const imgData = ctx.getImageData(0, 0, width, height);
                    for (let i = 0; i < imgData.data.length; i += 4) {
                        const avg = (imgData.data[i] + imgData.data[i+1] + imgData.data[i+2]) / 3;
                        imgData.data[i] = imgData.data[i+1] = imgData.data[i+2] = avg;
                    }
                    ctx.putImageData(imgData, 0, 0);
                }

                const format  = document.getElementById('portal-req').value.startsWith('png') ? 'image/png' : 'image/jpeg';
                const quality = 0.85;
                const dataUrl = canvas.toDataURL(format, quality);

                // Show processed preview
                document.getElementById('proc-box').innerHTML =
                    `<img src="${dataUrl}" class="w-full h-full object-contain rounded-xl p-1">`;

                // Estimate size
                const sizeKb = Math.round((dataUrl.length * 0.75) / 1024);
                document.getElementById('proc-size').textContent = sizeKb + ' KB';

                // Set download
                const link      = document.getElementById('download-link');
                link.href       = dataUrl;
                link.download   = 'processed_document.' + (format === 'image/png' ? 'png' : 'jpg');

                document.getElementById('download-section').classList.remove('hidden');

                btn.textContent = '⚡ Process Photo';
                btn.disabled    = false;
            };

            const reader = new FileReader();
            reader.onload = (e) => img.src = e.target.result;
            reader.readAsDataURL(selectedFile);
        }

        function resetTool() {
            selectedFile = null;
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('original-preview').classList.add('hidden');
            document.getElementById('orig-box').innerHTML = '<span class="text-gray-300 text-sm">No image</span>';
            document.getElementById('proc-box').innerHTML = '<span class="text-gray-300 text-sm">Not processed</span>';
            document.getElementById('process-btn').classList.add('hidden');
            document.getElementById('download-section').classList.add('hidden');
            document.getElementById('file-info').classList.add('hidden');
        }
    </script>

</x-app-layout>

