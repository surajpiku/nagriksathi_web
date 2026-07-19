<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
</div>
<x-app-layout title="Digital Signature — Seva Mitra Toolkit">

    <div class="bg-blue-900 text-white py-6">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between">
            <div>
                <div class="text-blue-300 text-xs mb-1">Seva Mitra Toolkit — Tool 5</div>
                <h1 class="text-2xl font-bold">✍️ Digital Signature & Stamp</h1>
                <p class="text-blue-200 text-sm">Capture customer signature and add official stamp to documents</p>
            </div>
            <a href="/csc/toolkit" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">← Toolkit</a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Signature Pad -->
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-bold text-gray-800 mb-3">✍️ Customer Signature</h3>
                <div class="border-2 border-gray-300 rounded-xl overflow-hidden">
                    <canvas id="signature-pad" width="400" height="200"
                        class="w-full cursor-crosshair bg-white touch-none"></canvas>
                </div>
                <div class="flex gap-2 mt-3">
                    <button onclick="clearSignature()"
                        class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                        🗑️ Clear
                    </button>
                    <button onclick="saveSignature()"
                        class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg text-sm font-semibold transition">
                        💾 Save Signature
                    </button>
                </div>
                <div id="sig-saved" class="hidden mt-2 text-center text-green-600 text-xs font-medium">
                    ✅ Signature saved successfully!
                </div>
            </div>

            <!-- Stamp Selector -->
            <div class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">🏛️ Select Stamp Type</h3>
                    <div class="space-y-2">
                        @foreach([
                            ['csc_official', '🏢 CSC Official Stamp',     'For official CSC documents'],
                            ['verified',     '✅ Verified by Agent',       'For document verification'],
                            ['attested',     '📋 Attested True Copy',     'For true copy attestation'],
                            ['received',     '📥 Received',                'For receipt acknowledgment'],
                        ] as [$val, $label, $desc])
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="stamp" value="{{ $val }}" class="accent-blue-600">
                            <div>
                                <div class="font-semibold text-gray-800 text-sm">{{ $label }}</div>
                                <div class="text-xs text-gray-400">{{ $desc }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-bold text-gray-800 mb-3">📝 Agent Details (on stamp)</h3>
                    <div class="space-y-3">
                        <input type="text" id="agent-name" placeholder="Agent Name"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                        <input type="text" id="agent-id" placeholder="CSC ID / Agent ID"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                        <input type="date" id="stamp-date"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-orange-400">
                    </div>
                </div>

                <button onclick="generateStamp()"
                    class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 rounded-xl transition">
                    🖨️ Generate Stamp + Signature
                </button>
            </div>
        </div>

        <!-- Generated Output -->
        <div id="output-section" class="hidden mt-6 bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 mb-3">✅ Generated Output</h3>
            <div class="flex gap-4 flex-wrap">
                <div class="border border-gray-200 rounded-xl p-4 text-center">
                    <img id="signature-preview" class="max-w-[200px] max-h-[100px] object-contain" />
                    <div class="text-xs text-gray-500 mt-2">Customer Signature</div>
                </div>
                <div id="stamp-preview" class="border border-gray-200 rounded-xl p-4 flex items-center justify-center min-w-[150px]">
                    <div class="text-center">
                        <div class="text-3xl mb-1">🏢</div>
                        <div class="text-xs font-bold text-gray-700" id="stamp-name-preview">CSC Official</div>
                        <div class="text-xs text-gray-500" id="stamp-agent-preview"></div>
                        <div class="text-xs text-gray-400" id="stamp-date-preview"></div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button onclick="downloadSignature()"
                    class="bg-green-700 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-green-800 transition">
                    ⬇️ Download Signature
                </button>
            </div>
        </div>
    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
        const canvas = document.getElementById('signature-pad');
        const ctx    = canvas.getContext('2d');
        let drawing  = false;
        let signatureData = null;

        ctx.strokeStyle = '#1a1a1a';
        ctx.lineWidth   = 2;
        ctx.lineCap     = 'round';

        canvas.addEventListener('mousedown',  startDraw);
        canvas.addEventListener('mousemove',  draw);
        canvas.addEventListener('mouseup',    stopDraw);
        canvas.addEventListener('mouseleave', stopDraw);
        canvas.addEventListener('touchstart', e => { e.preventDefault(); startDraw(e.touches[0]); });
        canvas.addEventListener('touchmove',  e => { e.preventDefault(); draw(e.touches[0]); });
        canvas.addEventListener('touchend',   stopDraw);

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            return { x: (e.clientX - rect.left) * scaleX, y: (e.clientY - rect.top) * scaleY };
        }

        function startDraw(e) { drawing = true; const p = getPos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }
        function draw(e)      { if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); }
        function stopDraw()   { drawing = false; }

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signatureData = null;
            document.getElementById('sig-saved').classList.add('hidden');
        }

        async function saveSignature() {
            signatureData = canvas.toDataURL('image/png');

            try {
                await fetch('/api/v1/csc/toolkit/signature/add', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + token },
                    body:    JSON.stringify({ signature: signatureData }),
                });
                document.getElementById('sig-saved').classList.remove('hidden');
            } catch(e) {}
        }

        function generateStamp() {
            if (!signatureData) { alert('Please draw customer signature first'); return; }

            const stampType  = document.querySelector('input[name="stamp"]:checked')?.value || 'csc_official';
            const agentName  = document.getElementById('agent-name').value;
            const agentId    = document.getElementById('agent-id').value;
            const stampDate  = document.getElementById('stamp-date').value;

            document.getElementById('signature-preview').src = signatureData;
            document.getElementById('stamp-agent-preview').textContent = agentName + (agentId ? ' | ' + agentId : '');
            document.getElementById('stamp-date-preview').textContent  = stampDate ? new Date(stampDate).toLocaleDateString('en-IN') : '';
            document.getElementById('output-section').classList.remove('hidden');
        }

        function downloadSignature() {
            if (!signatureData) return;
            const a  = document.createElement('a');
            a.href   = signatureData;
            a.download = 'signature_' + Date.now() + '.png';
            a.click();
        }
    </script>

</x-app-layout>

