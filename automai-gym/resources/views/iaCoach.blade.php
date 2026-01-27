@extends('layouts.app')

@section('title', 'IA Coach')

@section('content')
    <div class="main">
        <header class="topbar">
            <div class="hero">
                <h1>IA Coach</h1>
                <p>Tu asistente inteligente de entrenamiento.</p>
            </div>
            <div class="top-actions">
                <button class="icon-btn" aria-label="Notificaciones">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
                    </svg>
                    <span class="notif-dot"></span>
                </button>
                <div class="user-pill">
                    <div class="mini-avatar"><img src="{{ asset('img/user.png') }}" alt=""></div>
                    <span>Marcelo</span>
                </div>
            </div>
        </header>

        <div class="chat-surface">
            <header class="chat-header">
                <div class="coach-badge">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M12 2a7 7 0 0 0-7 7c0 2.6 1.4 4.9 3.5 6.1V22l3-1.6 3 1.6v-6.9C17.6 13.9 19 11.6 19 9a7 7 0 0 0-7-7Zm0 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z" />
                    </svg>
                </div>
                <div class="chat-title">
                    <strong>AutomAI Assistant</strong>
                    <span>En línea · Listo para optimizar tu rutina</span>
                </div>
            </header>

            <div class="chat-body" id="chatContainer">
                <div class="day-sep">Ayer</div>

                <div class="msg assistant-msg">
                    <div class="who">
                        <div class="coach-badge" style="width:34px; height:34px; border-radius:10px;">
                            <svg viewBox="0 0 24 24" style="width:16px; height:16px;">
                                <path
                                    d="M12 2a7 7 0 0 0-7 7c0 2.6 1.4 4.9 3.5 6.1V22l3-1.6 3 1.6v-6.9C17.6 13.9 19 11.6 19 9a7 7 0 0 0-7-7Zm0 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="bubble">
                        <p>¡Hola Marcelo! He analizado tu rendimiento en la sesión de Piernas de ayer. Noté que tu ritmo
                            cardíaco subió un 15% más de lo habitual en la última serie de sentadillas.</p>
                        <p>¿Te sentiste especialmente fatigado o estabas probando un peso mayor?</p>
                    </div>
                </div>

                <div class="msg user-msg">
                    <div class="bubble">
                        <p>Subí 5kg en el último set. Me costó bastante terminarlo.</p>
                    </div>
                    <div class="who"><img src="{{ asset('img/user.png') }}" alt=""></div>
                </div>

                <div class="msg assistant-msg">
                    <div class="who">
                        <div class="coach-badge" style="width:34px; height:34px; border-radius:10px;">
                            <svg viewBox="0 0 24 24" style="width:16px; height:16px;">
                                <path
                                    d="M12 2a7 7 0 0 0-7 7c0 2.6 1.4 4.9 3.5 6.1V22l3-1.6 3 1.6v-6.9C17.6 13.9 19 11.6 19 9a7 7 0 0 0-7-7Zm0 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="bubble">
                        <h4>Análisis de Esfuerzo</h4>
                        <p>Entiendo. Ese incremento explica el pico de pulsaciones. Sugerencia:</p>
                        <ul>
                            <li>Mantén ese peso la próxima sesión pero reduce las repeticiones a 8.</li>
                            <li>Aumenta el descanso entre sets a 120 segundos.</li>
                        </ul>
                        <p>¿Quieres que actualice tu plan de mañana considerando este ajuste?</p>
                    </div>
                </div>

                <div class="day-sep">Hoy</div>
            </div>

            <div class="chat-footer">
                <div class="quick-actions">
                    <button class="chip primary">Optimizar rutina de mañana</button>
                    <button class="chip">Ver mi progreso semanal</button>
                    <button class="chip">¿Cuánto debo descansar hoy?</button>
                </div>

                <form class="composer" id="iaForm">
                    <div class="field">
                        <textarea placeholder="Escribe tu consulta al IA Coach..." id="iaInput"></textarea>
                    </div>
                    <button class="send" type="submit">
                        <svg viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* --------------------------------------------------------------------------
          IA COACH STYLES
        -------------------------------------------------------------------------- */
        .main {
            min-width: 0;
            display: grid;
            grid-template-rows: auto 1fr;
            gap: 18px;
            align-content: start;
            height: calc(100vh - 56px);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .hero h1 {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            font-size: 44px;
            color: var(--cream);
        }

        .hero p {
            margin: 6px 0 0;
            color: rgba(239, 231, 214, .60);
            font-size: 12px;
            text-transform: uppercase;
        }

        .chat-surface {
            min-width: 0;
            border-radius: 18px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .18);
            backdrop-filter: blur(12px);
            overflow: hidden;
            display: grid;
            grid-template-rows: auto 1fr auto;
        }

        .chat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .10);
        }

        .coach-badge {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .18);
            display: grid;
            place-items: center;
            color: rgba(239, 231, 214, .86);
        }

        .chat-body {
            overflow: auto;
            padding: 18px 16px 10px;
        }

        .day-sep {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 8px 0 14px;
            color: rgba(239, 231, 214, .42);
            font-size: 11px;
            text-transform: uppercase;
        }

        .day-sep::before,
        .day-sep::after {
            content: "";
            height: 1px;
            background: rgba(239, 231, 214, .08);
            flex: 1;
        }

        .msg {
            display: grid;
            grid-template-columns: 44px 1fr;
            gap: 10px;
            margin: 10px 0 14px;
        }

        .msg.user-msg {
            grid-template-columns: 1fr 44px;
        }

        .who {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(239, 231, 214, .14);
            display: grid;
            place-items: center;
        }

        .who img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bubble {
            border-radius: 16px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .18);
            padding: 12px;
            color: rgba(239, 231, 214, .88);
            font-size: 13px;
            line-height: 1.5;
        }

        .composer {
            display: grid;
            grid-template-columns: 1fr 56px;
            gap: 12px;
            padding: 14px 16px 16px;
            border-top: 1px solid rgba(239, 231, 214, .10);
        }

        .field textarea {
            width: 100%;
            height: 52px;
            resize: none;
            border: 0;
            outline: none;
            padding: 14px;
            background: rgba(0, 0, 0, .16);
            color: white;
            border-radius: 16px;
            border: 1px solid rgba(239, 231, 214, .12);
        }

        .send {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(70, 98, 72, .9), rgba(40, 65, 40, .95));
            border: 1px solid rgba(239, 231, 214, .14);
            color: white;
            cursor: pointer;
            display: grid;
            place-items: center;
        }

        .chip {
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(255, 255, 255, .05);
            color: rgba(239, 231, 214, .78);
            font-size: 11px;
            cursor: pointer;
        }

        .quick-actions {
            display: flex;
            gap: 10px;
            padding: 12px 16px 0;
        }
    </style>
@endpush
