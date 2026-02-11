@extends('layouts.app')

@section('title', 'Detalle de Rutina')

@section('content')
    <div class="main">
        <div>
            <header class="hero">
                <h1>Detalle de Rutina</h1>
                <p>Constancia técnica, evolución real.</p>
            </header>

            <div class="section-title">
                <div class="left">
                    <span class="dot"></span>
                    <span>Configuración de la Rutina</span>
                </div>
                <div class="pill">Versión 2.4 — IA Gen</div>
            </div>

            <section class="panel">
                <div class="rutina-head">
                    <div class="rutina-title">
                        <h2>Espalda & Bíceps: Hipertrofia</h2>
                        <div class="sub">Diseñada para maximizar el reclutamiento de fibras en el plano sagital y frontal.
                            Balance de tracciones verticales y horizontales.</div>
                    </div>
                    <div class="chips">
                        <span class="tag green">Nivel Avanzado</span>
                        <span class="tag bronze">75 min aprox.</span>
                    </div>
                </div>

                <div class="metrics">
                    <div class="metric">
                        <div class="k">Frecuencia</div>
                        <div class="v">2x / semana</div>
                    </div>
                    <div class="metric">
                        <div class="k">Intensidad</div>
                        <div class="v">RPE 8 — 9</div>
                    </div>
                    <div class="metric">
                        <div class="k">Ejercicios</div>
                        <div class="v">6 bloques</div>
                    </div>
                    <div class="metric">
                        <div class="k">Objetivo</div>
                        <div class="v">Fuerza / Masa</div>
                    </div>
                </div>

                <div class="btn-row">
                    <button class="btn">Empezar ahora</button>
                    <button class="btn-ghost">Editar rutina</button>
                    <button class="btn-ghost" onclick="toggleModal()">Añadir ejercicio</button>
                </div>

                <p class="note">Nota: Esta rutina ha sido ajustada por tu IA Coach según tu nivel de fatiga reportado
                    ayer.</p>
            </section>

            <div class="section-title">
                <div class="left">
                    <span class="dot"></span>
                    <span>Bloques de Entrenamiento</span>
                </div>
            </div>

            <div class="list">
                <!-- Item 1 -->
                <article class="item">
                    <div class="num">01</div>
                    <div class="info">
                        <div class="name">Dominadas con lastre</div>
                        <div class="muscle">Dorsal ancho, redondo mayor</div>
                    </div>
                    <div class="params">
                        <div class="p">
                            <div class="k">Series</div>
                            <div class="v">4</div>
                        </div>
                        <div class="p">
                            <div class="k">Reps</div>
                            <div class="v">6 — 8</div>
                        </div>
                        <div class="p">
                            <div class="k">Carga</div>
                            <div class="v">+15 kg</div>
                        </div>
                        <div class="p">
                            <div class="k">Descanso</div>
                            <div class="v">180s</div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="mini green">Ver técnica</button>
                        <button class="mini">Historial</button>
                    </div>
                </article>

                <!-- Item 2 -->
                <article class="item">
                    <div class="num">02</div>
                    <div class="info">
                        <div class="name">Remo con barra (Pendlay)</div>
                        <div class="muscle">Trapecio medio, romboides</div>
                    </div>
                    <div class="params">
                        <div class="p">
                            <div class="k">Series</div>
                            <div class="v">3</div>
                        </div>
                        <div class="p">
                            <div class="k">Reps</div>
                            <div class="v">8 — 10</div>
                        </div>
                        <div class="p">
                            <div class="k">Carga</div>
                            <div class="v">80 kg</div>
                        </div>
                        <div class="p">
                            <div class="k">Descanso</div>
                            <div class="v">120s</div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="mini green">Ver técnica</button>
                        <button class="mini">Historial</button>
                    </div>
                </article>

                <!-- Item 3 -->
                <article class="item">
                    <div class="num">03</div>
                    <div class="info">
                        <div class="name">Jalón al pecho (Agarre neutro)</div>
                        <div class="muscle">Dorsal inferior</div>
                    </div>
                    <div class="params">
                        <div class="p">
                            <div class="k">Series</div>
                            <div class="v">3</div>
                        </div>
                        <div class="p">
                            <div class="k">Reps</div>
                            <div class="v">12</div>
                        </div>
                        <div class="p">
                            <div class="k">Carga</div>
                            <div class="v">65 kg</div>
                        </div>
                        <div class="p">
                            <div class="k">Descanso</div>
                            <div class="v">90s</div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="mini green">Ver técnica</button>
                        <button class="mini">Historial</button>
                    </div>
                </article>
            </div>
        </div>

        <!-- IA COACH -->
        <aside class="chat" aria-label="IA Coach">
            <div class="chat-head">
                <div class="left">
                    <div class="chip">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2a6 6 0 0 0-6 6v3H5a3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1h1a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3h-1V8a6 6 0 0 0-6-6Zm-4 6a4 4 0 1 1 8 0v3H8V8Zm10 5a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-1v-4h1ZM6 17H5a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1h1v4Zm2 3v-7h8v7H8Z" />
                        </svg>
                    </div>
                    <div>
                        <h2>IA Coach</h2>
                        <p>Foco técnico hoy.</p>
                    </div>
                </div>
            </div>
            <div class="chat-body">
                <div class="bubble ai">Marcelo, en las dominadas de hoy prioriza el descenso controlado. Queremos estirar
                    el dorsal bajo carga.</div>
                <div class="bubble ai">Si notas que el agarre falla, usa los straps que reservamos.</div>
                <div class="bubble user">Oído. ¿Puedo cambiar el Pendlay por Remo con mancuerna?</div>
                <div class="bubble ai">Poder puedes, pero el Pendlay nos da esa explosividad que buscamos hoy. Si no hay
                    barra libre, adelante con la mancuerna.</div>
            </div>
            <div class="chat-foot">
                <button class="iconbtn">＋</button>
                <input class="chat-input" type="text" placeholder="Duda sobre el ejercicio..." />
                <button class="iconbtn">➤</button>
            </div>
        </aside>
    </div>

    <!-- Modal para añadir ejercicios (portado del original) -->
    <div class="modal-backdrop" id="modalAdd">
        <div class="modal">
            <div class="modal-head">
                <h3>Añadir bloque a la rutina</h3>
                <button class="close" onclick="toggleModal()">✕</button>
            </div>
            <div class="modal-body">
                <div class="field">
                    <span class="label">Buscar ejercicio</span>
                    <input class="input" type="text" placeholder="Ej: Facepull, Curl Martillo...">
                </div>
                <div class="grid2">
                    <div class="field">
                        <span class="label">Series</span>
                        <input class="input" type="number" value="3">
                    </div>
                    <div class="field">
                        <span class="label">Repeticiones</span>
                        <input class="input" type="text" value="10-12">
                    </div>
                </div>
                <div class="field">
                    <span class="label">Notas técnicas</span>
                    <textarea class="input" style="height:80px;" placeholder="Ej: Pausa arriba, codos pegados..."></textarea>
                </div>
            </div>
            <div class="modal-foot">
                <button class="btn-wide" onclick="toggleModal()">Cancelar</button>
                <button class="btn-wide primary" onclick="toggleModal()">Confirmar bloque</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
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
            font-size: clamp(34px, 3vw, 54px);
            color: rgba(239, 231, 214, .90);
            text-shadow: 0 12px 40px rgba(0, 0, 0, .62);
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
            justify-content: space-between;
            gap: 10px;
            margin: 18px 6px 10px;
            color: rgba(239, 231, 214, .78);
            font-weight: 750;
            letter-spacing: .02em;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(22, 250, 22, .28);
            border: 1px solid rgba(239, 231, 214, .14);
        }

        .pill {
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .14);
            background: rgba(0, 0, 0, .14);
            color: rgba(239, 231, 214, .70);
            font-size: 11px;
            text-transform: uppercase;
        }

        .panel {
            margin: 0 6px 14px;
            border-radius: var(--r);
            padding: 22px;
            background: rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .12);
            backdrop-filter: blur(16px);
        }

        .rutina-head {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
            padding-bottom: 14px;
            margin-bottom: 14px;
        }

        .rutina-title h2 {
            margin: 0;
            font-family: var(--serif);
            font-size: 26px;
            color: var(--cream);
        }

        .rutina-title .sub {
            margin-top: 8px;
            color: rgba(239, 231, 214, .56);
            font-size: 13px;
            line-height: 1.4;
        }

        .chips {
            display: flex;
            gap: 8px;
        }

        .tag {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid rgba(239, 231, 214, .12);
        }

        .tag.green {
            background: rgba(22, 250, 22, .1);
            color: #4ade80;
        }

        .tag.bronze {
            background: rgba(190, 145, 85, .1);
            color: #d4a373;
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .metric {
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .1);
            padding: 12px;
        }

        .metric .k {
            font-size: 10px;
            color: rgba(239, 231, 214, .4);
            text-transform: uppercase;
        }

        .metric .v {
            margin-top: 6px;
            font-family: var(--serif);
            font-size: 18px;
            color: var(--cream);
        }

        .btn-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            height: 42px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .16);
            background: linear-gradient(180deg, var(--greenBtn1), var(--greenBtn2));
            color: #fff;
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
        }

        .btn-ghost {
            height: 42px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .16);
            background: transparent;
            color: var(--cream);
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
        }

        .note {
            margin-top: 15px;
            font-size: 12px;
            color: rgba(239, 231, 214, .45);
            font-style: italic;
        }

        .list {
            display: grid;
            gap: 10px;
            margin: 0 6px;
        }

        .item {
            display: grid;
            grid-template-columns: 46px 1fr 340px;
            gap: 12px;
            align-items: center;
            padding: 15px;
            border-radius: var(--r);
            border: 1px solid rgba(239, 231, 214, .1);
            background: rgba(0, 0, 0, .1);
            transition: .2s;
        }

        .item:hover {
            border-color: rgba(239, 231, 214, .2);
            background: rgba(0, 0, 0, .15);
        }

        .num {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .1);
            background: rgba(0, 0, 0, .2);
            display: grid;
            place-items: center;
            font-family: var(--serif);
            font-size: 18px;
            color: var(--cream);
        }

        .info .name {
            color: var(--cream);
            font-weight: 800;
            font-size: 14px;
        }

        .info .muscle {
            color: var(--cream-3);
            font-size: 11px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .params {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .p {
            border-radius: 10px;
            background: rgba(0, 0, 0, .2);
            padding: 8px;
            border: 1px solid rgba(239, 231, 214, .05);
        }

        .p .k {
            font-size: 9px;
            color: var(--cream-3);
            text-transform: uppercase;
        }

        .p .v {
            font-size: 12px;
            color: var(--cream);
            font-weight: 700;
            margin-top: 4px;
        }

        .item-actions {
            grid-column: 1 / -1;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 5px;
        }

        .mini {
            height: 32px;
            padding: 0 12px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .1);
            background: rgba(0, 0, 0, .15);
            color: var(--cream-2);
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .mini.green {
            color: #4ade80;
            border-color: rgba(74, 222, 128, .2);
        }

        /* IA Coach & Modal Styles similar to detailing above */
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
            background: rgba(0, 0, 0, .15);
            border: 1px solid rgba(239, 231, 214, .14);
            backdrop-filter: blur(18px);
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            backdrop-filter: blur(4px);
        }

        .modal {
            width: min(600px, 90%);
            border-radius: var(--r);
            background: #0a0a0a;
            border: 1px solid rgba(239, 231, 214, .15);
            padding: 25px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, .8);
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-head h3 {
            font-family: var(--serif);
            font-size: 22px;
            color: var(--cream);
            margin: 0;
        }

        .close {
            background: transparent;
            border: none;
            color: var(--cream-3);
            font-size: 20px;
            cursor: pointer;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 15px;
        }

        .input {
            height: 44px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(239, 231, 214, .1);
            color: #fff;
            padding: 0 12px;
        }

        .modal-foot {
            display: flex;
            gap: 12px;
            margin-top: 10px;
        }

        .btn-wide {
            flex: 1;
            height: 44px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: transparent;
            color: var(--cream);
            font-weight: 800;
        }

        .btn-wide.primary {
            background: var(--greenBtn1);
            color: #fff;
            border: none;
        }

        @media (max-width: 1100px) {
            .main {
                grid-template-columns: 1fr;
            }

            .chat {
                display: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function toggleModal() {
            const m = document.getElementById('modalAdd');
            m.style.display = (m.style.display === 'flex') ? 'none' : 'flex';
        }
    </script>
@endpush
