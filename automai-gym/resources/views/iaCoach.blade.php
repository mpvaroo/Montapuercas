@extends('layouts.app')

@section('title', 'IA Coach - Chat')

@section('content')
<div class="chat-wrapper">
    <!-- HEADER -->
    <header class="chat-header">
        <div class="chat-header-info">
            <div class="ia-avatar-header">
                <img src="{{ asset('img/ia-coach.jpg') }}" alt="IA Coach">
                <span class="online-dot"></span>
            </div>
            <div class="ia-details">
                <h1>IA Coach</h1>
                <p id="iaStatus">En línea · Tu entrenador personal inteligente</p>
            </div>
        </div>
        <div class="chat-header-actions">
            <select id="convSelect" title="Conversaciones" aria-label="Seleccionar conversación">
                <option value="">+ Nueva conversación</option>
            </select>
            <button id="deleteConvBtn" class="delete-conv-btn" title="Eliminar conversación" aria-label="Eliminar conversación actual" style="display:none;">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                    <path d="M9 3v1H4v2h1l1 14h12l1-14h1V4h-5V3H9zm2 5h2v9h-2V8zm-3 0h2v9H8V8zm6 0h2v9h-2V8z"/>
                </svg>
            </button>
        </div>
    </header>

    <!-- BODY -->
    <div class="chat-body" id="chatBody">
        <div id="loadMoreWrap" style="display:none; text-align:center; padding:8px 0;">
            <button id="loadMoreBtn" class="load-more-btn">↑ Cargar mensajes anteriores</button>
        </div>
        <div id="messagesContainer"></div>

        <div class="message ai-message" id="typingIndicator" style="display:none;">
            <div class="msg-bubble typing">
                <span class="dot"></span><span class="dot"></span><span class="dot"></span>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="chat-footer">
        <form id="chatForm" class="chat-form">
            @csrf
            <div class="input-wrapper">
                <textarea id="chatInput" rows="1" placeholder="Escribe un mensaje al IA Coach..." required></textarea>
            </div>
            <button type="submit" class="send-btn" aria-label="Enviar">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ── Layout principal ─────────────────────────────────────────────── */
    .chat-wrapper {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 40px);
        max-width: 860px;
        margin: 0 auto;
        background: rgba(16, 18, 14, 0.96);
        border: 1px solid rgba(90, 120, 70, 0.25);
        border-radius: 18px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.6);
        overflow: hidden;
    }

    /* ── Header ───────────────────────────────────────────────────────── */
    .chat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        background: rgba(20, 24, 16, 0.99);
        border-bottom: 1px solid rgba(90, 120, 70, 0.2);
        gap: 12px;
        flex-shrink: 0;
    }
    .chat-header-info { display: flex; align-items: center; gap: 13px; }

    .ia-avatar-header {
        position: relative; width: 46px; height: 46px; border-radius: 50%;
        border: 2px solid rgba(120, 180, 80, 0.4); flex-shrink: 0;
    }
    .ia-avatar-header img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
    .online-dot {
        position:absolute; bottom:1px; right:1px; width:10px; height:10px;
        background: #4ade80; border-radius:50%; border: 2px solid #141810;
        box-shadow: 0 0 6px rgba(74,222,128,.8);
    }

    .ia-details h1 {
        margin:0; font-size:17px; font-weight:700; letter-spacing:.01em;
        color: #e8f0e0;
    }
    .ia-details p { margin:2px 0 0; font-size:11px; color: #4ade80; font-weight:500; }

    #convSelect {
        background: rgba(30,35,22,.95); border: 1px solid rgba(90,120,70,.3);
        color: #c8d8b8; padding: 6px 10px; border-radius: 10px; font-size:12px;
        cursor: pointer; max-width: 180px;
    }

    /* ── Body / Mensajes ──────────────────────────────────────────────── */
    .chat-body {
        flex: 1; overflow-y: auto; padding: 20px 20px 10px;
        scroll-behavior: smooth;
    }
    .chat-body::-webkit-scrollbar { width: 5px; }
    .chat-body::-webkit-scrollbar-thumb { background: rgba(90,120,70,.3); border-radius:10px; }

    #messagesContainer {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Mensaje base */
    .message {
        display: flex;
        flex-direction: column;
        max-width: 75%;
        animation: fadeUp .25s ease-out;
    }

    /* IA → izquierda */
    .ai-message {
        align-self: flex-start;
    }
    .ai-message .msg-bubble {
        background: rgba(30, 38, 22, 0.98);
        border: 1px solid rgba(80, 130, 55, 0.3);
        border-radius: 4px 16px 16px 16px;
        color: #dcefd0;
    }
    .ai-message .msg-time { align-self: flex-start; }

    /* Usuario → derecha */
    .user-message {
        align-self: flex-end;
    }
    .user-message .msg-bubble {
        background: rgba(40, 80, 30, 0.95);
        border: 1px solid rgba(80, 160, 60, 0.4);
        border-radius: 16px 4px 16px 16px;
        color: #d8f0cc;
    }
    .user-message .msg-time { align-self: flex-end; }

    /* Burbuja común */
    .msg-bubble {
        padding: 11px 15px;
        font-size: 14px;
        line-height: 1.55;
        word-break: break-word;
        white-space: pre-wrap;
    }

    /* Negrita dentro de mensajes */
    .msg-bubble strong, .msg-bubble b { color: #8fe870; font-weight: 600; }

    .msg-time {
        font-size: 10px;
        color: rgba(180,200,160,.55);
        padding: 3px 4px;
        display: block;
    }

    /* Typing */
    .typing { display:flex; gap:4px; padding: 13px 16px !important; align-items:center; min-width:56px; }
    .typing .dot {
        width:7px; height:7px; background: rgba(100,180,70,.7);
        border-radius:50%; animation: bounce 1.3s infinite ease-in-out;
    }
    .typing .dot:nth-child(1) { animation-delay:0s; }
    .typing .dot:nth-child(2) { animation-delay:.18s; }
    .typing .dot:nth-child(3) { animation-delay:.36s; }

    @keyframes bounce { 0%,100% { transform:translateY(0); opacity:.4; } 50% { transform:translateY(-5px); opacity:1; } }
    @keyframes fadeUp  { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

    /* Load more btn */
    .load-more-btn {
        background: rgba(40,55,28,.8); border: 1px solid rgba(90,120,70,.25);
        color: rgba(180,210,140,.7); padding: 5px 16px; border-radius:20px;
        font-size: 11px; cursor:pointer; transition: background .2s;
    }
    .load-more-btn:hover { background: rgba(60,80,40,.9); }

    /* ── Footer ───────────────────────────────────────────────────────── */
    .chat-footer {
        padding: 12px 16px 14px;
        background: rgba(20, 24, 16, 0.99);
        border-top: 1px solid rgba(90, 120, 70, 0.2);
        flex-shrink: 0;
    }
    .chat-form { display:flex; align-items:flex-end; gap:10px; }

    .input-wrapper {
        flex: 1;
        background: rgba(28, 34, 20, 0.98);
        border: 1px solid rgba(90, 120, 70, 0.3);
        border-radius: 20px; padding: 10px 16px;
        transition: border-color .2s;
    }
    .input-wrapper:focus-within { border-color: rgba(100,180,70,.55); }

    #chatInput {
        width: 100%; background: transparent; border: none;
        color: #d8edcc; font-size: 14px; resize: none;
        outline: none; max-height: 120px; line-height: 1.4;
        font-family: inherit;
    }
    #chatInput::placeholder { color: rgba(160,185,130,.4); }

    .send-btn {
        width: 42px; height: 42px; border-radius: 50%;
        background: linear-gradient(145deg, #3a7a28, #2a5a1a);
        border: 1px solid rgba(80,160,60,.4);
        color: #c8f0b0; cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 3px 12px rgba(60,150,40,.2);
        transition: transform .15s, filter .15s;
    }
    .send-btn:hover { transform: scale(1.08); filter: brightness(1.15); }
    .send-btn:disabled { opacity:.4; cursor:not-allowed; transform:none; }
    .send-btn svg { fill: #c8f0b0; transform: translateX(1px); }

    /* Error bubble */
    .error-bubble {
        align-self: center; text-align: center;
        background: rgba(180,40,40,.12); border: 1px solid rgba(200,60,60,.25);
        color: rgba(255,180,160,.85); padding: 7px 14px; border-radius:10px; font-size:13px;
    }

    /* Botón eliminar conversación */
    .chat-header-actions { display: flex; align-items: center; gap: 8px; }
    .delete-conv-btn {
        width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0;
        background: rgba(180,40,40,.1); border: 1px solid rgba(200,60,60,.25);
        color: rgba(255,140,120,.7); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s, color .15s;
    }
    .delete-conv-btn:hover { background: rgba(200,40,40,.25); color: #ff9090; }
</style>
@endpush

@push('scripts')
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let currentConvId = null, oldestMsgId = null, isLoading = false;

    const chatBody  = document.getElementById('chatBody');
    const msgCont   = document.getElementById('messagesContainer');
    const chatForm  = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const sendBtn   = chatForm.querySelector('.send-btn');
    const typing    = document.getElementById('typingIndicator');
    const iaStatus  = document.getElementById('iaStatus');
    const convSel   = document.getElementById('convSelect');
    const loadMoreWrap = document.getElementById('loadMoreWrap');
    const loadMoreBtn  = document.getElementById('loadMoreBtn');

    // ── Helpers ─────────────────────────────────────────────────────────

    function formatTime(dateStr) {
        if (!dateStr) return 'Ahora';
        return new Date(dateStr).toLocaleTimeString('es-ES', { hour:'2-digit', minute:'2-digit' });
    }

    function markdownToHtml(text) {
        return text
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');
    }

    function appendMessage(rol, contenido, dateStr = null, prepend = false) {
        const isUser = rol === 'user';
        const wrap = document.createElement('div');
        wrap.className = 'message ' + (isUser ? 'user-message' : 'ai-message');
        wrap.innerHTML = `
            <div class="msg-bubble">${markdownToHtml(contenido)}</div>
            <span class="msg-time">${formatTime(dateStr)}</span>
        `;
        if (prepend) msgCont.insertBefore(wrap, msgCont.firstChild);
        else msgCont.appendChild(wrap);
        return wrap;
    }

    function showTyping() {
        chatBody.appendChild(typing);
        typing.style.display = 'flex';
        iaStatus.textContent = 'Escribiendo…';
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    function hideTyping() {
        typing.style.display = 'none';
        iaStatus.textContent = 'En línea · Tu entrenador personal inteligente';
    }
    function showError(txt) {
        const e = document.createElement('div');
        e.className = 'error-bubble';
        e.textContent = txt;
        msgCont.appendChild(e);
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    function setLoading(s) {
        isLoading = s; sendBtn.disabled = s; chatInput.disabled = s;
    }

    // ── Cargar conversaciones ────────────────────────────────────────────

    async function loadConversations() {
        try {
            const r = await fetch('/api/ia/conversations', {
                headers:{'X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},
                credentials:'same-origin'
            });
            if (!r.ok) return;
            const convs = await r.json();
            while (convSel.options.length > 1) convSel.remove(1);
            convs.forEach(c => {
                const o = document.createElement('option');
                o.value = c.id;
                o.textContent = c.titulo || `Conversación #${c.id}`;
                convSel.appendChild(o);
            });
        } catch(e) { console.error(e); }
    }

    // ── Cargar mensajes ─────────────────────────────────────────────────

    async function loadMessages(convId, before = null) {
        const url = `/api/ia/conversations/${convId}/messages` + (before ? `?before=${before}&limit=20` : '?limit=20');
        try {
            const r = await fetch(url, {
                headers:{'X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},
                credentials:'same-origin'
            });
            if (!r.ok) return;
            const data = await r.json();
            if (!before) msgCont.innerHTML = '';
            const prevH = chatBody.scrollHeight;
            data.messages.forEach(m => appendMessage(m.rol, m.contenido, m.created_at, !!before));
            if (data.has_more) {
                oldestMsgId = data.oldest_id;
                loadMoreWrap.style.display = 'block';
            } else {
                loadMoreWrap.style.display = 'none';
                oldestMsgId = null;
            }
            if (!before) chatBody.scrollTop = chatBody.scrollHeight;
            else chatBody.scrollTop = chatBody.scrollHeight - prevH;
        } catch(e) { console.error(e); }
    }

    // ── Enviar ──────────────────────────────────────────────────────────

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text || isLoading) return;

        setLoading(true);
        appendMessage('user', text);
        chatInput.value = ''; chatInput.style.height = 'auto';
        chatBody.scrollTop = chatBody.scrollHeight;
        showTyping();

        try {
            const body = { message: text };
            if (currentConvId) body.conversation_id = currentConvId;

            const res = await fetch('/api/ia/chat', {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'},
                credentials: 'same-origin',
                body: JSON.stringify(body),
            });

            hideTyping();

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                showError(err.message || err.error || 'Error al comunicarse con la IA.');
                setLoading(false);
                return;
            }

            const data = await res.json();
            if (!currentConvId && data.conversation_id) {
                currentConvId = data.conversation_id;
                const o = document.createElement('option');
                o.value = currentConvId; o.textContent = text.substring(0,50); o.selected = true;
                convSel.appendChild(o);
            }
            appendMessage('assistant', data.message);
            chatBody.scrollTop = chatBody.scrollHeight;
        } catch(err) {
            hideTyping();
            showError('Error de conexión. Verifica que Ollama esté activo en localhost:11434.');
            console.error(err);
        }
        setLoading(false);
    });

    // ── Textarea auto-resize ─────────────────────────────────────────────

    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // ── Cambio de conversación ───────────────────────────────────────────

    convSel.addEventListener('change', async function() {
        const id = parseInt(this.value);
        deleteConvBtn.style.display = id ? 'flex' : 'none';
        if (!id) { currentConvId=null; oldestMsgId=null; msgCont.innerHTML=''; loadMoreWrap.style.display='none'; return; }
        currentConvId = id;
        await loadMessages(id);
    });

    // ── Eliminar conversación ────────────────────────────────────────────

    const deleteConvBtn = document.getElementById('deleteConvBtn');
    deleteConvBtn.addEventListener('click', async () => {
        if (!currentConvId) return;
        if (!confirm('¿Eliminar esta conversación y todos sus mensajes? Esta acción no se puede deshacer.')) return;

        try {
            const res = await fetch(`/api/ia/conversations/${currentConvId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) { alert('No se pudo eliminar la conversación.'); return; }

            // Eliminar del select
            const opt = convSel.querySelector(`option[value="${currentConvId}"]`);
            if (opt) opt.remove();

            // Limpiar UI
            currentConvId = null; oldestMsgId = null;
            msgCont.innerHTML = '';
            loadMoreWrap.style.display = 'none';
            deleteConvBtn.style.display = 'none';
            convSel.value = '';

            appendMessage('assistant', '✅ Conversación eliminada. Puedes empezar una nueva.');
        } catch (e) { alert('Error al eliminar. Inténtalo de nuevo.'); console.error(e); }
    });

    loadMoreBtn.addEventListener('click', async () => {
        if (currentConvId && oldestMsgId) await loadMessages(currentConvId, oldestMsgId);
    });

    // ── Init ─────────────────────────────────────────────────────────────

    (async function init() {
        await loadConversations();
        appendMessage('assistant', '¡Hola, {{ Auth::user()->nombre_mostrado_usuario ?? "atleta" }}! 💪 Soy tu IA Coach.\n\nPuedo ayudarte a:\n• Crear rutinas personalizadas\n• Reservar clases en el gimnasio\n• Analizar tu progreso\n\n¿Por dónde empezamos?');
    })();
</script>
@endpush
