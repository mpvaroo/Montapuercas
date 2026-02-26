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
                            <div class="name">
                                {{-- Siempre mostramos el nombre real del ejercicio como título --}}
                                {{ $ejercicio->nombre_ejercicio }}
                            </div>
                            <div class="muscle">
                                {{ $ejercicio->grupo_muscular_principal
                                    ? ucfirst($ejercicio->grupo_muscular_principal)
                                    : ($routine->origen_rutina === 'ia_coach'
                                        ? 'Generado por IA'
                                        : 'Sin grupo') }}
                            </div>
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

    </div>

    <!-- Modal para editar rutina -->
    <div class="modal-backdrop" id="modalEdit">
        <div class="modal">
            <div class="modal-head">
                <div>
                    <h3>Editar datos de la rutina</h3>
                    <p>CONFIGURACIÓN GENERAL</p>
                </div>
                <button class="modal-close" onclick="toggleModal('modalEdit')">✕</button>
            </div>
            <form action="{{ route('rutina.update', $routine->id_rutina_usuario) }}" method="POST" id="editRoutineForm">
                @csrf
                <div class="modal-body">
                    @if ($errors->updateRoutine->any())
                        <div
                            style="background:rgba(255,0,0,0.1); border:1px solid rgba(255,0,0,0.2); padding:10px; border-radius:8px; margin-bottom:15px; color:#ff4d4d; font-size:12px;">
                            <ul>
                                @foreach ($errors->updateRoutine->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="field">
                        <label class="label">Nombre de la rutina</label>
                        <input class="field-input" type="text" name="nombre_rutina_usuario"
                            value="{{ $routine->nombre_rutina_usuario }}" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="field">
                            <label class="label">Objetivo</label>
                            <select class="field-input" name="objetivo_rutina_usuario" required>
                                <option value="salud"
                                    {{ $routine->objetivo_rutina_usuario == 'salud' ? 'selected' : '' }}>Salud</option>
                                <option value="definir"
                                    {{ $routine->objetivo_rutina_usuario == 'definir' ? 'selected' : '' }}>Definir</option>
                                <option value="volumen"
                                    {{ $routine->objetivo_rutina_usuario == 'volumen' ? 'selected' : '' }}>Volumen</option>
                                <option value="rendimiento"
                                    {{ $routine->objetivo_rutina_usuario == 'rendimiento' ? 'selected' : '' }}>Rendimiento
                                </option>
                            </select>
                        </div>
                        <div class="field">
                            <label class="label">Nivel</label>
                            <select class="field-input" name="nivel_rutina_usuario">
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
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="field">
                            <label class="label">Duración (min)</label>
                            <input class="field-input" type="number" name="duracion_estimada_minutos"
                                value="{{ $routine->duracion_estimada_minutos }}" min="1" max="480">
                        </div>
                        <div class="field">
                            <label class="label">Día programado</label>
                            <select class="field-input" name="dia_semana">
                                <option value="">Libre</option>
                                @foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo', 'descanso'] as $day)
                                    <option value="{{ $day }}"
                                        {{ $routine->dia_semana == $day ? 'selected' : '' }}>{{ ucfirst($day) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Instrucciones</label>
                        <textarea class="field-input" name="instrucciones_rutina" style="height:80px;">{{ $routine->instrucciones_rutina }}</textarea>
                    </div>
                </div>
            </form>
            <div class="modal-foot">
                <button type="button" class="modal-btn secondary" onclick="toggleModal('modalEdit')">CANCELAR</button>
                <button type="submit" form="editRoutineForm" class="modal-btn primary">GUARDAR CAMBIOS</button>
            </div>
        </div>
    </div>

    <!-- Modal para añadir ejercicios -->
    <div class="modal-backdrop" id="modalAdd">
        <div class="modal">
            <div class="modal-head">
                <div>
                    <h3>Añadir bloque a la rutina</h3>
                    <p>BIBLIOTECA DE EJERCICIOS</p>
                </div>
                <button class="modal-close" onclick="toggleModal('modalAdd')">✕</button>
            </div>
            <form action="{{ route('rutina.add_exercise', $routine->id_rutina_usuario) }}" method="POST"
                id="addExerciseForm">
                @csrf
                <div class="modal-body">
                    @if ($errors->addExercise->any())
                        <div
                            style="background:rgba(255,0,0,0.1); border:1px solid rgba(255,0,0,0.2); padding:10px; border-radius:8px; margin-bottom:15px; color:#ff4d4d; font-size:12px;">
                            <ul>
                                @foreach ($errors->addExercise->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="field">
                        <label class="label">Buscar ejercicio</label>
                        <select class="field-input" name="id_ejercicio" required>
                            <option value="">Selecciona un ejercicio...</option>
                            @foreach ($allExercises as $ex)
                                <option value="{{ $ex->id_ejercicio }}">{{ $ex->nombre_ejercicio }}
                                    ({{ $ex->grupo_muscular_principal }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="field">
                            <label class="label">Series</label>
                            <input class="field-input" type="number" name="series" value="3" min="1"
                                max="50" required>
                        </div>
                        <div class="field">
                            <label class="label">Repeticiones</label>
                            <input class="field-input" type="text" name="reps" placeholder="Ej: 10-12" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="field">
                            <label class="label">Carga (kg)</label>
                            <input class="field-input" type="number" name="peso" step="0.5" placeholder="0"
                                min="0" max="1000">
                        </div>
                        <div class="field">
                            <label class="label">Descanso (s)</label>
                            <input class="field-input" type="number" name="descanso" value="60" min="0"
                                max="3600">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Notas técnicas</label>
                        <textarea class="field-input" name="notas" style="height:80px;" placeholder="Ej: Pausa arriba..."></textarea>
                    </div>
                </div>
            </form>
            <div class="modal-foot">
                <button type="button" class="modal-btn secondary" onclick="toggleModal('modalAdd')">CANCELAR</button>
                <button type="submit" form="addExerciseForm" class="modal-btn primary">CONFIRMAR BLOQUE</button>
            </div>
        </div>
    </div>

    <!-- Modal para editar ejercicio individual -->
    <div class="modal-backdrop" id="modalEditEx">
        <div class="modal">
            <div class="modal-head">
                <div>
                    <h3 id="exEditTitle">Editar bloque</h3>
                    <p>AJUSTE DE PARÁMETROS</p>
                </div>
                <button class="modal-close" onclick="toggleModal('modalEditEx')">✕</button>
            </div>
            <form id="formEditEx" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->updateExercise->any())
                        <div
                            style="background:rgba(255,0,0,0.1); border:1px solid rgba(255,0,0,0.2); padding:10px; border-radius:8px; margin-bottom:15px; color:#ff4d4d; font-size:12px;">
                            <ul>
                                @foreach ($errors->updateExercise->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="field">
                            <label class="label">Series</label>
                            <input class="field-input" type="number" name="series" id="editExSeries" min="1"
                                max="50" required>
                        </div>
                        <div class="field">
                            <label class="label">Repeticiones</label>
                            <input class="field-input" type="text" name="reps" id="editExReps" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="field">
                            <label class="label">Carga (kg)</label>
                            <input class="field-input" type="number" name="peso" step="0.5" id="editExPeso"
                                min="0" max="1000">
                        </div>
                        <div class="field">
                            <label class="label">Descanso (s)</label>
                            <input class="field-input" type="number" name="descanso" id="editExDescanso"
                                min="0" max="3600">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Notas técnicas</label>
                        <textarea class="field-input" name="notas" id="editExNotas" style="height:80px;"></textarea>
                    </div>
                </div>
            </form>
            <div class="modal-foot">
                <button type="button" class="modal-btn secondary" onclick="toggleModal('modalEditEx')">CANCELAR</button>
                <button type="submit" form="formEditEx" class="modal-btn primary">GUARDAR CAMBIOS</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .main {
            display: grid;
            grid-template-columns: 1fr;
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
        @if ($errors->updateRoutine->any())
            toggleModal('modalEdit');
        @elseif ($errors->addExercise->any())
            toggleModal('modalAdd');
        @elseif ($errors->updateExercise->any())
            // Para editar ejercicio necesitamos que el form sepa el ID,
            // pero si hay error, el modal se cerró. Como no tenemos el ID fácilmente aquí post-reload
            // a menos que lo pasemos en la sesión, por ahora abrimos el modal de añadir como fallback
            // o simplemente el de editar si logramos identificar el flujo.
            toggleModal('modalEditEx');
        @endif

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
