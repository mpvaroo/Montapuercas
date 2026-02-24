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
                    <p>En línea · Tu entrenador personal inteligente</p>
                </div>
            </div>
            <div class="chat-header-actions">
                <button class="icon-btn" aria-label="Ajustes de IA"><i class="fas fa-ellipsis-v"></i></button>
            </div>
        </header>

        <!-- BODY -->
        <div class="chat-body" id="chatBody">
            <div class="chat-date"><span>Hoy</span></div>
            
            <!-- IA Message -->
            <div class="message ai-message">
                <div class="msg-content">
                    <div class="msg-bubble">
                        ¡Hola, <b>{{ Auth::user()->nombre_mostrado_usuario ?? 'atleta' }}</b>! Soy tu nuevo IA Coach. He analizado tu perfil y tus objetivos de entrenamiento. ¿En qué te puedo ayudar hoy? ¿Quieres que preparemos la rutina de hoy o revisemos tu progreso semanal?
                    </div>
                    <span class="msg-time">Ahora</span>
                </div>
            </div>

            <!-- User Message Example -->
            <div class="message user-message">
                <div class="msg-content">
                    <div class="msg-bubble">
                        Hola, me gustaría empezar con una rutina de piernas suave de adaptación y ver qué tal respondo.
                    </div>
                    <span class="msg-time">Ahora</span>
                </div>
            </div>

            <!-- IA Message Typing Example (can be toggled via JS) -->
            <div class="message ai-message" id="typingIndicator" style="display: none;">
                <div class="msg-content">
                    <div class="msg-bubble typing">
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER (Input) -->
        <div class="chat-footer">
            <form id="chatForm" class="chat-form">
                <div class="input-wrapper">
                    <textarea id="chatInput" rows="1" placeholder="Escribe un mensaje al IA Coach..." required></textarea>
                </div>
                <button type="submit" class="send-btn" title="Enviar mensaje" aria-label="Enviar">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* VARIABLES LOCALES CHAT */
    :root {
        --chat-bg: rgba(0, 0, 0, 0.2);
        --chat-border: rgba(239, 231, 214, 0.08);
        --msg-ai-bg: radial-gradient(120% 140% at 20% 0%, rgba(190, 145, 85, 0.15), transparent 45%), rgba(0, 0, 0, 0.4);
        --msg-ai-border: rgba(190, 145, 85, 0.25);
        --msg-user-bg: radial-gradient(120% 140% at 20% 0%, rgba(22, 250, 22, 0.12), transparent 45%), rgba(0, 0, 0, 0.5);
        --msg-user-border: rgba(22, 250, 22, 0.25);
        --text-color: rgba(239, 231, 214, 0.9);
        --text-muted: rgba(239, 231, 214, 0.5);
    }

    /* CONTENEDOR PRINCIPAL */
    .chat-wrapper {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 40px);
        max-width: 900px;
        margin: 0 auto;
        background: rgba(12, 12, 12, 0.85);
        border: 1px solid rgba(239, 231, 214, 0.15);
        border-radius: 20px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
        overflow: hidden;
    }

    /* HEADER */
    .chat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: rgba(8, 8, 8, 0.95);
        border-bottom: 1px solid rgba(239, 231, 214, 0.12);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        z-index: 10;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .ia-avatar-header {
        position: relative;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 2px solid rgba(190, 145, 85, 0.4);
        padding: 2px;
        box-shadow: 0 0 10px rgba(190, 145, 85, 0.2);
    }

    .ia-avatar-header img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .online-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        background-color: #16fa16;
        border-radius: 50%;
        border: 2px solid #121110;
        box-shadow: 0 0 8px rgba(22, 250, 22, 0.6);
    }

    .ia-details h1 {
        margin: 0;
        font-family: var(--serif);
        font-size: 20px;
        font-weight: 600;
        color: var(--text-color);
        letter-spacing: 0.02em;
    }

    .ia-details p {
        margin: 2px 0 0;
        font-size: 12px;
        color: #16fa16;
        font-weight: 500;
        letter-spacing: 0.03em;
    }

    .icon-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 18px;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: color 0.2s, background 0.2s;
    }

    .icon-btn:hover {
        color: var(--text-color);
        background: rgba(255, 255, 255, 0.05);
    }

    /* BODY */
    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        scroll-behavior: smooth;
    }

    .chat-body::-webkit-scrollbar {
        width: 6px;
    }
    .chat-body::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-body::-webkit-scrollbar-thumb {
        background: rgba(239, 231, 214, 0.1);
        border-radius: 10px;
    }

    .chat-date {
        text-align: center;
        margin: 10px 0;
        position: relative;
    }

    .chat-date span {
        background: rgba(0, 0, 0, 0.5);
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.05em;
        border: 1px solid var(--chat-border);
    }

    /* MENSAJES PARAMETROS */
    .message {
        display: flex;
        gap: 12px;
        max-width: 85%;
        animation: fadeIn 0.3s ease-out forwards;
    }

    .ai-message {
        align-self: flex-start;
    }

    .user-message {
        align-self: flex-end;
        flex-direction: row;
    }

    .msg-avatar {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        border-radius: 50%;
        overflow: hidden;
        border: 1px solid rgba(239, 231, 214, 0.1);
        background: rgba(0, 0, 0, 0.3);
    }

    .msg-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-initials {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--serif);
        font-size: 14px;
        font-weight: 500;
        color: var(--text-color);
        background: linear-gradient(135deg, rgba(70,98,72,0.8), rgba(0,0,0,0.6));
    }

    .msg-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
        max-width: 100%;
    }

    .user-message .msg-content {
        align-items: flex-end;
    }

    .msg-bubble {
        padding: 12px 16px;
        font-size: 14px;
        line-height: 1.5;
        color: rgba(239, 231, 214, 0.97);
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        word-wrap: break-word;
    }

    .ai-message .msg-bubble {
        background: radial-gradient(140% 150% at 10% 0%, rgba(190, 145, 85, 0.30), transparent 60%), rgba(22, 18, 10, 0.92);
        border: 1px solid rgba(190, 145, 85, 0.30);
        border-radius: 0 16px 16px 16px;
    }

    .user-message .msg-bubble {
        background: radial-gradient(140% 150% at 10% 0%, rgba(22, 250, 22, 0.22), transparent 60%), rgba(5, 22, 5, 0.92);
        border: 1px solid rgba(22, 250, 22, 0.28);
        border-radius: 16px 0 16px 16px;
    }

    .msg-time {
        font-size: 10px;
        color: var(--text-muted);
        padding: 0 4px;
    }

    /* TYPING INDICATOR */
    .typing {
        display: flex;
        gap: 4px;
        padding: 16px !important;
        align-items: center;
    }

    .typing .dot {
        width: 6px;
        height: 6px;
        background: rgba(190, 145, 85, 0.8);
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing .dot:nth-child(1) { animation-delay: 0s; }
    .typing .dot:nth-child(2) { animation-delay: 0.2s; }
    .typing .dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 100% { transform: translateY(0); opacity: 0.5; }
        50% { transform: translateY(-4px); opacity: 1; }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* FOOTER / INPUT */
    .chat-footer {
        padding: 15px 20px;
        background: rgba(8, 8, 8, 0.95);
        border-top: 1px solid rgba(239, 231, 214, 0.12);
        z-index: 10;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .quick-replies {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }

    .quick-replies::-webkit-scrollbar {
        display: none;
    }

    .reply-chip {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--chat-border);
        color: var(--text-color);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s, border-color 0.2s;
    }

    .reply-chip:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(190, 145, 85, 0.4);
    }

    .chat-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }

    .attach-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--chat-border);
        color: var(--text-muted);
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.2s, color 0.2s;
    }

    .attach-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-color);
    }

    .input-wrapper {
        flex: 1;
        background: rgba(25, 25, 25, 0.95);
        border: 1px solid rgba(239, 231, 214, 0.15);
        border-radius: 22px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input-wrapper:focus-within {
        border-color: rgba(190, 145, 85, 0.5);
        box-shadow: 0 0 0 3px rgba(190, 145, 85, 0.12);
    }

    #chatInput {
        width: 100%;
        background: transparent;
        border: none;
        color: var(--text-color);
        font-family: var(--sans);
        font-size: 14px;
        resize: none;
        outline: none;
        max-height: 120px;
        line-height: 1.4;
    }

    #chatInput::placeholder {
        color: rgba(239, 231, 214, 0.3);
    }

    .send-btn {
        width: 52px;
        height: 50px;
        border-radius: 16px;
        background: linear-gradient(160deg, rgba(60, 110, 65, 0.95), rgba(30, 70, 35, 0.98));
        border: 1px solid rgba(22, 250, 22, 0.35);
        color: rgba(239, 231, 214, 0.95);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(22, 250, 22, 0.15), inset 0 1px 0 rgba(255,255,255,0.1);
        transition: transform 0.2s, filter 0.2s, box-shadow 0.2s;
    }
    
    .send-btn svg {
        width: 20px;
        height: 20px;
        fill: rgba(239, 231, 214, 0.95);
        transform: translateX(1px); /* compensar la forma del avión */
    }

    .send-btn:hover {
        transform: translateY(-2px) scale(1.05);
        filter: brightness(1.1);
    }
    
    .send-btn:active {
        transform: translateY(0) scale(0.95);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .chat-wrapper {
            height: calc(100vh - 80px); /* Adjust for mobile bottom-nav if present */
            border-radius: 0;
            border: none;
            margin: -20px -15px 0 -15px; /* Pull to edges if layout has padding */
        }
        .message {
            max-width: 95%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Pasar variables de PHP a JS de forma segura
    const USER_AVATAR_URL = @json(Auth::user()->avatar_url);
    const USER_INITIALS = @json(Auth::user() ? Auth::user()->initials() : 'T');
    const IA_COACH_URL = "{{ asset('img/ia-coach.jpg') }}";

    document.addEventListener('DOMContentLoaded', () => {
        const chatInput = document.getElementById('chatInput');
        const chatBody = document.getElementById('chatBody');
        const chatForm = document.getElementById('chatForm');

        // Scroll al final al cargar
        chatBody.scrollTop = chatBody.scrollHeight;

        // Auto-resize textarea
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            if (this.scrollHeight <= 120) {
                this.style.height = (this.scrollHeight) + 'px';
            } else {
                this.style.height = '120px';
                this.style.overflowY = 'auto';
            }
        });

        // Enter para enviar (Shift+Enter para salto de linea)
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim() !== '') {
                    chatForm.dispatchEvent(new Event('submit'));
                }
            }
        });

        // Generar HTML del avatar del usuario para JS
        function getUserAvatarHTML() {
            if (USER_AVATAR_URL) {
                return `<img src="${USER_AVATAR_URL}" alt="Tú" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
            } else {
                return `<div class="avatar-initials">${USER_INITIALS}</div>`;
            }
        }

        // Envio de mensaje
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = chatInput.value.trim();
            if(!text) return;

            const userMsg = document.createElement('div');
            userMsg.className = 'message user-message';
            userMsg.innerHTML = `
                <div class="msg-content">
                    <div class="msg-bubble">${text.replace(/\n/g, '<br>')}</div>
                    <span class="msg-time">Ahora</span>
                </div>
            `;
            
            chatBody.appendChild(userMsg);
            chatInput.value = '';
            chatInput.style.height = 'auto';
            chatBody.scrollTop = chatBody.scrollHeight;

            // Simular Typing IA
            const typing = document.getElementById('typingIndicator');
            chatBody.appendChild(typing);
            typing.style.display = 'flex';
            chatBody.scrollTop = chatBody.scrollHeight;

            setTimeout(() => {
                typing.style.display = 'none';
                
                const aiMsg = document.createElement('div');
                aiMsg.className = 'message ai-message';
                aiMsg.innerHTML = `
                    <div class="msg-content">
                        <div class="msg-bubble">Entendido. Esta será nuestra primera integración de backend. Enseguida conectaremos esto con un LLM real para responderte.</div>
                        <span class="msg-time">Ahora</span>
                    </div>
                `;
                chatBody.appendChild(aiMsg);
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 1500);
        });
    });
</script>
@endpush
