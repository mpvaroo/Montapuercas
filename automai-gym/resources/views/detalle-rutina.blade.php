@extends('layouts.app')

@section('title', 'Detalle de Rutina')

@section('content')
    <div class="main">
        <div>
            <header class="hero">
                <a href="{{ route('rutinas') }}"
                    style="color:rgba(239,231,214,.5); text-decoration:none; font-size:12px; margin-bottom:8px; display:block;">&larr;
                    Volver a Rutinas</a>
                <h1>Detalle de Rutina</h1>
                <p>Constancia técnica, evolución real.</p>
            </header>

            <div class="section-title">
                <div class="left">
                    <span class="dot"></span>
                    <span>Configuración de la Rutina</span>
                </div>
                <div class="pill">{{ $routine->origen_rutina === 'ia_coach' ? 'Generada por IA' : 'Plantilla / Usuario' }}
                </div>
            </div>

            <section class="panel">
                <div class="rutina-head">
                    <div class="rutina-title">
                        <h2>{{ $routine->nombre_rutina_usuario }}</h2>
                        <div class="sub">{{ $routine->instrucciones_rutina ?? 'Sin instrucciones adicionales.' }}</div>
                    </div>
                    <div class="chips">
                        <span class="tag green">Nivel {{ ucfirst($routine->nivel_rutina_usuario) }}</span>
                        <span class="tag bronze">{{ $routine->duracion_estimada_minutos }} min aprox.</span>
                    </div>
                </div>

                <div class="metrics">
                    <div class="metric">
                        <div class="k">Frecuencia</div>
                        <div class="v">
                            {{ $routine->dia_semana ? 'Semanal (' . ucfirst($routine->dia_semana) . ')' : 'Libre' }}</div>
                    </div>
                    <div class="metric">
                        <div class="k">Intensidad</div>
                        <div class="v">Objetivo: {{ ucfirst($routine->objetivo_rutina_usuario) }}</div>
                    </div>
                    <div class="metric">
                        <div class="k">Ejercicios</div>
                        <div class="v">{{ $routine->ejercicios->count() }} bloques</div>
                    </div>
                    <div class="metric">
                        <div class="k">Origen</div>
                        <div class="v">{{ ucfirst($routine->origen_rutina) }}</div>
                    </div>
                </div>

                <div class="btn-row">
                    <button class="btn">Empezar ahora</button>
                    <button class="btn-ghost" onclick="toggleModal('modalEdit')">Editar rutina</button>
                    <button class="btn-ghost" onclick="toggleModal('modalAdd')">Añadir ejercicio</button>
                </div>
            </section>

            <div class="section-title">
                <div class="left">
                    <span class="dot"></span>
                    <span>Bloques de Entrenamiento</span>
                </div>
            </div>

            <div class="list">
                @forelse($routine->ejercicios as $index => $ejercicio)
                    <article class="item">
                        <div class="num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="info">
                            <div class="name">{{ $ejercicio->nombre_ejercicio }}</div>
                            <div class="muscle">{{ $ejercicio->grupo_muscular_principal }}</div>
                        </div>
                        <div class="params">
                            <div class="p">
                                <div class="k">Series</div>
                                <div class="v">{{ $ejercicio->pivot->series_objetivo }}</div>
                            </div>
                            <div class="p">
                                <div class="k">Reps</div>
                                <div class="v">{{ $ejercicio->pivot->repeticiones_objetivo }}</div>
                            </div>
                            <div class="p">
                                <div class="k">Carga</div>
                                <div class="v">{{ $ejercicio->pivot->peso_objetivo_kg ?? '0' }} kg</div>
                            </div>
                            <div class="p">
                                <div class="k">Descanso</div>
                                <div class="v">{{ $ejercicio->pivot->descanso_segundos ?? '60' }}s</div>
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="mini"
                                onclick="editExerciseBlock({{ json_encode($ejercicio->pivot) }}, '{{ $ejercicio->nombre_ejercicio }}', {{ $ejercicio->id_ejercicio }})">Editar
                                bloque</button>
                        </div>
                    </article>
                @empty
                    <p
                        style="color:var(--cream); padding:20px; text-align:center; background:rgba(0,0,0,0.2); border-radius:14px;">
                        Esta rutina aún no tiene ejercicios asignados.</p>
                @endforelse
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

    <!-- Modal para editar rutina -->
    <div class="modal-backdrop" id="modalEdit">
        <div class="modal">
            <div class="modal-head">
                <h3>Editar datos de la rutina</h3>
                <button class="close" onclick="toggleModal('modalEdit')">✕</button>
            </div>
            <form action="{{ route('rutina.update', $routine->id_rutina_usuario) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="field">
                        <span class="label">Nombre de la rutina</span>
                        <input class="input" type="text" name="nombre_rutina_usuario"
                            value="{{ $routine->nombre_rutina_usuario }}" required>
                    </div>
                    <div class="grid2">
                        <div class="field">
                            <span class="label">Objetivo</span>
                            <input class="input" type="text" name="objetivo_rutina_usuario"
                                value="{{ $routine->objetivo_rutina_usuario }}">
                        </div>
                        <div class="field">
                            <span class="label">Nivel</span>
                            <select class="input" name="nivel_rutina_usuario">
                                <option value="principiante"
                                    {{ $routine->nivel_rutina_usuario == 'principiante' ? 'selected' : '' }}>Principiante
                                </option>
                                <option value="intermedio"
                                    {{ $routine->nivel_rutina_usuario == 'intermedio' ? 'selected' : '' }}>Intermedio
                                </option>
                                <option value="avanzado"
                                    {{ $routine->nivel_rutina_usuario == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid2">
                        <div class="field">
                            <span class="label">Duración (min)</span>
                            <input class="input" type="number" name="duracion_estimada_minutos"
                                value="{{ $routine->duracion_estimada_minutos }}">
                        </div>
                        <div class="field">
                            <span class="label">Día programado</span>
                            <select class="input" name="dia_semana">
                                <option value="">Libre</option>
                                @foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo', 'descanso'] as $day)
                                    <option value="{{ $day }}"
                                        {{ $routine->dia_semana == $day ? 'selected' : '' }}>{{ ucfirst($day) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <span class="label">Instrucciones</span>
                        <textarea class="input" name="instrucciones_rutina" style="height:80px;">{{ $routine->instrucciones_rutina }}</textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-wide" onclick="toggleModal('modalEdit')">Cancelar</button>
                    <button type="submit" class="btn-wide primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para añadir ejercicios -->
    <div class="modal-backdrop" id="modalAdd">
        <div class="modal">
            <div class="modal-head">
                <h3>Añadir bloque a la rutina</h3>
                <button class="close" onclick="toggleModal('modalAdd')">✕</button>
            </div>
            <form action="{{ route('rutina.add_exercise', $routine->id_rutina_usuario) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="field">
                        <span class="label">Buscar ejercicio</span>
                        <select class="input" name="id_ejercicio" required>
                            <option value="">Selecciona un ejercicio...</option>
                            @foreach ($allExercises as $ex)
                                <option value="{{ $ex->id_ejercicio }}">{{ $ex->nombre_ejercicio }}
                                    ({{ $ex->grupo_muscular_principal }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid2">
                        <div class="field">
                            <span class="label">Series</span>
                            <input class="input" type="number" name="series" value="3">
                        </div>
                        <div class="field">
                            <span class="label">Repeticiones</span>
                            <input class="input" type="text" name="reps" placeholder="Ej: 10-12">
                        </div>
                    </div>
                    <div class="grid2">
                        <div class="field">
                            <span class="label">Carga (kg)</span>
                            <input class="input" type="number" name="peso" step="0.5" placeholder="0">
                        </div>
                        <div class="field">
                            <span class="label">Descanso (s)</span>
                            <input class="input" type="number" name="descanso" value="60">
                        </div>
                    </div>
                    <div class="field">
                        <span class="label">Notas técnicas</span>
                        <textarea class="input" name="notas" style="height:80px;" placeholder="Ej: Pausa arriba..."></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-wide" onclick="toggleModal('modalAdd')">Cancelar</button>
                    <button type="submit" class="btn-wide primary">Confirmar bloque</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para editar ejercicio individual -->
    <div class="modal-backdrop" id="modalEditEx">
        <div class="modal">
            <div class="modal-head">
                <h3 id="exEditTitle">Editar bloque</h3>
                <button class="close" onclick="toggleModal('modalEditEx')">✕</button>
            </div>
            <form id="formEditEx" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="grid2">
                        <div class="field">
                            <span class="label">Series</span>
                            <input class="input" type="number" name="series" id="editExSeries">
                        </div>
                        <div class="field">
                            <span class="label">Repeticiones</span>
                            <input class="input" type="text" name="reps" id="editExReps">
                        </div>
                    </div>
                    <div class="grid2">
                        <div class="field">
                            <span class="label">Carga (kg)</span>
                            <input class="input" type="number" name="peso" step="0.5" id="editExPeso">
                        </div>
                        <div class="field">
                            <span class="label">Descanso (s)</span>
                            <input class="input" type="number" name="descanso" id="editExDescanso">
                        </div>
                    </div>
                    <div class="field">
                        <span class="label">Notas técnicas</span>
                        <textarea class="input" name="notas" id="editExNotas" style="height:80px;"></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-wide" onclick="toggleModal('modalEditEx')">Cancelar</button>
                    <button type="submit" class="btn-wide primary">Guardar cambios</button>
                </div>
            </form>
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
            width: fit-content;
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
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid rgba(239, 231, 214, .12);
            width: fit-content;
            display: inline-flex;
            align-items: center;
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
            background: rgba(0, 0, 0, .4);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            backdrop-filter: blur(14px);
            padding: 20px;
        }

        .modal {
            width: min(500px, 100%);
            border-radius: 24px;
            background: rgba(15, 15, 15, .85);
            border: 1px solid rgba(239, 231, 214, .15);
            padding: 30px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, .8);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
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
            font-size: 22px;
            cursor: pointer;
            padding: 5px;
            line-height: 1;
        }

        .modal-body {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .field .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: rgba(239, 231, 214, .5);
            font-weight: 700;
            margin-bottom: 8px;
            display: block;
        }

        .input {
            width: 100%;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .03);
            border: 1px solid rgba(239, 231, 214, .1);
            color: #fff;
            padding: 0 14px;
            font-family: inherit;
            font-size: 14px;
        }

        .input:focus {
            outline: none;
            border-color: rgba(239, 231, 214, .3);
            background: rgba(255, 255, 255, .05);
        }

        select.input option {
            background: #111;
            color: #fff;
        }

        .modal-foot {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn-wide {
            flex: 1;
            height: 46px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: transparent;
            color: var(--cream);
            font-weight: 800;
            cursor: pointer;
            transition: .2s;
        }

        .btn-wide:hover {
            background: rgba(255, 255, 255, .05);
        }

        .btn-wide.primary {
            background: var(--greenBtn1);
            color: #fff;
            border: none;
        }

        .btn-wide.primary:hover {
            filter: brightness(1.1);
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
        function toggleModal(id) {
            const m = document.getElementById(id);
            if (!m) return;
            m.style.display = (m.style.display === 'flex') ? 'none' : 'flex';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal-backdrop')) {
                event.target.style.display = 'none';
            }
        }

        function editExerciseBlock(pivot, name, exId) {
            document.getElementById('exEditTitle').innerText = 'Editar: ' + name;
            document.getElementById('editExSeries').value = pivot.series_objetivo;
            document.getElementById('editExReps').value = pivot.repeticiones_objetivo;
            document.getElementById('editExPeso').value = pivot.peso_objetivo_kg;
            document.getElementById('editExDescanso').value = pivot.descanso_segundos;
            document.getElementById('editExNotas').value = pivot.notas_ejercicio;

            const form = document.getElementById('formEditEx');
            form.action = `/detalle-rutina/{{ $routine->id_rutina_usuario }}/exercise/${exId}/update`;

            toggleModal('modalEditEx');
        }
    </script>
@endpush
