<x-app-layout title="Passport Photo Maker — Seva Mitra Toolkit">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between">
            <div>
                <div class="text-blue-300 text-xs mb-1">Seva Mitra Toolkit — Tool 2</div>
                <h1 class="text-2xl font-bold">🖼️ Passport & Stamp Photo Maker</h1>
                <p class="text-blue-200 text-sm">Generate correct size photos for any government exam or ID</p>
            </div>
            <a href="/csc/toolkit" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">← Toolkit</a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Preset Selector -->
            <div class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">📋 Select Preset</h3>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach([
                            ['passport_india',  'Passport (India)',   '35×45mm', 'white', '🛂'],
                            ['aadhaar',         'Aadhaar Card',       '35×45mm', 'white', '🪪'],
                            ['voter_id',        'Voter ID',           '35×45mm', 'white', '🗳️'],
                            ['pan_card',        'PAN Card',           '25×35mm', 'white', '💳'],
                            ['upsc',            'UPSC Exam',          '35×45mm', 'white', '📝'],
                            ['ssc',             'SSC Exam',           '20×25mm', 'white', '📋'],
                            ['sbi_po',          'SBI PO',             '35×45mm', 'white', '🏦'],
                            ['ibps',            'IBPS Exam',          '35×45mm', 'white', '🏦'],
                            ['railway',         'Railway (RRB)',      '35×45mm', 'white', '🚂'],
                            ['driving_license', 'Driving License',   '35×45mm', 'white', '🚗'],
                        ] as [$val, $label, $size, $bg, $icon])
                        <label class="flex items-center justify-between p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-400 transition has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="preset" value="{{ $val }}" class="accent-orange-500" {{ $val === 'passport_india' ? 'checked' : '' }}>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">{{ $icon }} {{ $label }}</div>
                                    <div class="text-xs text-gray-400">{{ $size }} • {{ $bg }} bg</div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Print Count -->
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">🔢 Print Count</h3>
                    <div class="flex items-center gap-3">
                        <button onclick="changeCount(-1)" class="w-10 h-10 border border-gray-300 rounded-lg font-bold text-gray-600 hover:bg-gray-50">-</button>
                        <span id="count-display" class="flex-1 text-center font-bold text-gray-800 text-xl">6</span>
                        <button onclick="changeCount(1)"  class="w-10 h-10 border border-gray-300 rounded-lg font-bold text-gray-600 hover:bg-gray-50">+</button>
                    </div>
                    <div class="text-xs text-center text-gray-400 mt-1">Photos to generate</div>
                </div>
            </div>

            <!-- Upload & Result -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">📸 Upload Photo</h3>
                    <label class="block w-full border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-orange-400 transition">
                        <div id="upload-placeholder">
                            <div class="text-4xl mb-2">🤳</div>
                            <div class="text-sm font-medium text-gray-600">Click to upload face photo</div>
                            <div class="text-xs text-gray-400 mt-1">Clear face, white/light background preferred</div>
                        </div>
                        <img id="photo-preview" class="hidden w-48 h-48 object-cover rounded-xl mx-auto" />
                        <input type="file" accept="image/*" class="hidden" onchange="handlePhoto(this)">
                    </label>

                    <button onclick="generatePhotos()" id="generate-btn"
                        class="hidden w-full mt-4 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition">
                        🖼️ Generate Photos
                    </button>
                </div>

                <!-- Output Grid -->
                <div id="output-section" class="hidden bg-white border border-gray-200 rounded-xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800">✅ Generated Photos</h3>
                        <button onclick="downloadAll()"
                            class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-800 transition">
                            ⬇️ Download All
                        </button>
                    </div>
                    <div id="photos-grid" class="grid grid-cols-3 gap-3"></div>
                    <div class="mt-4 text-center">
                        <button onclick="printPhotos()"
                            class="bg-blue-900 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-blue-800 transition">
                            🖨️ Print Sheet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let photoFile  = null;
        let photoCount = 6;
        let generatedPhotos = [];

        function changeCount(delta) {
            photoCount = Math.max(1, Math.min(12, photoCount + delta));
            document.getElementById('count-display').textContent = photoCount;
        }

        function handlePhoto(input) {
            photoFile = input.files[0];
            if (!photoFile) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('upload-placeholder').classList.add('hidden');
                const prev = document.getElementById('photo-preview');
                prev.src   = e.target.result;
                prev.classList.remove('hidden');
                document.getElementById('generate-btn').classList.remove('hidden');
            };
            reader.readAsDataURL(photoFile);
        }

        async function generatePhotos() {
            if (!photoFile) return;

            const btn    = document.getElementById('generate-btn');
            btn.textContent = 'Generating...';
            btn.disabled    = true;

            const reader = new FileReader();
            reader.onload = (e) => {
                generatedPhotos = [];
                const grid = document.getElementById('photos-grid');
                grid.innerHTML = '';

                for (let i = 0; i < photoCount; i++) {
                    const img = document.createElement('img');
                    img.src   = e.target.result;
                    img.className = 'w-full h-32 object-cover rounded-xl border-2 border-gray-200 cursor-pointer hover:border-orange-400';
                    img.onclick   = () => downloadSingle(e.target.result, i + 1);
                    grid.appendChild(img);
                    generatedPhotos.push(e.target.result);
                }

                document.getElementById('output-section').classList.remove('hidden');
                btn.textContent = '🖼️ Generate Photos';
                btn.disabled    = false;
            };
            reader.readAsDataURL(photoFile);
        }

        function downloadSingle(dataUrl, index) {
            const a    = document.createElement('a');
            a.href     = dataUrl;
            a.download = `photo_${index}.jpg`;
            a.click();
        }

        function downloadAll() {
            generatedPhotos.forEach((photo, i) => {
                setTimeout(() => downloadSingle(photo, i + 1), i * 200);
            });
        }

        function printPhotos() { window.print(); }
    </script>

</x-app-layout>
