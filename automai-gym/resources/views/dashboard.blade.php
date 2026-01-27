@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="main">
        <div>
            <header class="hero">
                <h1>Buenos días, Marcelo.</h1>
                <p>Hoy entrenas con intención.</p>
            </header>

            <div class="section-title">
                <span class="dot" aria-hidden="true"></span>
                <span>Tu día</span>
            </div>

            <div class="grid-cards">
                <article class="card">
                    <div>
                        <h3>Entrenamiento de hoy</h3>
                        <p class="bigline">Espalda y Bíceps</p>
                        <div class="meta">50 min · Intensidad moderada</div>
                    </div>
                    <button class="btn" type="button">Empezar entrenamiento</button>
                </article>

                <article class="card">
                    <div>
                        <h3>Mis Rutinas</h3>
                        <p class="bigline">Fuerza y Enfoque</p>
                        <div class="meta">Semana 2 de 8 · 3/4 completados</div>
                    </div>
                    <button class="btn" type="button">Ver rutina</button>
                </article>

                <article class="card">
                    <div>
                        <h3>Progreso semanal</h3>
                        <div class="progress-row">
                            <b>3 / 5</b>
                            <span class="meta">entrenamientos</span>
                        </div>
                        <div class="bar" aria-label="Progreso semanal">
                            <span></span>
                        </div>
                        <div class="meta" style="margin-top:10px;">Mantén el ritmo. Sin ruido.</div>
                    </div>
                    <button class="btn" type="button">Ver progreso</button>
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
                            <span class="dot-mini" aria-hidden="true"></span>
                            Días con reservas
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
            <div class="chat-head">
                <div class="left">
                    <div class="chip" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path
                                d="M12 2a6 6 0 0 0-6 6v3H5a3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1h1a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3h-1V8a6 6 0 0 0-6-6Zm-4 6a4 4 0 1 1 8 0v3H8V8Zm10 5a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-1v-4h1ZM6 17H5a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1h1v4Zm2 3v-7h8v7H8Z" />
                        </svg>
                    </div>
                    <div style="min-width:0;">
                        <h2>IA Coach</h2>
                        <p>Rutinas y reservas, sin fricción.</p>
                    </div>
                </div>
                <button class="kebab" type="button" aria-label="Opciones">···</button>
            </div>

            <div class="chat-body">
                <div class="bubble ai">Hola Marcelo, ¿qué quieres hacer hoy: generar rutina o reservar una
                    clase?</div>
                <div class="bubble user">Hoy quiero entrenar espalda y bíceps, pero estoy cansado y con
                    agujetas.</div>
                <div class="bubble ai">Perfecto. Te preparo una rutina de baja fatiga (RPE 6–7) con énfasis en
                    técnica.
                    ¿Dispones de polea, mancuernas o máquinas?</div>
                <div class="bubble user">Polea y mancuernas.</div>
                <div class="bubble ai">Genial. Empiezo: calentamiento 6 min + movilidad escapular. Luego te paso
                    5
                    ejercicios y lo dejamos guardado en “Mis Rutinas”.</div>
            </div>

            <div class="chat-foot">
                <button class="iconbtn" type="button" aria-label="Adjuntar">＋</button>
                <input class="chat-input" type="text" placeholder="Escribe un mensaje… (rutina / reserva)" />
                <button class="iconbtn" type="button" aria-label="Enviar">➤</button>
            </div>
        </aside>
    </div>
@endsection

@push('styles')
    <style>
        /* --------------------------------------------------------------------------
          MAIN DASHBOARD STYLES
        -------------------------------------------------------------------------- */
        .main {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 10px;
            align-content: start;
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
            box-shadow: 0 18px 52px rgba(0, 0, 0, .50);
            transition: transform .18s ease, filter .18s ease, border-color .18s ease;
        }

        .progreso-row {
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

        .bar {
            position: relative;
            z-index: 1;
            margin-top: 10px;
            width: 100%;
            height: 10px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .14);
            background: rgba(0, 0, 0, .18);
            overflow: hidden;
        }

        .bar>span {
            display: block;
            height: 100%;
            width: 60%;
            background: linear-gradient(90deg, rgba(22, 250, 22, .18), rgba(239, 231, 214, .22));
            border-right: 1px solid rgba(239, 231, 214, .16);
        }

        /* --------------------------------------------------------------------------
          CALENDARIO INTERACTIVO
        -------------------------------------------------------------------------- */
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
          IA COACH (CARD TIPO CHATGPT)
        -------------------------------------------------------------------------- */
        .chat {
            grid-column: 2 / 3;
            align-self: start;
            position: sticky;
            top: 28px;
            height: calc(100vh - 56px);
            display: grid;
            grid-template-rows: auto 1fr auto;
            border-radius: var(--r2);
            padding: 14px;
            background:
                radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .16), transparent 65%),
                linear-gradient(180deg, rgba(0, 0, 0, .16), rgba(0, 0, 0, .10)),
                rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .14);
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(18px) saturate(115%);
            -webkit-backdrop-filter: blur(18px) saturate(115%);
            overflow: hidden;
            min-width: 0;
        }

        .chat-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 6px 6px 10px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
        }

        .chip {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .14);
            background:
                radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, 0.10), transparent 35%),
                rgba(0, 0, 0, .14);
            display: grid;
            place-items: center;
            color: rgba(239, 231, 214, .86);
            flex: 0 0 34px;
        }

        .chat-head h2 {
            margin: 0;
            font-family: var(--sans);
            font-weight: 900;
            letter-spacing: .02em;
            color: rgba(239, 231, 214, .92);
            font-size: 15px;
        }

        .chat-head p {
            margin: 2px 0 0;
            color: rgba(239, 231, 214, .56);
            font-size: 12px;
            letter-spacing: .02em;
        }

        .kebab {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .70);
            cursor: pointer;
        }

        .chat-body {
            padding: 14px 6px;
            overflow: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .bubble {
            max-width: 92%;
            padding: 12px 12px;
            border-radius: 16px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .14);
            color: rgba(239, 231, 214, .82);
            font-size: 13px;
            line-height: 1.4;
            box-shadow: 0 12px 32px rgba(0, 0, 0, .24);
        }

        .bubble.ai {
            border-top-left-radius: 10px;
            background:
                radial-gradient(120% 140% at 20% 0%, rgba(190, 145, 85, .12), transparent 45%),
                rgba(0, 0, 0, .14);
        }

        .bubble.user {
            align-self: flex-end;
            border-top-right-radius: 10px;
            background:
                radial-gradient(120% 140% at 20% 0%, rgba(22, 250, 22, .10), transparent 45%),
                rgba(0, 0, 0, .16);
        }

        .chat-foot {
            padding: 10px 6px 6px;
            border-top: 1px solid rgba(239, 231, 214, .10);
            display: grid;
            grid-template-columns: 44px 1fr 44px;
            gap: 10px;
            align-items: center;
        }

        .iconbtn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .74);
            cursor: pointer;
        }

        .chat-input {
            height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .90);
            padding: 0 12px;
            outline: none;
            font-family: var(--sans);
            font-size: 13px;
        }

        @media (max-width: 1180px) {
            .main {
                grid-template-columns: 1fr;
            }

            .chat {
                position: relative;
                top: 0;
                height: auto;
                grid-column: 1 / -1;
            }

            .grid-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .grid-cards {
                grid-template-columns: 1fr;
            }

            .cal-wrap {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Datos demo: reservas/clases por fecha (YYYY-MM-DD)
        const demoEvents = {
            "2026-01-24": [{
                    title: "Clase: Espalda & Core",
                    time: "11:00",
                    place: "Sala 2"
                },
                {
                    title: "Reserva: Spinning (confirmada)",
                    time: "18:30",
                    place: "Sala Cycling"
                },
                {
                    title: "Recordatorio: Registrar sesión",
                    time: "Después",
                    place: "App"
                }
            ],
            "2026-01-26": [{
                title: "Clase: Movilidad",
                time: "10:00",
                place: "Sala 1"
            }],
            "2026-01-29": [{
                title: "Reserva: HIIT",
                time: "19:00",
                place: "Box"
            }]
        };

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
                if (demoEvents[key]?.length) cell.classList.add("has-event");

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
            <div class="title">Sin reservas para este día</div>
            <div class="time">Usa IA Coach para reservar una clase</div>
          </div>`;
                eventsListEl.appendChild(empty);
                return;
            }

            events.forEach(ev => {
                const item = document.createElement("div");
                item.className = "event";
                item.innerHTML = `
          <span class="badge" aria-hidden="true"></span>
          <div class="text">
            <div class="title">${ev.title}</div>
            <div class="time">${ev.time} · ${ev.place}</div>
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
