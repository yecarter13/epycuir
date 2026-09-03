<div id="chatbot-container" class="chatbot-container">
    <button id="chatbot-toggle" class="chatbot-toggle" onclick="toggleChatbot()">
        <svg id="chatbot-icon" class="chatbot-toggle-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12c0 1.88.54 3.63 1.48 5.12L2 22l5.12-1.48C8.37 21.46 10.12 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm-2 6h4v2h-4V8zm0 4h4v2h-4v-2z"/>
        </svg>
        <svg id="chatbot-close-icon" class="chatbot-toggle-icon" style="display: none;" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
    </button>

    <div id="chatbot-panel" class="chatbot-panel">
        <div class="chatbot-header">
            <div class="chatbot-header-left">
                <div class="chatbot-avatar">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="chatbot-header-title">Sellerie Super Confort AI</p>
                    <p class="chatbot-header-status">En ligne — posez-moi vos questions</p>
                </div>
            </div>
            <button onclick="toggleChatbot()" class="chatbot-close-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div id="chatbot-messages" class="chatbot-messages">
            <div class="flex items-start gap-2.5 chatbot-msg bot">
                <div class="chatbot-msg-avatar">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="chatbot-msg-bubble bot">
                    <p class="text-sm text-stable-800">🔧 Bonjour ! Décrivez l'équipement recherché. Exemple : <span class="text-safety font-medium">"selle de dressage"</span></p>
                </div>
            </div>
        </div>

        <div id="chatbot-products" class="chatbot-products"></div>

        <div class="chatbot-input-area">
            <form id="chatbot-form" onsubmit="sendChatbotMessage(event)" class="flex gap-2">
                <input id="chatbot-input" type="text" autocomplete="off" placeholder="Décrivez l'équipement que vous cherchez..."
                       class="chatbot-input">
                <button type="submit" id="chatbot-send" class="chatbot-send-btn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .chatbot-container { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; display: flex; flex-direction: column; align-items: flex-end; }
    .chatbot-toggle { width: 56px; height: 56px; background: linear-gradient(135deg, #334e68, #243b53); border-radius: 50%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid rgba(255,255,255,0.2); }
    .chatbot-toggle-icon { width: 1.75rem; height: 1.75rem; color: white; }
    .chatbot-panel { display: none; position: absolute; bottom: 5rem; right: 0; width: 380px; height: 520px; background: white; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid #e5e7eb; overflow: hidden; flex-direction: column; }
    .chatbot-header { background: linear-gradient(90deg, #334e68, #243b53); padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
    .chatbot-header-left { display: flex; align-items: center; gap: 0.75rem; }
    .chatbot-avatar { width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .chatbot-header-title { font-size: 0.875rem; font-weight: 600; color: white; }
    .chatbot-header-status { font-size: 0.625rem; color: rgba(255,255,255,0.7); }
    .chatbot-close-btn { color: rgba(255,255,255,0.7); background: none; border: none; cursor: pointer; padding: 0; }
    .chatbot-close-btn:hover { color: white; }
    .chatbot-messages { flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem; background: #f8f4f0; scroll-behavior: smooth; }
    .chatbot-messages::-webkit-scrollbar { width: 4px; }
    .chatbot-messages::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }
    .chatbot-msg { animation: msg-in 0.25s ease-out; }
    .chatbot-msg-avatar { width: 28px; height: 28px; background: #334e68; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px; }
    .chatbot-msg-bubble.bot { background: white; border-radius: 1rem; border-top-left-radius: 0.25rem; padding: 0.625rem 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; max-width: 85%; }
    .chatbot-msg-bubble.user { background: #334e68; color: white; border-radius: 1rem; border-top-right-radius: 0.25rem; padding: 0.625rem 1rem; max-width: 85%; }
    .chatbot-products { display: none; border-top: 1px solid #e5e7eb; background: white; max-height: 180px; overflow-y: auto; }
    .chatbot-input-area { padding: 0.75rem; border-top: 1px solid #e5e7eb; background: white; flex-shrink: 0; }
    .chatbot-input { flex: 1; padding: 0.625rem 1rem; font-size: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; background: #f8f4f0; }
    .chatbot-input:focus { outline: none; border-color: #334e68; }
    .chatbot-send-btn { padding: 10px 16px; background: #334e68; color: white; border-radius: 0.75rem; border: none; cursor: pointer; transition: all 0.15s; }
    .chatbot-send-btn:hover { background: #243b53; }
    .chatbot-send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    @keyframes msg-in { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .typing-dot { animation: typing-bounce 1.4s infinite ease-in-out both; }
    .typing-dot:nth-child(1) { animation-delay: 0s; }
    .typing-dot:nth-child(2) { animation-delay: 0.16s; }
    .typing-dot:nth-child(3) { animation-delay: 0.32s; }
    @keyframes typing-bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
    @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .chatbot-product-card { transition: all 0.15s ease; }
    .chatbot-product-card:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

    @media (max-width: 639px) {
        .chatbot-container { bottom: 0; right: 0; left: 0; }
        .chatbot-toggle { position: fixed; bottom: 1rem; right: 1rem; }
        .chatbot-panel { position: fixed; bottom: 0; left: 0; right: 0; top: 0; width: 100%; height: 100%; border-radius: 0; border: none; }
        .chatbot-header { padding: 14px 16px; padding-top: calc(14px + env(safe-area-inset-top, 0px)); }
        .chatbot-messages { padding-bottom: env(safe-area-inset-bottom, 0px); }
        .chatbot-input-area { padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px)); }
    }
</style>

<script>
let chatHistory = [];
let isMobileKeyboardOpen = false;

function toggleChatbot() {
    const panel = document.getElementById('chatbot-panel');
    const icon = document.getElementById('chatbot-icon');
    const closeIcon = document.getElementById('chatbot-close-icon');
    const isHidden = panel.style.display === 'none' || panel.style.display === '';
    panel.style.display = isHidden ? 'flex' : 'none';
    icon.style.display = isHidden ? 'none' : 'block';
    closeIcon.style.display = isHidden ? 'block' : 'none';
    if (isHidden) {
        setTimeout(() => document.getElementById('chatbot-input').focus(), 300);
        if (window.innerWidth < 640) {
            document.body.style.overflow = 'hidden';
        }
    } else {
        document.body.style.overflow = '';
    }
}

function addChatMessage(text, isUser) {
    const container = document.getElementById('chatbot-messages');
    const div = document.createElement('div');
    div.className = `flex items-start gap-2.5 chatbot-msg ${isUser ? 'flex-row-reverse' : ''}`;
    if (isUser) {
        div.innerHTML = `
            <div class="chatbot-msg-avatar">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="chatbot-msg-bubble user"><p class="text-sm">${text}</p></div>`;
    } else {
        div.innerHTML = `
            <div class="chatbot-msg-avatar">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="chatbot-msg-bubble bot"><p class="text-sm text-stable-800">${text}</p></div>`;
    }
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function showTyping() {
    const container = document.getElementById('chatbot-messages');
    const div = document.createElement('div');
    div.id = 'chatbot-typing';
    div.className = 'flex items-start gap-2.5 chatbot-msg';
    div.innerHTML = `
        <div class="chatbot-msg-avatar">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="bg-white rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm border border-stable-100 flex items-center gap-1.5">
            <span class="typing-dot w-2 h-2 bg-stable-400 rounded-full inline-block"></span>
            <span class="typing-dot w-2 h-2 bg-stable-400 rounded-full inline-block"></span>
            <span class="typing-dot w-2 h-2 bg-stable-400 rounded-full inline-block"></span>
        </div>`;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

function hideTyping() {
    const el = document.getElementById('chatbot-typing');
    if (el) el.remove();
}

function showProduits(products) {
    const container = document.getElementById('chatbot-products');
    if (!products || products.length === 0) {
        container.style.display = 'none';
        return;
    }
    let html = '<div class="px-3 py-2 text-[11px] font-semibold text-stable-400 uppercase tracking-wider bg-stable-50">Articles correspondants</div>';
    products.forEach(p => {
        html += `<a href="${p.url}" target="_blank" class="flex items-center gap-3 px-3 py-2 hover:bg-stable-50 transition-colors chatbot-product-card border-b border-stable-50 last:border-0">
            <div class="w-10 h-10 bg-stable-100 rounded-lg flex-shrink-0 overflow-hidden">
                <img src="${p.image || 'https://images.unsplash.com/photo-1578643463396-0997cb5328c1?w=48&q=80'}" alt="" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1578643463396-0997cb5328c1?w=48&q=80'">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-stable-900 truncate">${p.name}</p>
                <p class="text-xs text-stable-400">${p.price}</p>
            </div>
            <svg class="w-4 h-4 text-stable-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>`;
    });
    container.innerHTML = html;
    container.style.display = 'block';
}

async function sendChatbotMessage(e) {
    e.preventDefault();
    const input = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send');
    const msg = input.value.trim();
    if (!msg) return;
    input.value = '';
    sendBtn.disabled = true;
    addChatMessage(msg, true);
    chatHistory.push({ role: 'user', content: msg });
    showTyping();
    try {
        const res = await fetch('{{ route("chatbot.message") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: msg, history: chatHistory })
        });
        const data = await res.json();
        hideTyping();
        addChatMessage(data.reply, false);
        chatHistory.push({ role: 'assistant', content: data.reply });
        showProduits(data.products);
    } catch (err) {
        hideTyping();
        addChatMessage('Désolé, une erreur est survenue. Veuillez réessayer.', false);
    } finally {
        sendBtn.disabled = false;
        input.focus();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('chatbot-input');
    if (input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') sendChatbotMessage(e);
        });
        input.addEventListener('focus', function() {
            if (window.innerWidth < 640) {
                setTimeout(() => this.scrollIntoView({ behavior: 'smooth', block: 'center' }), 300);
            }
        });
    }
});
</script>
