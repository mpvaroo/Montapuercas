@extends('layouts.app')

@section('title', 'Detalle de Reserva')

@section('content')
<div class="main">
    <div>
        <header class="hero">
            <h1>Detalle de Reserva</h1>
            <p>Gestiona tu tiempo, maximiza tu rendimiento.</p>
        </header>

        <div class="section-title">
            <div class="left">
                <span class="dot"></span>
                <span>Información General</span>
            </div>
            <div class="pill">ID: #RES-7102</div>
        </div>

        <section class="panel">
            <div class="head-grid">
                <div class="title-block">
                    <h2>Entrenamiento Personalizado</h2>
                    <div class="sub">Sesión de alta intensidad con enfoque en fuerza funcional y movilidad.</div>
                </div>
                <div class="tags">
                    <span class="tag green">Confirmada</span>
                    <span class="tag bronze">Premium</span>
                </div>
            </div>

            <div class="metrics">
                <div class="metric">
                    <div class="k">Fecha</div>
                    <div class="v">24 Ene 2026</div>
                </div>
                <div class="metric">
                    <div class="k">Hora</div>
                    <div class="v">11:00 — 12:30</div>
                </div>
                <div class="metric">
                    <div class="k">Lugar</div>
                    <div class="v">Sala 2 / Box 4</div>
                </div>
                <div class="metric">
                    <div class="k">Coach</div>
                    <div class="v">Alex Rivera</div>
                </div>
            </div>

            <div class="btn-row">
                <button class="btn-ghost">Modificar fecha</button>
                <button class="btn-ghost">Contactar coach</button>
                <button class="btn-danger">Cancelar reserva</button>
            </div>

            <p class="note">Nota: Las cancelaciones con menos de 2 horas de antelación pueden incurrir en penalizaciones según tu plan.</p>
        </section>

        <div class="section-title">
            <div class="left">
                <span class="dot"></span>
                <span>Planificación de la Sesión</span>
            </div>
        </div>

        <div class="class-card">
            <article class="class-main">
                <h3>Resumen de la clase</h3>
                <p class="class-title">Espalda & Core: Estabilidad</p>
                <div class="class-meta">
                    Enfoque en tracciones pesadas controladas seguido de un bloque de estabilidad lumbopélvica.
                    Ideal para mejorar la postura y la fuerza de transferencia.
                </div>
            </article>

            <aside class="class-right">
                <div class="mini-box">
                    <div class="k">Ocupación</div>
                    <div class="v">8 / 12 plazas</div>
                    <div class="bar"><span></span></div>
                </div>
                <div class="mini-box">
                    <div class="k">Equipo necesario</div>
                    <div class="v">Toalla, Agua, Straps</div>
                </div>
            </aside>
        </div>

        <div class="section-title">
            <div class="left">
                <span class="dot"></span>
                <span>Check-in Digital</span>
            </div>
        </div>

        <div class="checkin-grid">
            <div class="check-card">
                <h3>Asistencia</h3>
                <div class="row">
                    <span class="k">Confirmar asistencia</span>
                    <div class="switch">
                        <div class="toggle on" id="toggleCheck"></div>
                    </div>
                </div>
            </div>
            <div class="check-card">
                <h3>Equipamiento</h3>
                <div class="row">
                    <span class="k">Kit de reserva (Straps)</span>
                    <span class="v">Entregado</span>
                </div>
            </div>
        </div>
    </div>

    <!-- IA COACH (SIDEBAR DERECHA) -->
    <aside class="chat" aria-label="IA Coach">
        <div class="chat-head">
            <div class="left">
                <div class="chip" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2a6 6 0 0 0-6 6v3H5a3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1h1a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3h-1V8a6 6 0 0 0-6-6Zm-4 6a4 4 0 1 1 8 0v3H8V8Zm10 5a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-1v-4h1ZM6 17H5a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1h1v4Zm2 3v-7h8v7H8Z"/>
                    </svg>
                </div>
                <div>
                    <h2>IA Coach</h2>
                    <p>Asistente de reserva.</p>
                </div>
            </div>
        </div>

        <div class="chat-body">
            <div class="bubble ai">Hola Marcelo, veo que tienes clase de Espalda & Core hoy a las 11:00.</div>
            <div class="bubble ai">Recuerda traer tus straps, hoy el volumen de tracción es alto. ¿Quieres que te envíe un recordatorio 15 min antes?</div>
            <div class="bubble user">Sí, por favor. Y confírmame si hay parking disponible.</div>
            <div class="bubble ai">¡Hecho! Recordatorio fijado. Hay 3 plazas libres en el parking ahora mismo.</div>
        </div>

        <div class="chat-foot">
            <button class="iconbtn">＋</button>
            <input class="chat-input" type="text" placeholder="Pregunta algo..." />
            <button class="iconbtn">➤</button>
        </div>
    </aside>
</div>
@endsection

@push('styles')
<style>
/* Estilos específicos de detalle-reserva (portados del HTML original) */
.main { display: grid; grid-template-columns: 1fr 380px; gap: 10px; align-content: start; min-width: 0; }
.hero { padding: 10px 6px 6px; }
.hero h1 { margin: 0; font-family: var(--serif); font-weight: 500; font-size: clamp(34px, 3vw, 54px); color: rgba(239, 231, 214, .90); text-shadow: 0 12px 40px rgba(0, 0, 0, .62); }
.hero p { margin: 10px 0 0; font-family: var(--sans); color: rgba(239, 231, 214, .62); letter-spacing: .08em; text-transform: uppercase; font-size: 12px; }
.section-title { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin: 18px 6px 10px; color: rgba(239, 231, 214, .78); font-weight: 750; letter-spacing: .02em; }
.section-title .left { display: flex; align-items: center; gap: 10px; }
.dot { width: 10px; height: 10px; border-radius: 999px; background: rgba(22, 250, 22, .28); border: 1px solid rgba(239, 231, 214, .14); }
.pill { padding: 8px 12px; border-radius: 999px; border: 1px solid rgba(239, 231, 214, .14); background: rgba(0, 0, 0, .14); color: rgba(239, 231, 214, .70); font-size: 11px; text-transform: uppercase; }
.panel { margin: 0 6px 14px; border-radius: var(--r); padding: 22px; background: rgba(0, 0, 0, .12); border: 1px solid rgba(239, 231, 214, .12); backdrop-filter: blur(16px); }
.head-grid { display: grid; grid-template-columns: 1fr auto; gap: 12px; border-bottom: 1px solid rgba(239, 231, 214, .10); padding-bottom: 14px; margin-bottom: 14px; }
.title-block h2 { margin: 0; font-family: var(--serif); font-size: 26px; color: var(--cream); }
.title-block .sub { margin-top: 8px; color: rgba(239, 231, 214, .56); font-size: 13px; }
.tags { display: flex; gap: 8px; }
.tag { padding: 6px 12px; border-radius: 999px; font-size: 11px; text-transform: uppercase; border: 1px solid rgba(239, 231, 214, .12); }
.tag.green { background: rgba(22, 250, 22, .1); color: #4ade80; }
.tag.bronze { background: rgba(190, 145, 85, .1); color: #d4a373; }
.metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
.metric { border-radius: 12px; border: 1px solid rgba(239, 231, 214, .10); background: rgba(0, 0, 0, .1); padding: 12px; }
.metric .k { font-size: 10px; color: rgba(239, 231, 214, .4); text-transform: uppercase; }
.metric .v { margin-top: 6px; font-family: var(--serif); font-size: 18px; color: var(--cream); }
.btn-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 20px; }
.btn-ghost { height: 42px; border-radius: 999px; border: 1px solid rgba(239, 231, 214, .16); background: transparent; color: var(--cream); cursor: pointer; font-size: 12px; font-weight: 700; transition: .2s; }
.btn-ghost:hover { background: rgba(239, 231, 214, .05); border-color: var(--cream); }
.btn-danger { height: 42px; border-radius: 999px; border: 1px solid rgba(255, 100, 100, .3); background: rgba(255, 50, 50, .1); color: #ff8888; cursor: pointer; font-size: 12px; font-weight: 700; transition: .2s; }
.btn-danger:hover { background: rgba(255, 50, 50, .2); border-color: #ff5555; }
.note { margin-top: 15px; font-size: 12px; color: rgba(239, 231, 214, .45); font-style: italic; }
.class-card { display: grid; grid-template-columns: 1fr 300px; gap: 12px; margin: 0 6px 14px; }
.class-main { border-radius: var(--r); padding: 20px; background: rgba(0, 0, 0, .1); border: 1px solid rgba(239, 231, 214, .12); }
.class-main h3 { margin: 0 0 6px; font-size: 11px; text-transform: uppercase; color: var(--cream-3); }
.class-title { font-family: var(--serif); font-size: 22px; color: var(--cream); margin: 0; }
.class-meta { margin-top: 10px; font-size: 13px; color: var(--cream-2); line-height: 1.5; }
.class-right { display: grid; gap: 12px; }
.mini-box { border-radius: 14px; padding: 15px; background: rgba(0, 0, 0, .15); border: 1px solid rgba(239, 231, 214, .1); }
.mini-box .k { font-size: 10px; color: var(--cream-3); text-transform: uppercase; }
.mini-box .v { font-size: 16px; color: var(--cream); margin-top: 5px; font-family: var(--serif); }
.bar { height: 8px; background: rgba(0,0,0,.3); border-radius: 4px; margin-top: 10px; overflow: hidden; border: 1px solid rgba(239,231,214,.1); }
.bar span { display: block; height: 100%; width: 66%; background: #4ade80; }
.checkin-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 0 6px; }
.check-card { border-radius: var(--r); padding: 18px; background: rgba(0, 0, 0, .1); border: 1px solid rgba(239, 231, 214, .12); }
.check-card h3 { font-size: 11px; text-transform: uppercase; color: var(--cream-3); margin: 0 0 12px; }
.row { display: flex; justify-content: space-between; align-items: center; border-radius: 10px; background: rgba(0,0,0,.2); padding: 12px; border: 1px solid rgba(239,231,214,.05); }
.row .k { font-size: 12px; color: var(--cream-2); }
.row .v { font-size: 12px; color: var(--cream); font-weight: 700; }
.toggle { width: 44px; height: 24px; border-radius: 12px; background: rgba(255,255,255,.1); position: relative; cursor: pointer; transition: .3s; }
.toggle::after { content: ""; width: 18px; height: 18px; border-radius: 50%; background: #fff; position: absolute; top: 3px; left: 3px; transition: .3s; }
.toggle.on { background: #4ade80; }
.toggle.on::after { left: 23px; }

/* IA Coach specific */
.chat { grid-column: 2 / 3; align-self: start; position: sticky; top: 28px; height: calc(100vh - 56px); display: grid; grid-template-rows: auto 1fr auto; border-radius: var(--r2); padding: 14px; background: rgba(0,0,0,.15); border: 1px solid rgba(239,231,214,.14); backdrop-filter: blur(18px); }
.chat-head { display: flex; align-items: center; gap: 10px; padding-bottom: 12px; border-bottom: 1px solid rgba(239,231,214,.1); }
.chip { width: 34px; height: 34px; border-radius: 10px; background: rgba(22,250,22,.1); display: grid; place-items: center; color: #4ade80; border: 1px solid rgba(239,231,214,.1); }
.chat-head h2 { font-size: 15px; margin: 0; color: var(--cream); }
.chat-head p { font-size: 11px; margin: 2px 0 0; color: var(--cream-3); }
.chat-body { padding: 15px 0; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; }
.bubble { max-width: 85%; padding: 10px 14px; border-radius: 15px; font-size: 13px; line-height: 1.4; color: var(--cream-2); border: 1px solid rgba(239,231,214,.1); }
.bubble.ai { background: rgba(255,255,255,.05); align-self: flex-start; border-top-left-radius: 4px; }
.bubble.user { background: rgba(22,250,22,.05); align-self: flex-end; border-top-right-radius: 4px; color: var(--cream); }
.chat-foot { display: flex; gap: 8px; padding-top: 12px; border-top: 1px solid rgba(239,231,214,.1); }
.iconbtn { width: 38px; height: 38px; border-radius: 10px; border: 1px solid rgba(239,231,214,.1); background: rgba(0,0,0,.2); color: var(--cream-2); cursor: pointer; }
.chat-input { flex: 1; border-radius: 10px; background: rgba(0,0,0,.2); border: 1px solid rgba(239,231,214,.1); color: #fff; padding: 0 12px; font-size: 13px; outline: none; }

@media (max-width: 1100px) { .main { grid-template-columns: 1fr; } .chat { display: none; } }
</style>
@endpush
