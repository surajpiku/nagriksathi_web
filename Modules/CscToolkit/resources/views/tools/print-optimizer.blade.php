<x-app-layout title="Print Layout Optimizer — Seva Mitra Toolkit">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between">
            <div>
                <div class="text-blue-300 text-xs mb-1">Seva Mitra Toolkit — Tool 7</div>
                <h1 class="text-2xl font-bold">🖨️ Print Layout Optimizer</h1>
                <p class="text-blue-200 text-sm">Arrange multiple photos on one A4 sheet — save paper and ink</p>
            </div>
            <a href="/csc/toolkit" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">← Toolkit</a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left — Controls -->
            <div class="space-y-4">

                <!-- Layout Selector -->
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">📐 Select Layout</h3>
                    <div class="space-y-2">
                        @foreach([
                            ['passport_6', '6 Passport Photos', '3×2 grid on A4', '🪪'],
                            ['stamp_4',    '4 Stamp Photos',    '2×2 grid on A4', '📷'],
                            ['halfdoc_2',  '2 Half Documents',  '1×2 on A4',      '📄'],
                            ['fullpage',   'Full Page',         '1 per A4',       '🗒️'],
                        ] as [$val, $label, $desc, $icon])
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-400 transition has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                            <input type="radio" name="layout" value="{{ $val }}" class="accent-orange-500" {{ $val === 'passport_6' ? 'checked' : '' }}>
                            <div>
                                <div class="font-semibold text-gray-800 text-sm">{{ $icon }} {{ $label }}</div>
                                <div class="text-xs text-gray-400">{{ $desc }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Upload Images -->
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">📸 Upload Photos</h3>
                    <label class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-orange-400 transition">
                        <div class="text-3xl mb-2">📁</div>
                        <div class="text-sm font-medium text-gray-600">Click to upload photos</div>
                        <div class="text-xs text-gray-400 mt-1">JPG, PNG — Max 10MB each</div>
                        <input type="file" multiple accept="image/*" class="hidden" onchange="handleImages(this)">
                    </label>
                    <div id="image-count" class="text-xs text-center text-gray-400 mt-2"></div>
                </div>

                <!-- Actions -->
                <button onclick="generateLayout()"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition">
                    🖨️ Generate Print Layout
                </button>
                <button onclick="window.print()"
                    id="print-btn"
                    class="hidden w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 rounded-xl transition">
                    🖨️ Print Now
                </button>
            </div>

            <!-- Right — Preview -->
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800">👁️ Print Preview</h3>
                        <span class="text-xs text-gray-400">A4 — 210×297mm</span>
                    </div>

                    <!-- A4 Preview -->
                    <div id="print-preview"
                        class="border-2 border-gray-300 rounded-lg overflow-hidden"
                        style="width: 100%; aspect-ratio: 210/297; background: white; display: flex; align-items: center; justify-content: center;">
                        <div class="text-center text-gray-300">
                            <div class="text-5xl mb-3">🖨️</div>
                            <div class="text-sm">Upload photos and select layout to preview</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            nav, footer, .no-print { display: none !important; }
            #print-preview { border: none !important; }
            body { margin: 0; padding: 0; }
        }
    </style>

    <script>
        let uploadedImages = [];

        function handleImages(input) {
            uploadedImages = Array.from(input.files);
            document.getElementById('image-count').textContent = uploadedImages.length + ' photos selected';
        }

        function generateLayout() {
            if (uploadedImages.length === 0) {
                alert('Please upload at least one photo');
                return;
            }

            const layout   = document.querySelector('input[name="layout"]:checked').value;
            const preview  = document.getElementById('print-preview');
            const configs  = {
                passport_6: { cols: 3, rows: 2 },
                stamp_4:    { cols: 2, rows: 2 },
                halfdoc_2:  { cols: 1, rows: 2 },
                fullpage:   { cols: 1, rows: 1 },
            };

            const config = configs[layout];
            const total  = config.cols * config.rows;

            preview.style.display    = 'grid';
            preview.style.gridTemplateColumns = `repeat(${config.cols}, 1fr)`;
            preview.style.gap        = '4px';
            preview.style.padding    = '8px';
            preview.innerHTML        = '';

            for (let i = 0; i < total; i++) {
                const img    = document.createElement('img');
                const src    = uploadedImages[i % uploadedImages.length];
                const reader = new FileReader();
                reader.onload = (e) => {
                    img.src = e.target.result;
                    img.style.cssText = 'width:100%;height:100%;object-fit:cover;border:1px solid #ddd;';
                };
                reader.readAsDataURL(src);
                preview.appendChild(img);
            }

            document.getElementById('print-btn').classList.remove('hidden');
        }
    </script>

</x-app-layout>
