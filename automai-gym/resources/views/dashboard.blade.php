@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        /* LAYOUT CRÍTICO */
        div.main {
            display: flex !important;
            flex-direction: row !important;
            gap: 20px !important;
            align-items: flex-start !important;
            width: 100% !important;
        }

        div.dashboard-content {
            flex: 1 1 0 !important;
            min-width: 0 !important;
        }

        aside.chat {
            flex: 0 0 380px !important;
            width: 380px !important;
            align-self: flex-start !important;
            position: sticky !important;
            top: 28px !important;
            height: calc(100vh - 56px) !important;
            display: flex !important;
            flex-direction: column !important;
        }

        aside.chat .chat-body {
            flex: 1 !important;
            overflow-y: auto !important;
        }

        aside.chat .chat-foot {
            flex-shrink: 0 !important;
        }
    </style>
    <div class="main">
        <div class="dashboard-content">
            <header class="hero">
                <h1>{{ __('Buenos días') }}, {{ Auth::user()->nombre_mostrado_usuario ?? 'Usuario' }}.</h1>
                <p>Hoy entrenas con intención.</p>
            </header>

            <div class="section-title">
                <span class="dot" aria-hidden="true"></span>
                <span>Tu día</span>
            </div>

            <div class="grid-cards">
                {{-- Card 1: rutinas_usuario (rutina activa para hoy) --}}
                <article class="card">
                    <div>
                        <h3>Rutina para hoy</h3>
                        @if ($routineToday)
                            <p class="bigline">{{ $routineToday->nombre_rutina_usuario }}</p>
                            <div class="meta">
                                objetivo: {{ $routineToday->objetivo_rutina_usuario }} ·
                                nivel: {{ $routineToday->nivel_rutina_usuario }} ·
                                {{ $routineToday->duracion_estimada_minutos }} min
                            </div>
                        @else
                            <p class="bigline">Día de descanso</p>
                            <div class="meta">Recupera fuerzas para mañana.</div>
                        @endif
                    </div>
                    @if ($routineToday)
                        <a href="{{ route('detalle-rutina', $routineToday->id_rutina_usuario) }}" class="btn">Ver
                            rutina</a>
                    @else
                        <a href="{{ route('rutinas') }}" class="btn">Ver mis rutinas</a>
                    @endif
                </article>

                {{-- Card 2: rutinas_usuario (listado) --}}
                <article class="card">
                    <div>
                        <h3>Explorar Rutinas</h3>
                        <p class="bigline">Mis RUTINAS &amp; Planes</p>
                        <div class="meta">Gestiona tus entrenamientos activos.</div>
                    </div>
                    <a href="{{ route('rutinas') }}" class="btn">Abrir rutinas</a>
                </article>

                {{-- Card 3: registros_progreso --}}
                <article class="card">
                    <div>
                        <h3>Último progreso</h3>
                        @if ($randomProgress)
                            <div class="progress-row">
                                <b>{{ number_format($randomProgress['value'], 2) }}</b>
                                <span class="meta">{{ $randomProgress['unit'] }} ({{ $randomProgress['label'] }})</span>
                            </div>
                            <div class="meta" style="margin-top:10px;">
                                fecha_registro: {{ $randomProgress['date'] }} · notas:
                                {{ $randomProgress['notes'] ?? '—' }}
                            </div>
                        @else
                            <p class="bigline">Sin registros</p>
                            <div class="meta">Registra tus medidas hoy.</div>
                        @endif
                    </div>
                    <a href="{{ route('progreso') }}" class="btn">Ver progreso</a>
                </article>
            </div>

            <section class="calendar" aria-label="Calendario y reservas">
                <div class="calendar-head">
                    <h2>Calendario</h2>
                    <div class="pill" id="selectedLabel">—</div>
                </div>

                <div class="cal-wrap">
                    <div class="cal-left">
                        <div class="cal-top">
                            <button class="cal-nav" id="prevMonth" aria-label="Mes anterior">‹</button>
                            <div class="cal-month" id="monthTitle">—</div>
                            <button class="cal-nav" id="nextMonth" aria-label="Mes siguiente">›</button>
                        </div>

                        <div class="cal-weekdays">
                            <span>L</span><span>M</span><span>X</span><span>J</span><span>V</span><span>S</span><span>D</span>
                        </div>

                        <div class="cal-days" id="calDays"></div>

                        <div class="cal-hint">
                            <span class="dot-mini green" aria-hidden="true"></span> Clases
                            <span class="dot-mini gold" style="margin-left:8px;" aria-hidden="true"></span> Rutinas
                        </div>
                    </div>

                    <div class="cal-right">
                        <div class="daybox">
                            <div class="label">Seleccionado</div>
                            <div class="num" id="selectedDayNum">—</div>
                        </div>

                        <div class="events" id="eventsList"></div>
                    </div>
                </div>
            </section>
        </div>

        <!-- IA COACH -->
        <aside class="chat" aria-label="IA Coach">
            <!-- HEAD -->
            <div class="chat-head">
                <div class="chat-head-left">
                    <div class="dash-ia-avatar">
                        <img src="{{ asset('img/ia-coach.jpg') }}" alt="IA Coach">
                        <span class="dash-online-dot"></span>
                    </div>
                    <div style="min-width:0;">
                        <h2>IA Coach</h2>
                        <p id="dashIaStatus">En línea · Tu entrenador personal</p>
                    </div>
                </div>
                <div class="dash-chat-actions">
                    <select id="dashConvSelect" title="Conversaciones" aria-label="Seleccionar conversación">
                        <option value="">+ Nueva</option>
                    </select>
                    <button id="dashDeleteConvBtn" class="dash-delete-btn" title="Eliminar conversación" aria-label="Eliminar conversación" style="display:none;">
                        <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M9 3v1H4v2h1l1 14h12l1-14h1V4h-5V3H9zm2 5h2v9h-2V8zm-3 0h2v9H8V8zm6 0h2v9h-2V8z"/></svg>
                    </button>
                </div>
            </div>

            <!-- BODY -->
            <div class="chat-body" id="dashChatBody">
                <div id="dashLoadMoreWrap" style="display:none; text-align:center; padding:4px 0;">
                    <button id="dashLoadMoreBtn" class="dash-load-more-btn">↑ Anteriores</button>
                </div>
                <div id="dashMessagesContainer"></div>
                <div class="dash-ai-msg" id="dashTypingIndicator" style="display:none;">
                    <div class="dash-bubble dash-bubble-ai dash-typing">
                        <span class="dash-dot"></span><span class="dash-dot"></span><span class="dash-dot"></span>
                    </div>
                </div>
            </div>

            <!-- FOOT -->
            <div class="chat-foot">
                <form id="dashChatForm" class="dash-chat-form">
                    @csrf
                    <div class="dash-input-wrapper">
                        <textarea id="dashChatInput" rows="1" placeholder="Escribe al IA Coach…"></textarea>
                    </div>
                    <button type="submit" class="dash-send-btn" aria-label="Enviar">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </form>
            </div>
        </aside>
    </div>
@endsection

@push('styles')
    <style>
        /* --------------------------------------------------------------------------
                                                      MAIN     DASHBOARD STYLES
                                                      ----  ---------------------------------------------------------------------- */
        .main {
            display: flex;
            flex-direction: row;
            gap: 20px;
            align-items: flex-start;
            width: 100%;
        }

        .main>div:first-child {
            flex: 1;
            min-width: 0;
        }

        .hero {
            padding: 10px 6px 6px;
        }

        .hero h1 {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            font-size: clamp(34px, 3vw, 54px);
            line-height: 1.02;
            color: rgba(239, 231, 214, .90);
            text-shadow: 0 12px 40px rgba(0, 0, 0, .62);
            letter-spacing: .01em;
        }

        .hero p {
            margin: 10px 0 0;
            font-family: var(--sans);
            color: rgba(239, 231, 214, .62);
            letter-spacing: .08em;
            text-transform: uppercase;
            font-size: 12px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 18px 0 10px;
            color: rgba(239, 231, 214, .78);
            font-weight: 750;
            letter-spacing: .02em;
            user-select: none;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(22, 250, 22, .28);
            box-shadow: 0 0 0 4px rgba(22, 250, 22, .06);
            border: 1px solid rgba(239, 231, 214, .14);
        }

        .grid-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .card {
            position: relative;
            border-radius: 14px;
            padding: 16px 16px 14px;
            min-height: 210px;
            background:
                radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .18), transparent 62%),
                linear-gradient(180deg, rgba(0, 0, 0, .10), rgba(0, 0, 0, .06)),
                rgba(0, 0, 0, .08);
            border: 1px solid rgba(239, 231, 214, .14);
            box-shadow: 0 14px 38px rgba(0, 0, 0, .36);
            backdrop-filter: blur(14px) saturate(112%);
            -webkit-backdrop-filter: blur(14px) saturate(112%);
            overflow: hidden;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(239, 231, 214, .06), transparent 40%, rgba(239, 231, 214, .04)),
                radial-gradient(140% 120% at 50% 15%, rgba(0, 0, 0, 0), rgba(0, 0, 0, .18));
            opacity: .95;
            pointer-events: none;
        }

        .card h3 {
            position: relative;
            z-index: 1;
            margin: 0 0 8px;
            font-size: 12px;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: rgba(239, 231, 214, .58);
            font-weight: 850;
        }

        .bigline {
            position: relative;
            z-index: 1;
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            color: rgba(239, 231, 214, .92);
            font-size: 20px;
            line-height: 1.12;
            letter-spacing: .01em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .meta {
            position: relative;
            z-index: 1;
            margin-top: 8px;
            color: rgba(239, 231, 214, .52);
            font-size: 12.5px;
            letter-spacing: .02em;
        }

        .btn {
            position: relative;
            z-index: 1;
            margin-top: 12px;
            width: 100%;
            height: 44px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .16);
            cursor: pointer;
            background:
                radial-gradient(120% 160% at 30% 0%, var(--greenGlow), transparent 35%),
                linear-gradient(180deg, var(--greenBtn1), var(--greenBtn2));
            color: rgba(239, 231, 214, .95);
            font-family: var(--sans);
            font-weight: 800;
            letter-spacing: .06em;
            font-size: 13px;
            box-shadow: 0 18px 52px rgba(0, 0, 0, .50);
            transition: transform .18s ease, filter .18s ease, border-color .18s ease;
            display: inline-grid;
            place-items: center;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
            border-color: rgba(239, 231, 214, .22);
        }

        .btn:active {
            transform: translateY(0);
            filter: brightness(1.02);
        }

        .progress-row {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin-top: 8px;
        }

        .progress-row b {
            font-family: var(--serif);
            font-weight: 500;
            font-size: 28px;
            color: rgba(239, 231, 214, .92);
            line-height: 1;
        }

        /* --------------------------------------------------------------------------
                                                          CALENDARIO INTERACTIVO
                                                    ----    ---------------------------------------------------------------------- */
        .calendar {
            margin-top: 14px;
            border-radius: var(--r);
            padding: 18px;
            background:
                radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .16), transparent 65%),
                linear-gradient(180deg, rgba(0, 0, 0, .12), rgba(0, 0, 0, .08)),
                rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .12);
            box-shadow: 0 18px 52px rgba(0, 0, 0, .40);
            backdrop-filter: blur(16px) saturate(112%);
            -webkit-backdrop-filter: blur(16px) saturate(112%);
            overflow: hidden;
        }

        .calendar-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .calendar-head h2 {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            color: rgba(239, 231, 214, .92);
            letter-spacing: .01em;
            font-size: 22px;
        }

        .pill {
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .14);
            background: rgba(0, 0, 0, .14);
            color: rgba(239, 231, 214, .70);
            font-size: 12px;
            letter-spacing: .08em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .cal-wrap {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 14px;
            align-items: start;
        }

        .cal-left {
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .10);
            padding: 14px;
        }

        .cal-top {
            display: grid;
            grid-template-columns: 44px 1fr 44px;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
        }

        .cal-nav {
            width: 44px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .78);
            cursor: pointer;
            transition: transform .18s ease, border-color .18s ease;
        }

        .cal-nav:hover {
            transform: translateY(-1px);
            border-color: rgba(239, 231, 214, .18);
        }

        .cal-month {
            text-align: center;
            font-family: var(--serif);
            font-size: 18px;
            font-weight: 500;
            color: rgba(239, 231, 214, .92);
            letter-spacing: .01em;
        }

        .cal-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            margin: 8px 0 10px;
            text-align: center;
        }

        .cal-weekdays span {
            font-size: 11px;
            color: rgba(239, 231, 214, .46);
            letter-spacing: .10em;
            text-transform: uppercase;
        }

        .cal-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
        }

        .day {
            height: 36px;
            border-radius: 10px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .10);
            color: rgba(239, 231, 214, .78);
            display: grid;
            place-items: center;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
            position: relative;
            transition: transform .15s ease, border-color .15s ease, background .15s ease;
        }

        .day:hover {
            transform: translateY(-1px);
            border-color: rgba(239, 231, 214, .18);
            background: rgba(0, 0, 0, .14);
        }

        .day.muted {
            color: rgba(239, 231, 214, .22);
            background: rgba(0, 0, 0, .06);
        }

        .day.today {
            border-color: rgba(239, 231, 214, .22);
            background:
                radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, .14), transparent 35%),
                linear-gradient(180deg, rgba(70, 98, 72, .55), rgba(43, 70, 43, .60));
            color: rgba(239, 231, 214, .96);
            font-weight: 800;
        }

        .day.selected {
            border-color: rgba(190, 145, 85, .35);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .10);
        }

        .day.has-event::after {
            content: "";
            position: absolute;
            bottom: 6px;
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: rgba(22, 250, 22, .34);
            border: 1px solid rgba(239, 231, 214, .14);
            box-shadow: 0 0 0 3px rgba(22, 250, 22, .06);
        }

        .day.has-rutina::after {
            background: rgba(190, 145, 85, .75);
            box-shadow: 0 0 0 3px rgba(190, 145, 85, .12);
        }

        .day.has-both::after {
            background: linear-gradient(135deg, rgba(22, 250, 22, .5) 50%, rgba(190, 145, 85, .75) 50%);
        }

        .cal-hint {
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(239, 231, 214, .52);
            font-size: 12px;
        }

        .dot-mini {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: rgba(22, 250, 22, .34);
            border: 1px solid rgba(239, 231, 214, .14);
            box-shadow: 0 0 0 4px rgba(22, 250, 22, .06);
            display: inline-block;
        }

        .dot-mini.gold {
            background: rgba(190, 145, 85, .80);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .10);
            border-color: rgba(190, 145, 85, .20);
        }

        .dot-mini.green {
            background: rgba(22, 250, 22, .34);
        }

        .cal-right {
            display: grid;
            gap: 12px;
        }

        .daybox {
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            padding: 14px;
        }

        .daybox .label {
            font-size: 12px;
            color: rgba(239, 231, 214, .58);
            letter-spacing: .10em;
            text-transform: uppercase;
        }

        .daybox .num {
            margin-top: 8px;
            font-family: var(--serif);
            font-size: 38px;
            line-height: 1;
            color: rgba(239, 231, 214, .92);
            font-weight: 500;
        }

        .events {
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .10);
            padding: 14px;
            display: grid;
            gap: 10px;
            min-width: 0;
            align-content: start;
        }

        .event {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 10px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .12);
            min-width: 0;
            text-decoration: none;
        }

        .event .badge {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(22, 250, 22, .26);
            border: 1px solid rgba(239, 231, 214, .12);
            box-shadow: 0 0 0 4px rgba(22, 250, 22, .05);
            flex: 0 0 10px;
        }

        .event .badge.gold {
            background: rgba(190, 145, 85, .75);
            border-color: rgba(190, 145, 85, .20);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .08);
        }

        .event .text {
            min-width: 0;
        }

        .event .title {
            color: rgba(239, 231, 214, .88);
            font-weight: 750;
            font-size: 13px;
            letter-spacing: .02em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event .time {
            margin-top: 2px;
            color: rgba(239, 231, 214, .56);
            font-size: 12px;
            letter-spacing: .02em;
        }

        /* --------------------------------------------------------------------------
                               IA COACH – CHAT FUNCIONAL (Dashboard aside)
           -------------------------------------------------------------------------- */
        .chat {
            flex: 0 0 380px;
            width: 380px;
            position: sticky;
            top: 28px;
            height: calc(100vh - 56px);
            display: flex;
            flex-direction: column;
            border-radius: var(--r2);
            background:
                radial-gradient(140% 120% at 18% 10%, rgba(16,18,14,.98), transparent 65%),
                rgba(16,18,14,.96);
            border: 1px solid rgba(90,120,70,.25);
            box-shadow: 0 8px 40px rgba(0,0,0,.6);
            overflow: hidden;
        }

        /* ── head ── */
        .chat-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 16px 12px;
            background: rgba(20,24,16,.99);
            border-bottom: 1px solid rgba(90,120,70,.2);
            flex-shrink: 0;
        }
        .chat-head-left { display:flex; align-items:center; gap:11px; min-width:0; }
        .dash-ia-avatar {
            position:relative; width:40px; height:40px; border-radius:50%;
            border: 2px solid rgba(120,180,80,.4); flex-shrink:0;
        }
        .dash-ia-avatar img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
        .dash-online-dot {
            position:absolute; bottom:1px; right:1px; width:9px; height:9px;
            background:#4ade80; border-radius:50%; border:2px solid #141810;
            box-shadow:0 0 6px rgba(74,222,128,.8);
        }
        .chat-head h2 {
            margin:0; font-family:var(--sans); font-weight:700; font-size:15px;
            color:#e8f0e0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
        }
        .chat-head p {
            margin:2px 0 0; font-size:11px; color:#4ade80; font-weight:500; letter-spacing:.01em;
        }
        .dash-chat-actions { display:flex; align-items:center; gap:7px; flex-shrink:0; }
        #dashConvSelect {
            background:rgba(30,35,22,.95); border:1px solid rgba(90,120,70,.3);
            color:#c8d8b8; padding:5px 8px; border-radius:10px; font-size:11px;
            cursor:pointer; max-width:120px;
        }
        .dash-delete-btn {
            width:30px; height:30px; border-radius:8px; flex-shrink:0;
            background:rgba(180,40,40,.1); border:1px solid rgba(200,60,60,.25);
            color:rgba(255,140,120,.7); cursor:pointer;
            display:flex; align-items:center; justify-content:center;
            transition:background .15s, color .15s;
        }
        .dash-delete-btn:hover { background:rgba(200,40,40,.25); color:#ff9090; }

        /* ── body / mensajes ── */
        .chat-body {
            flex:1; overflow-y:auto; padding:14px 14px 8px;
            scroll-behavior:smooth; display:flex; flex-direction:column;
        }
        .chat-body::-webkit-scrollbar { width:4px; }
        .chat-body::-webkit-scrollbar-thumb { background:rgba(90,120,70,.3); border-radius:10px; }

        #dashMessagesContainer { display:flex; flex-direction:column; gap:10px; }

        .dash-msg {
            display:flex; flex-direction:column; max-width:80%;
            animation: dashFadeUp .22s ease-out;
        }
        .dash-ai-msg  { align-self:flex-start; }
        .dash-user-msg { align-self:flex-end; }

        .dash-bubble {
            padding:10px 13px; font-size:13px; line-height:1.5;
            word-break:break-word; white-space:pre-wrap; border-radius:16px;
        }
        .dash-bubble-ai {
            background:rgba(30,38,22,.98); border:1px solid rgba(80,130,55,.3);
            border-radius:4px 16px 16px 16px; color:#dcefd0;
        }
        .dash-bubble-user {
            background:rgba(40,80,30,.95); border:1px solid rgba(80,160,60,.4);
            border-radius:16px 4px 16px 16px; color:#d8f0cc;
        }
        .dash-bubble strong, .dash-bubble b { color:#8fe870; font-weight:600; }
        .dash-msg-time {
            font-size:10px; color:rgba(180,200,160,.5); padding:2px 3px; display:block;
        }
        .dash-ai-msg  .dash-msg-time { align-self:flex-start; }
        .dash-user-msg .dash-msg-time { align-self:flex-end; }

        /* typing */
        .dash-typing { display:flex; gap:4px; padding:11px 14px !important; align-items:center; min-width:50px; }
        .dash-dot {
            width:6px; height:6px; background:rgba(100,180,70,.7);
            border-radius:50%; animation:dashBounce 1.3s infinite ease-in-out;
        }
        .dash-dot:nth-child(1){animation-delay:0s;}
        .dash-dot:nth-child(2){animation-delay:.18s;}
        .dash-dot:nth-child(3){animation-delay:.36s;}
        @keyframes dashBounce { 0%,100%{transform:translateY(0);opacity:.4;} 50%{transform:translateY(-5px);opacity:1;} }
        @keyframes dashFadeUp  { from{opacity:0;transform:translateY(5px);} to{opacity:1;transform:translateY(0);} }

        .dash-load-more-btn {
            background:rgba(40,55,28,.8); border:1px solid rgba(90,120,70,.25);
            color:rgba(180,210,140,.7); padding:4px 14px; border-radius:20px;
            font-size:11px; cursor:pointer; transition:background .2s;
        }
        .dash-load-more-btn:hover { background:rgba(60,80,40,.9); }

        .dash-error-bubble {
            align-self:center; text-align:center;
            background:rgba(180,40,40,.12); border:1px solid rgba(200,60,60,.25);
            color:rgba(255,180,160,.85); padding:6px 12px; border-radius:10px; font-size:12px;
        }

        /* ── foot ── */
        .chat-foot {
            padding:10px 12px 12px;
            background:rgba(20,24,16,.99);
            border-top:1px solid rgba(90,120,70,.2);
            flex-shrink:0;
        }
        .dash-chat-form { display:flex; align-items:flex-end; gap:8px; }
        .dash-input-wrapper {
            flex:1;
            background:rgba(28,34,20,.98); border:1px solid rgba(90,120,70,.3);
            border-radius:18px; padding:8px 14px; transition:border-color .2s;
        }
        .dash-input-wrapper:focus-within { border-color:rgba(100,180,70,.55); }
        #dashChatInput {
            width:100%; background:transparent; border:none; color:#d8edcc;
            font-size:13px; resize:none; outline:none; max-height:100px;
            line-height:1.4; font-family:inherit;
        }
        #dashChatInput::placeholder { color:rgba(160,185,130,.4); }
        .dash-send-btn {
            width:38px; height:38px; border-radius:50%;
            background:linear-gradient(145deg,#3a7a28,#2a5a1a);
            border:1px solid rgba(80,160,60,.4); color:#c8f0b0;
            cursor:pointer; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            box-shadow:0 3px 12px rgba(60,150,40,.2);
            transition:transform .15s, filter .15s;
        }
        .dash-send-btn:hover  { transform:scale(1.08); filter:brightness(1.15); }
        .dash-send-btn:disabled { opacity:.4; cursor:not-allowed; transform:none; }
        .dash-send-btn svg { fill:#c8f0b0; transform:translateX(1px); }

        @media (max-width: 640px) {
            .grid-cards { grid-template-columns: 1fr; }
            .cal-wrap { grid-template-columns: 1fr; }
            .chat { display:none; }
        }
    </style>
@endpush

@push('scripts')
    <script>
        /* =====================================================================
           DASHBOARD – IA COACH CHAT (calco funcional de iaCoach.blade.php)
           ===================================================================== */
        (function() {
            const CSRF = '{{ csrf_token() }}';
            let dConvId = null, dOldestId = null, dLoading = false;

            const dBody    = document.getElementById('dashChatBody');
            const dMsgCont = document.getElementById('dashMessagesContainer');
            const dForm    = document.getElementById('dashChatForm');
            const dInput   = document.getElementById('dashChatInput');
            const dSendBtn = dForm.querySelector('.dash-send-btn');
            const dTyping  = document.getElementById('dashTypingIndicator');
            const dStatus  = document.getElementById('dashIaStatus');
            const dSel     = document.getElementById('dashConvSelect');
            const dLMWrap  = document.getElementById('dashLoadMoreWrap');
            const dLMBtn   = document.getElementById('dashLoadMoreBtn');
            const dDelBtn  = document.getElementById('dashDeleteConvBtn');

            function dFmtTime(s) {
                if (!s) return 'Ahora';
                return new Date(s).toLocaleTimeString('es-ES',{hour:'2-digit',minute:'2-digit'});
            }
            function dMdToHtml(t) {
                return t
                    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                    .replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>')
                    .replace(/\n/g,'<br>');
            }
            function dAppend(rol, contenido, dateStr=null, prepend=false) {
                const isUser = rol==='user';
                const w = document.createElement('div');
                w.className = 'dash-msg ' + (isUser ? 'dash-user-msg' : 'dash-ai-msg');
                w.innerHTML = `<div class="dash-bubble ${isUser?'dash-bubble-user':'dash-bubble-ai'}">${dMdToHtml(contenido)}</div><span class="dash-msg-time">${dFmtTime(dateStr)}</span>`;
                if (prepend) dMsgCont.insertBefore(w, dMsgCont.firstChild);
                else dMsgCont.appendChild(w);
                return w;
            }
            function dShowTyping() {
                dBody.appendChild(dTyping);
                dTyping.style.display='flex';
                dStatus.textContent='Escribiendo\u2026';
                dBody.scrollTop=dBody.scrollHeight;
            }
            function dHideTyping() {
                dTyping.style.display='none';
                dStatus.textContent='En l\u00ednea \u00b7 Tu entrenador personal';
            }
            function dShowError(txt) {
                const e=document.createElement('div');
                e.className='dash-error-bubble'; e.textContent=txt;
                dMsgCont.appendChild(e); dBody.scrollTop=dBody.scrollHeight;
            }
            function dSetLoading(s) { dLoading=s; dSendBtn.disabled=s; dInput.disabled=s; }

            async function dLoadConvs() {
                try {
                    const r=await fetch('/api/ia/conversations',{headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},credentials:'same-origin'});
                    if(!r.ok) return;
                    const convs=await r.json();
                    while(dSel.options.length>1) dSel.remove(1);
                    convs.forEach(c=>{ const o=document.createElement('option'); o.value=c.id; o.textContent=c.titulo||`Conv #${c.id}`; dSel.appendChild(o); });
                } catch(e){console.error(e);}
            }

            async function dLoadMsgs(convId, before=null) {
                const url=`/api/ia/conversations/${convId}/messages`+(before?`?before=${before}&limit=20`:'?limit=20');
                try {
                    const r=await fetch(url,{headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},credentials:'same-origin'});
                    if(!r.ok) return;
                    const data=await r.json();
                    if(!before) dMsgCont.innerHTML='';
                    const prevH=dBody.scrollHeight;
                    data.messages.forEach(m=>dAppend(m.rol,m.contenido,m.created_at,!!before));
                    if(data.has_more){ dOldestId=data.oldest_id; dLMWrap.style.display='block'; }
                    else { dLMWrap.style.display='none'; dOldestId=null; }
                    if(!before) dBody.scrollTop=dBody.scrollHeight;
                    else dBody.scrollTop=dBody.scrollHeight-prevH;
                } catch(e){console.error(e);}
            }

            dForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const text=dInput.value.trim();
                if(!text||dLoading) return;
                dSetLoading(true);
                dAppend('user',text);
                dInput.value=''; dInput.style.height='auto';
                dBody.scrollTop=dBody.scrollHeight;
                dShowTyping();
                try {
                    const body={message:text};
                    if(dConvId) body.conversation_id=dConvId;
                    const res=await fetch('/api/ia/chat',{
                        method:'POST',
                        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
                        credentials:'same-origin',
                        body:JSON.stringify(body),
                    });
                    dHideTyping();
                    if(!res.ok){ const err=await res.json().catch(()=>({})); dShowError(err.message||err.error||'Error al comunicarse con la IA.'); dSetLoading(false); return; }
                    const data=await res.json();
                    if(!dConvId&&data.conversation_id){ dConvId=data.conversation_id; const o=document.createElement('option'); o.value=dConvId; o.textContent=text.substring(0,40); o.selected=true; dSel.appendChild(o); dDelBtn.style.display='flex'; }
                    dAppend('assistant',data.message);
                    dBody.scrollTop=dBody.scrollHeight;
                } catch(err){ dHideTyping(); dShowError('Error de conexi\u00f3n. Verifica que Ollama est\u00e9 activo.'); console.error(err); }
                dSetLoading(false);
            });

            dInput.addEventListener('input', function(){ this.style.height='auto'; this.style.height=Math.min(this.scrollHeight,100)+'px'; });
            dInput.addEventListener('keydown', function(e){ if(e.key==='Enter'&&!e.shiftKey){ e.preventDefault(); if(this.value.trim()) dForm.dispatchEvent(new Event('submit')); } });

            dSel.addEventListener('change', async function(){
                const id=parseInt(this.value);
                dDelBtn.style.display=id?'flex':'none';
                if(!id){ dConvId=null; dOldestId=null; dMsgCont.innerHTML=''; dLMWrap.style.display='none'; return; }
                dConvId=id; await dLoadMsgs(id);
            });

            dDelBtn.addEventListener('click', async ()=>{
                if(!dConvId) return;
                if(!confirm('¿Eliminar esta conversación y todos sus mensajes?')) return;
                try {
                    const res=await fetch(`/api/ia/conversations/${dConvId}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'},credentials:'same-origin'});
                    if(!res.ok){ alert('No se pudo eliminar.'); return; }
                    const opt=dSel.querySelector(`option[value="${dConvId}"]`);
                    if(opt) opt.remove();
                    dConvId=null; dOldestId=null; dMsgCont.innerHTML=''; dLMWrap.style.display='none'; dDelBtn.style.display='none'; dSel.value='';
                    dAppend('assistant','\u2705 Conversaci\u00f3n eliminada. Puedes empezar una nueva.');
                } catch(e){ alert('Error al eliminar.'); console.error(e); }
            });

            dLMBtn.addEventListener('click', async ()=>{ if(dConvId&&dOldestId) await dLoadMsgs(dConvId,dOldestId); });

            // Init
            (async function(){
                await dLoadConvs();
                dAppend('assistant','\u00a1Hola, {{ Auth::user()->nombre_mostrado_usuario ?? "atleta" }}! \ud83d\udcaa Soy tu IA Coach.\n\nPuedo ayudarte a:\n\u2022 Crear rutinas personalizadas\n\u2022 Reservar clases en el gimnasio\n\u2022 Analizar tu progreso\n\n\u00bfPor d\u00f3nde empezamos?');
            })();
        })();
        /* ===================================================================== */

        // Datos reales: reservas/clases por fecha (YYYY-MM-DD)
        const demoEvents = @json($events);

        const calDaysEl = document.getElementById("calDays");
        const monthTitleEl = document.getElementById("monthTitle");
        const selectedLabelEl = document.getElementById("selectedLabel");
        const selectedDayNumEl = document.getElementById("selectedDayNum");
        const eventsListEl = document.getElementById("eventsList");
        const prevBtn = document.getElementById("prevMonth");
        const nextBtn = document.getElementById("nextMonth");

        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre",
            "Octubre", "Noviembre", "Diciembre"
        ];
        const mondayIndex = (day) => (day + 6) % 7;

        let view = new Date();
        let selected = new Date();

        function fmtDateKey(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, "0");
            const day = String(d.getDate()).padStart(2, "0");
            return `${y}-${m}-${day}`;
        }

        function sameDay(a, b) {
            return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
        }

        function hasAnyEvents(key) {
            return Array.isArray(demoEvents[key]) && demoEvents[key].length > 0;
        }

        function hasOnlyRutinas(key) {
            if (!Array.isArray(demoEvents[key])) return false;
            return demoEvents[key].every(e => e.type === 'rutina');
        }

        function hasClases(key) {
            if (!Array.isArray(demoEvents[key])) return false;
            return demoEvents[key].some(e => e.type === 'clase');
        }

        function hasRutinas(key) {
            if (!Array.isArray(demoEvents[key])) return false;
            return demoEvents[key].some(e => e.type === 'rutina');
        }

        function render() {
            calDaysEl.innerHTML = "";
            const year = view.getFullYear();
            const month = view.getMonth();
            monthTitleEl.textContent = `${monthNames[month]} · ${year}`;

            const first = new Date(year, month, 1);
            const startOffset = mondayIndex(first.getDay());
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            for (let i = 0; i < 42; i++) {
                const cell = document.createElement("div");
                cell.className = "day";
                let date;
                if (i < startOffset) {
                    const dayNum = daysInPrevMonth - (startOffset - 1 - i);
                    date = new Date(year, month - 1, dayNum);
                    cell.classList.add("muted");
                } else if (i >= startOffset + daysInMonth) {
                    const dayNum = i - (startOffset + daysInMonth) + 1;
                    date = new Date(year, month + 1, dayNum);
                    cell.classList.add("muted");
                } else {
                    const dayNum = i - startOffset + 1;
                    date = new Date(year, month, dayNum);
                }

                cell.textContent = date.getDate();
                const key = fmtDateKey(date);
                if (hasAnyEvents(key)) {
                    cell.classList.add("has-event");
                    if (hasClases(key) && hasRutinas(key)) cell.classList.add("has-both");
                    else if (hasRutinas(key)) cell.classList.add("has-rutina");
                }

                const today = new Date();
                if (sameDay(date, today)) cell.classList.add("today");
                if (sameDay(date, selected)) cell.classList.add("selected");

                cell.addEventListener("click", () => {
                    selected = date;
                    renderSelected();
                    render();
                });
                calDaysEl.appendChild(cell);
            }
            renderSelected();
        }

        function renderSelected() {
            const key = fmtDateKey(selected);
            selectedDayNumEl.textContent = selected.getDate();
            const label = `${selected.getDate()} ${monthNames[selected.getMonth()]} · ${selected.getFullYear()}`;
            selectedLabelEl.textContent = label.toUpperCase();

            const events = demoEvents[key] || [];
            eventsListEl.innerHTML = "";

            if (!events.length) {
                const empty = document.createElement("div");
                empty.className = "event";
                empty.innerHTML = `
                  <span class="badge" aria-hidden="true" style="opacity:.35"></span>
                  <div class="text">
                    <div class="title">Sin clases ni rutinas</div>
                    <div class="time">Reserva una clase o asigna un día a una rutina</div>
                  </div>`;
                eventsListEl.appendChild(empty);
                return;
            }

            events.forEach(ev => {
                const isRutina = ev.type === 'rutina';
                const badgeClass = isRutina ? 'badge gold' : 'badge';
                const timeText = isRutina ? 'Entrenamiento Personal' : `${ev.time} · ${ev.place}`;
                const titleStyle = isRutina ? 'style="color: rgba(190,145,85,.9);"' : '';
                const item = document.createElement("div");
                item.className = "event";
                item.innerHTML = `
                  <span class="${badgeClass}" aria-hidden="true"></span>
                  <div class="text">
                    <div class="title" ${titleStyle}>${ev.title}</div>
                    <div class="time">${timeText}</div>
                  </div>`;
                eventsListEl.appendChild(item);
            });
        }

        prevBtn.addEventListener("click", () => {
            view = new Date(view.getFullYear(), view.getMonth() - 1, 1);
            render();
        });

        nextBtn.addEventListener("click", () => {
            view = new Date(view.getFullYear(), view.getMonth() + 1, 1);
            render();
        });

        view = new Date(selected.getFullYear(), selected.getMonth(), 1);
        render();
    </script>
@endpush
