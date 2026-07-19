<x-app-layout title="Sathi AI — Your Government Advisor">

    <div class="h-screen flex flex-col" style="height: calc(100vh - 60px)">

        <!-- Chat Header -->
        <div class="bg-blue-900 text-white px-4 py-3 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-lg font-bold">
                    S
                </div>
                <div>
                    <div class="font-bold">Sathi AI</div>
                    <div class="text-xs text-green-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full inline-block"></span>
                        Online — Ready to help
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div id="message-counter" class="bg-white/10 rounded-full px-3 py-1 text-xs">
                    <span id="messages-used">0</span>/<span id="messages-limit">20</span> messages
                </div>
                <a href="/dashboard" class="text-blue-200 hover:text-white text-xs">← Dashboard</a>
            </div>
        </div>

        <!-- Not logged in -->
        <div id="not-logged-in" class="hidden flex-1 flex items-center justify-center bg-gray-50">
            <div class="text-center p-6">
                <div class="text-5xl mb-4">🔐</div>
                <h3 class="font-bold text-gray-800 mb-2">Login to Chat with Sathi</h3>
                <p class="text-gray-500 text-sm mb-4">Sathi AI needs your profile to give personalised guidance</p>
                <a href="/login" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold text-sm">Login Now →</a>
            </div>
        </div>

        <!-- Chat Interface -->
        <div id="chat-interface" class="hidden flex-1 flex flex-col overflow-hidden">

            <!-- Messages Area -->
            <div id="messages-area" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">

                <!-- Welcome Message -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">S</div>
                    <div class="max-w-lg">
                        <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm">
                            <p class="text-gray-800 text-sm">
                                नमस्ते! 🙏 मैं Sathi हूँ — आपका personal government advisor।
                            </p>
                            <p class="text-gray-800 text-sm mt-2">
                                मैं आपको इन चीज़ों में help कर सकता हूँ:
                            </p>
                            <ul class="text-sm text-gray-600 mt-2 space-y-1">
                                <li>✅ Government schemes की जानकारी</li>
                                <li>✅ Application process step-by-step</li>
                                <li>✅ Documents की list</li>
                                <li>✅ Forms भरने में मदद</li>
                                <li>✅ Application status track करना</li>
                            </ul>
                            <p class="text-gray-800 text-sm mt-2">आप Hindi या English में पूछ सकते हैं। बताइए, क्या जानना है? 😊</p>
                        </div>
                        <div class="text-xs text-gray-400 mt-1 ml-2">Sathi AI • Just now</div>
                    </div>
                </div>

                <!-- Quick Questions -->
                <div class="flex flex-wrap gap-2 ml-11">
                    <button onclick="sendQuickMessage('PM Kisan ke liye eligible hoon kya?')"
                        class="bg-white border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-full hover:border-orange-400 hover:text-orange-500 transition">
                        PM Kisan eligibility?
                    </button>
                    <button onclick="sendQuickMessage('Ayushman Bharat card kaise banaye?')"
                        class="bg-white border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-full hover:border-orange-400 hover:text-orange-500 transition">
                        Ayushman Card kaise banaye?
                    </button>
                    <button onclick="sendQuickMessage('Mujhe konsi schemes milti hain?')"
                        class="bg-white border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-full hover:border-orange-400 hover:text-orange-500 transition">
                        Meri schemes kaun si hain?
                    </button>
                    <button onclick="sendQuickMessage('RTI kaise file kare?')"
                        class="bg-white border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-full hover:border-orange-400 hover:text-orange-500 transition">
                        RTI kaise file kare?
                    </button>
                </div>

            </div>

            <!-- Typing Indicator (hidden by default) -->
            <div id="typing-indicator" class="hidden px-4 py-2 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">S</div>
                    <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm">
                        <div class="flex gap-1 items-center">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="bg-white border-t border-gray-200 p-3 flex-shrink-0">
                <div class="flex gap-2 items-end max-w-4xl mx-auto">
                    <div class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl px-4 py-2.5 flex items-end gap-2">
                        <textarea
                            id="message-input"
                            placeholder="Kuch bhi puchiye... (Hindi ya English mein)"
                            rows="1"
                            class="flex-1 bg-transparent outline-none text-sm text-gray-700 resize-none max-h-32"
                            onkeydown="handleKeyDown(event)"
                            oninput="autoResize(this)"></textarea>
                    </div>
                    <button onclick="sendMessage()"
                        id="send-btn"
                        class="w-10 h-10 bg-orange-500 hover:bg-orange-600 text-white rounded-full flex items-center justify-center transition flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
                <div class="text-center mt-2">
                    <span class="text-xs text-gray-400">Sathi AI can make mistakes. Verify important info from official portals.</span>
                </div>
            </div>

        </div>

        <!-- Upgrade Banner (shown when limit reached) -->
        <div id="limit-banner" class="hidden bg-orange-50 border-t border-orange-200 p-3 text-center flex-shrink-0">
            <p class="text-sm text-orange-700 font-medium">
                Monthly message limit reached.
                <a href="/upgrade" class="text-orange-600 font-bold hover:underline">Upgrade to Plus ₹99/month →</a>
            </p>
        </div>

    </div>

    <script>
        var token = window.nagrik?.token || localStorage.getItem('nagrik_token');
        var user = window.nagrik?.user || JSON.parse(localStorage.getItem('nagrik_user') || 'null');
        let messagesUsed = 0;
        let messagesLimit = 20;

        if (!token) {
            document.getElementById('not-logged-in').classList.remove('hidden');
        } else {
            document.getElementById('chat-interface').classList.remove('hidden');
            messagesLimit = user?.subscription_tier === 'pro' ? 999 :
                           user?.subscription_tier === 'plus' ? 200 : 20;
            document.getElementById('messages-limit').textContent = messagesLimit;
        }

        function handleKeyDown(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        }

        function autoResize(el) {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        }

        function sendQuickMessage(text) {
            document.getElementById('message-input').value = text;
            sendMessage();
        }

        function addUserMessage(text) {
            const area = document.getElementById('messages-area');
            const div = document.createElement('div');
            div.className = 'flex justify-end';
            div.innerHTML = `
                <div class="max-w-lg">
                    <div class="bg-orange-500 text-white rounded-2xl rounded-tr-none px-4 py-3 shadow-sm">
                        <p class="text-sm">${text.replace(/\n/g, '<br>')}</p>
                    </div>
                    <div class="text-xs text-gray-400 mt-1 text-right mr-2">You • Just now</div>
                </div>`;
            area.appendChild(div);
            area.scrollTop = area.scrollHeight;
        }

        function addSathiMessage(text) {
            const area = document.getElementById('messages-area');
            const div = document.createElement('div');
            div.className = 'flex items-start gap-3';
            div.innerHTML = `
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">S</div>
                <div class="max-w-lg">
                    <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm">
                        <p class="text-gray-800 text-sm whitespace-pre-wrap">${text}</p>
                    </div>
                    <div class="text-xs text-gray-400 mt-1 ml-2">Sathi AI • Just now</div>
                </div>`;
            area.appendChild(div);
            area.scrollTop = area.scrollHeight;
        }

        async function sendMessage() {
            const input = document.getElementById('message-input');
            const text = input.value.trim();
            if (!text) return;

            if (messagesUsed >= messagesLimit) {
                document.getElementById('limit-banner').classList.remove('hidden');
                return;
            }

            input.value = '';
            input.style.height = 'auto';

            addUserMessage(text);

            // Show typing
            document.getElementById('typing-indicator').classList.remove('hidden');
            document.getElementById('send-btn').disabled = true;

            try {
                const response = await fetch('/api/v1/sathi/message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify({ message: text })
                });

                const data = await response.json();

                document.getElementById('typing-indicator').classList.add('hidden');
                document.getElementById('send-btn').disabled = false;

                if (data.success) {
                    addSathiMessage(data.reply);
                    messagesUsed = data.used;
                    document.getElementById('messages-used').textContent = messagesUsed;

                    if (messagesUsed >= messagesLimit) {
                        document.getElementById('limit-banner').classList.remove('hidden');
                    }
                } else if (response.status === 429) {
                    document.getElementById('limit-banner').classList.remove('hidden');
                } else {
                    addSathiMessage('Maafi chahta hoon, abhi response nahi de pa raha. Thodi der baad try karein. 🙏');
                }
            } catch(e) {
                document.getElementById('typing-indicator').classList.add('hidden');
                document.getElementById('send-btn').disabled = false;
                addSathiMessage('Network error. Please check your connection and try again.');
            }
        }
    </script>

</x-app-layout>
