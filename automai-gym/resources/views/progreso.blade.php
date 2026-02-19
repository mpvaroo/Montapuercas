@extends('layouts.app')

@section('title', 'Progreso')

@section('content')
    <style>
        /* LAYOUT CRÍTICO PROGRESO */
        div.main-prog {
            display: flex !important;
            flex-direction: column !important;
            gap: 18px !important;
            min-width: 0 !important;
        }

        div.prog-topbar {
            display: flex !important;
            justify-content: space-between !important;
            align-items: flex-end !important;
            gap: 16px !important;
            padding: 10px 6px 0 !important;
        }

        div.prog-layout {
            display: flex !important;
            flex-direction: row !important;
            gap: 22px !important;
            align-items: flex-start !important;
            padding: 0 6px 10px !important;
        }

        div.prog-col {
            flex: 1.9 1 0 !important;
            min-width: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 18px !important;
        }

        div.prog-panel {
            flex: 0 0 320px !important;
            width: 320px !important;
            position: sticky !important;
            top: 28px !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 18px !important;
        }

        /* ── Gráfico Livewire: selector de métrica ── */
        .gc-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .gc-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--serif);
            font-weight: 400;
            color: rgba(239, 231, 214, .82);
            font-size: 18px;
        }

        .gc-title strong {
            font-weight: 600;
            color: rgba(239, 231, 214, .95);
        }

        .gc-tabs {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .gc-tab {
            height: 32px;
            padding: 0 14px;
            border-radius: 10px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .62);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .07em;
            cursor: pointer;
            transition: background .18s, border-color .18s, color .18s, transform .18s;
            user-select: none;
            white-space: nowrap;
        }

        .gc-tab:hover {
            background: rgba(0, 0, 0, .18);
            border-color: rgba(239, 231, 214, .18);
            color: rgba(239, 231, 214, .82);
            transform: translateY(-1px);
        }

        .gc-tab--active {
            background: radial-gradient(120% 160% at 30% 0%, rgba(190, 145, 85, .22), transparent 55%), rgba(0, 0, 0, .18);
            border-color: rgba(190, 145, 85, .40);
            color: rgba(239, 231, 214, .95);
            box-shadow: 0 4px 16px rgba(190, 145, 85, .18);
        }
    </style>

    {{-- Toast éxito --}}
    @if (session('success'))
        <div id="successToast"
            style="position:fixed;right:22px;bottom:22px;z-index:200;width:min(440px,calc(100% - 44px));border-radius:14px;padding:12px 16px;border:1px solid rgba(239,231,214,.14);background:radial-gradient(140% 120% at 18% 10%,rgba(190,145,85,.14),transparent 65%),rgba(0,0,0,.24);color:rgba(239,231,214,.86);box-shadow:0 18px 52px rgba(0,0,0,.55);font-size:13px;">
            <b style="font-family:var(--serif);font-weight:500;">Guardado</b>
            <div style="margin-top:4px;color:rgba(239,231,214,.74);">{{ session('success') }}</div>
        </div>
        <script>
            setTimeout(() => {
                const t = document.getElementById('successToast');
                if (t) t.style.display = 'none';
            }, 3000);
        </script>
    @endif

    <div class="main-prog">
        {{-- Topbar --}}
        <div class="prog-topbar">
            <div class="hero">
                <h1>Progreso</h1>
                <p>Registros y evolución · registros_progreso</p>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;">
                <button class="btn-action primary" type="button" id="openModal">AÑADIR REGISTRO</button>
            </div>
        </div>

        <div class="prog-layout">

            {{-- COLUMNA IZQUIERDA --}}
            <div class="prog-col">

                {{-- Card: Último Registro --}}
                <article class="prog-card">
                    <div class="prog-card-title">
                        <span class="prog-badge">
                            <svg viewBox="0 0 24 24">
                                <path d="M7 4h10v2H7V4Zm-2 4h14v12H5V8Zm2 2v8h10v-8H7Z" />
                            </svg>
                        </span>
                        Último registro
                    </div>

                    @if ($lastRecord)
                        <div class="prog-subline">FECHA: {{ $lastRecord->fecha_registro->format('Y-m-d') }}</div>
                        <div class="measure-grid">
                            @php
                                $measures = [
                                    [
                                        'key' => 'peso_kg_registro',
                                        'val' => $lastRecord->peso_kg_registro,
                                        'unit' => 'kg',
                                        'icon' =>
                                            'M12 2a7 7 0 0 0-7 7v6a7 7 0 0 0 14 0V9a7 7 0 0 0-7-7Zm0 2a5 5 0 0 1 5 5v6a5 5 0 0 1-10 0V9a5 5 0 0 1 5-5Z',
                                    ],
                                    [
                                        'key' => 'cintura_cm_registro',
                                        'val' => $lastRecord->cintura_cm_registro,
                                        'unit' => 'cm',
                                        'icon' => 'M7 3h10v2H7V3Zm-2 4h14v14H5V7Zm2 2v10h10V9H7Z',
                                    ],
                                    [
                                        'key' => 'pecho_cm_registro',
                                        'val' => $lastRecord->pecho_cm_registro,
                                        'unit' => 'cm',
                                        'icon' => 'M4 12h16v2H4v-2Zm2-6h12v2H6V6Zm0 10h12v2H6v-2Z',
                                    ],
                                    [
                                        'key' => 'cadera_cm_registro',
                                        'val' => $lastRecord->cadera_cm_registro,
                                        'unit' => 'cm',
                                        'icon' => 'M6 8h12v2H6V8Zm0 4h12v2H6v-2Zm0 4h12v2H6v-2Z',
                                    ],
                                ];
                            @endphp
                            @foreach ($measures as $m)
                                <div class="measure">
                                    <div class="measure-top">
                                        <div class="measure-val">
                                            {{ $m['val'] !== null ? number_format($m['val'], 2) : '—' }}
                                            @if ($m['val'] !== null)
                                                <span class="unit">{{ $m['unit'] }}</span>
                                            @endif
                                        </div>
                                        <div class="mini"><svg viewBox="0 0 24 24">
                                                <path d="{{ $m['icon'] }}" />
                                            </svg></div>
                                    </div>
                                    <div class="measure-label">{{ $m['key'] }}</div>
                                </div>
                            @endforeach
                        </div>
                        @if ($lastRecord->notas_progreso)
                            <div class="prog-note">Notas: "{{ $lastRecord->notas_progreso }}"</div>
                        @endif
                    @else
                        <div class="prog-subline">Sin registros aún</div>
                        <div class="measure-grid">
                            @foreach (['peso_kg_registro', 'cintura_cm_registro', 'pecho_cm_registro', 'cadera_cm_registro'] as $f)
                                <div class="measure">
                                    <div class="measure-top">
                                        <div class="measure-val">—</div>
                                    </div>
                                    <div class="measure-label">{{ $f }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="prog-note">Añade tu primer registro con el botón de arriba.</div>
                    @endif
                </article>

                {{-- Gráfico: métrica seleccionable con Livewire --}}
                <article class="prog-card">
                    @livewire('grafico-progreso')
                </article>
            </div>

            {{-- COLUMNA DERECHA --}}
            <div class="prog-panel" id="historyAnchor">

                {{-- Historial (últimos 6) --}}
                <article class="prog-card">
                    <div class="prog-card-title">
                        <span class="prog-badge">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12 8a1 1 0 0 1 1 1v3.6l2.1 1.26a1 1 0 1 1-1.02 1.72l-2.6-1.56A1 1 0 0 1 11 14V9a1 1 0 0 1 1-1Zm0-6a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Z" />
                            </svg>
                        </span>
                        Historial
                    </div>
                    <div class="hist-list">
                        @forelse($history as $record)
                            @php
                                $parts = [];
                                if ($record->peso_kg_registro !== null) {
                                    $parts[] = 'Peso ' . number_format($record->peso_kg_registro, 2);
                                }
                                if ($record->cintura_cm_registro !== null) {
                                    $parts[] = 'Cintura ' . number_format($record->cintura_cm_registro, 2);
                                }
                                if ($record->pecho_cm_registro !== null) {
                                    $parts[] = 'Pecho ' . number_format($record->pecho_cm_registro, 2);
                                }
                                $meta = count($parts) ? implode(' · ', $parts) : '—';
                            @endphp
                            <a class="hist-row" href="{{ route('progreso.detalle', $record->id_registro_progreso) }}"
                                style="text-decoration:none;">
                                <div class="hist-left">
                                    <span class="prog-badge"><svg viewBox="0 0 24 24">
                                            <path
                                                d="M12 8a1 1 0 0 1 1 1v3.6l2.1 1.26a1 1 0 1 1-1.02 1.72l-2.6-1.56A1 1 0 0 1 11 14V9a1 1 0 0 1 1-1Zm0-6a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Z" />
                                        </svg></span>
                                    <div style="min-width:0;">
                                        <p class="hist-label">{{ $record->fecha_registro->format('Y-m-d') }}</p>
                                        <div class="hist-meta">{{ $meta }}</div>
                                    </div>
                                </div>
                                <div class="hist-right">ABRIR</div>
                            </a>
                        @empty
                            <div class="hist-row" style="cursor:default;">
                                <div class="hist-left">
                                    <div>
                                        <p class="hist-label">Sin registros</p>
                                        <div class="hist-meta">Aún no hay datos</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <a href="{{ route('progreso.historial') }}" class="btn-full"
                        style="display:flex;align-items:center;justify-content:center;text-decoration:none;">VER TODOS</a>
                </article>
            </div>
        </div>
    </div>

    {{-- MODAL: Añadir Registro --}}
    <div class="modal-backdrop" id="modalBackdrop" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-head">
                <div>
                    <h3>Añadir registro</h3>
                    <p>REGISTROS_PROGRESO</p>
                </div>
                <button class="modal-close" type="button" id="closeModal" aria-label="Cerrar">✕</button>
            </div>
            <form class="modal-body" id="progressForm" action="{{ route('progreso.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label for="fecha_registro">Fecha</label>
                        <input class="field-input" id="fecha_registro" name="fecha_registro" type="date" required
                            min="{{ auth()->user()->fecha_creacion_usuario instanceof \Carbon\Carbon ? auth()->user()->fecha_creacion_usuario->format('Y-m-d') : date('Y-m-d', strtotime(auth()->user()->fecha_creacion_usuario)) }}" />
                    </div>
                    <div class="field">
                        <label for="peso_kg_registro">Peso (kg)</label>
                        <input class="field-input" id="peso_kg_registro" name="peso_kg_registro" type="number"
                            step="0.01" min="0.01" max="999.99" placeholder="Ej: 78.40" />
                    </div>
                    <div class="field">
                        <label for="cintura_cm_registro">Cintura (cm)</label>
                        <input class="field-input" id="cintura_cm_registro" name="cintura_cm_registro" type="number"
                            step="0.01" min="0.01" max="999.99" placeholder="Ej: 83.00" />
                    </div>
                    <div class="field">
                        <label for="pecho_cm_registro">Pecho (cm)</label>
                        <input class="field-input" id="pecho_cm_registro" name="pecho_cm_registro" type="number"
                            step="0.01" min="0.01" max="999.99" placeholder="Ej: 101.00" />
                    </div>
                    <div class="field">
                        <label for="cadera_cm_registro">Cadera (cm)</label>
                        <input class="field-input" id="cadera_cm_registro" name="cadera_cm_registro" type="number"
                            step="0.01" min="0.01" max="999.99" placeholder="Ej: 96.00" />
                    </div>
                    <div class="field" style="grid-column:1/-1;">
                        <label for="notas_progreso">Notas (opcional)</label>
                        <textarea class="field-input" id="notas_progreso" name="notas_progreso"
                            placeholder="Notas opcionales (máx. 220 caracteres)" maxlength="220"></textarea>
                    </div>
                </div>
            </form>
            <div class="modal-foot">
                <button class="modal-btn secondary" type="button" id="cancelBtn">CANCELAR</button>
                <button class="modal-btn primary" type="submit" form="progressForm">GUARDAR</button>
            </div>
        </div>
    </div>

    {{-- MODAL: Nota detalle --}}
    <div class="modal-backdrop" id="notaBackdrop" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" style="width:min(520px,100%);">
            <div class="modal-head">
                <div>
                    <h3 id="notaModalTitle">Registro</h3>
                    <p>DETALLE</p>
                </div>
                <button class="modal-close" type="button" onclick="closeNotaModal()">✕</button>
            </div>
            <div class="modal-body" style="gap:16px;">
                <div class="nota-table">
                    <div class="nota-row-item"><span class="nota-key">Peso</span><span class="nota-val"
                            id="nPeso">—</span></div>
                    <div class="nota-row-item"><span class="nota-key">Cintura</span><span class="nota-val"
                            id="nCintura">—</span></div>
                    <div class="nota-row-item"><span class="nota-key">Pecho</span><span class="nota-val"
                            id="nPecho">—</span></div>
                    <div class="nota-row-item"><span class="nota-key">Cadera</span><span class="nota-val"
                            id="nCadera">—</span></div>
                </div>
                <div id="notaText"
                    style="background:rgba(0,0,0,.12);border:1px solid rgba(239,231,214,.10);border-radius:12px;padding:12px 14px;font-size:13px;color:rgba(239,231,214,.78);line-height:1.5;">
                </div>
            </div>
            <div class="modal-foot" style="grid-template-columns:1fr;">
                <button class="modal-btn secondary" onclick="closeNotaModal()">CERRAR</button>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
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

        .btn-action {
            height: 40px;
            padding: 0 18px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: var(--sans);
            font-weight: 800;
            font-size: 12px;
            letter-spacing: .08em;
            cursor: pointer;
            text-decoration: none;
            transition: transform .18s, filter .18s, border-color .18s;
            border: 1px solid rgba(239, 231, 214, .14);
            background: rgba(0, 0, 0, .18);
            color: rgba(239, 231, 214, .82);
            white-space: nowrap;
            user-select: none;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            filter: brightness(1.08);
            border-color: rgba(239, 231, 214, .18);
        }

        .btn-action.primary {
            background: radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, .18), transparent 35%), linear-gradient(180deg, rgba(70, 98, 72, .92), rgba(43, 70, 43, .98));
            border-color: rgba(239, 231, 214, .16);
            color: rgba(239, 231, 214, .95);
            box-shadow: 0 14px 38px rgba(0, 0, 0, .36);
        }

        /* Card */
        .prog-card {
            position: relative;
            border-radius: 14px;
            padding: 16px 16px 14px;
            background: radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .16), transparent 65%), linear-gradient(180deg, rgba(0, 0, 0, .14), rgba(0, 0, 0, .08)), rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .14);
            box-shadow: 0 18px 52px rgba(0, 0, 0, .40);
            backdrop-filter: blur(16px) saturate(112%);
            -webkit-backdrop-filter: blur(16px) saturate(112%);
            overflow: hidden;
            min-width: 0;
        }

        .prog-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(239, 231, 214, .06), transparent 40%, rgba(239, 231, 214, .04)), radial-gradient(140% 120% at 50% 15%, rgba(0, 0, 0, 0), rgba(0, 0, 0, .18));
            opacity: .95;
            pointer-events: none;
        }

        .prog-card>* {
            position: relative;
            z-index: 1;
        }

        .prog-card-title {
            margin: 0 0 12px;
            font-family: var(--serif);
            font-weight: 500;
            letter-spacing: .01em;
            color: rgba(239, 231, 214, .92);
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .prog-badge {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .14);
            background: radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, .10), transparent 35%), rgba(0, 0, 0, .14);
            display: grid;
            place-items: center;
            color: rgba(239, 231, 214, .86);
            flex: 0 0 34px;
        }

        .prog-badge svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
            opacity: .95;
        }

        .prog-subline {
            margin-top: -6px;
            margin-bottom: 14px;
            color: rgba(239, 231, 214, .56);
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .prog-note {
            margin-top: 12px;
            color: rgba(239, 231, 214, .56);
            font-size: 12.5px;
            letter-spacing: .02em;
            line-height: 1.4;
        }

        /* Medidas */
        .measure-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .measure {
            padding: 12px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .12);
            min-width: 0;
        }

        .measure-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .measure-val {
            font-family: var(--serif);
            font-size: 22px;
            font-weight: 500;
            color: rgba(239, 231, 214, .92);
            letter-spacing: .01em;
            line-height: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .unit {
            font-size: 11px;
            opacity: .75;
            margin-left: 3px;
            font-family: var(--sans);
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .measure-label {
            font-size: 10px;
            color: rgba(239, 231, 214, .50);
            text-transform: uppercase;
            letter-spacing: .10em;
            margin-top: 6px;
        }

        .mini {
            width: 28px;
            height: 28px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(239, 231, 214, .10);
            color: rgba(239, 231, 214, .84);
            flex: 0 0 28px;
        }

        .mini svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
            opacity: .95;
        }

        /* Gráfico */
        .chart-wrap {
            margin-top: 12px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .10);
            padding: 14px 14px 10px;
            overflow: hidden;
        }

        .chart-grid {
            position: relative;
            height: 220px;
            min-width: 0;
        }

        .y-axis {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 26px;
            width: 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 10px;
            color: rgba(239, 231, 214, .22);
            pointer-events: none;
        }

        .grid-lines {
            position: absolute;
            left: 44px;
            right: 0;
            top: 0;
            bottom: 26px;
            pointer-events: none;
        }

        .grid-lines span {
            position: absolute;
            left: 0;
            right: 0;
            border-top: 1px dashed rgba(239, 231, 214, .08);
        }

        .grid-lines span:nth-child(1) {
            top: 15%
        }

        .grid-lines span:nth-child(2) {
            top: 45%
        }

        .grid-lines span:nth-child(3) {
            top: 75%
        }

        .chart-svg {
            position: absolute;
            left: 44px;
            right: 0;
            top: 0;
            bottom: 26px;
            width: calc(100% - 44px);
            height: calc(100% - 26px);
            pointer-events: none;
        }

        .x-axis {
            position: absolute;
            left: 44px;
            right: 0;
            bottom: 0;
            height: 26px;
            display: grid;
            align-items: end;
            color: rgba(239, 231, 214, .45);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .x-axis span {
            display: flex;
            justify-content: center;
            padding-bottom: 2px;
        }

        /* Historial / notas */
        .hist-list {
            display: grid;
            gap: 10px;
        }

        .hist-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .10);
            transition: background .2s, border-color .2s, transform .2s;
            min-width: 0;
        }

        .hist-row:hover {
            background: rgba(0, 0, 0, .14);
            border-color: rgba(239, 231, 214, .16);
            transform: translateY(-1px);
        }

        .hist-left {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            min-width: 0;
        }

        .hist-label {
            color: rgba(239, 231, 214, .92);
            font-size: 13px;
            line-height: 1.2;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hist-meta {
            color: rgba(239, 231, 214, .42);
            font-size: 11px;
            margin-top: 2px;
            letter-spacing: .02em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 190px;
        }

        .hist-right {
            color: rgba(239, 231, 214, .72);
            font-size: 12px;
            white-space: nowrap;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .btn-full {
            width: 100%;
            margin-top: 12px;
            height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .14);
            color: rgba(239, 231, 214, .78);
            font-weight: 800;
            letter-spacing: .08em;
            font-size: 12px;
            cursor: pointer;
            transition: background .2s, transform .18s, border-color .2s;
        }

        .btn-full:hover {
            background: rgba(0, 0, 0, .18);
            border-color: rgba(239, 231, 214, .18);
            transform: translateY(-1px);
        }

        /* Modal */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .62);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99;
            padding: 20px;
        }

        .modal {
            width: min(720px, 100%);
            border-radius: 16px;
            border: 1px solid rgba(239, 231, 214, .16);
            background: radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .18), transparent 60%), linear-gradient(180deg, rgba(0, 0, 0, .22), rgba(0, 0, 0, .16)), rgba(0, 0, 0, .22);
            box-shadow: 0 34px 110px rgba(56, 52, 32, .72);
            overflow: hidden;
        }

        .modal-head {
            padding: 14px 14px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
        }

        .modal-head h3 {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            color: rgba(239, 231, 214, .92);
            letter-spacing: .01em;
            font-size: 22px;
        }

        .modal-head p {
            margin: 2px 0 0;
            color: rgba(239, 231, 214, .56);
            font-size: 12px;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .modal-close {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .74);
            cursor: pointer;
            transition: transform .18s, border-color .18s;
            display: grid;
            place-items: center;
            user-select: none;
            font-size: 16px;
        }

        .modal-close:hover {
            transform: translateY(-1px);
            border-color: rgba(239, 231, 214, .18);
        }

        .modal-body {
            padding: 14px;
            display: grid;
            gap: 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field label {
            font-size: 11px;
            color: rgba(239, 231, 214, .58);
            text-transform: uppercase;
            letter-spacing: .10em;
        }

        .field-input {
            height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .92);
            padding: 0 12px;
            outline: none;
            font-family: var(--sans);
            font-size: 13px;
            transition: border-color .18s, box-shadow .18s;
            width: 100%;
        }

        textarea.field-input {
            height: 96px;
            padding: 10px 12px;
            resize: vertical;
        }

        .field-input::placeholder {
            color: rgba(239, 231, 214, .52);
        }

        .field-input:focus {
            border-color: rgba(239, 231, 214, .20);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .10);
        }

        /* Icono del calendario en color claro */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.85) sepia(0.2) saturate(0.5);
            opacity: .75;
            cursor: pointer;
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        .modal-foot {
            padding: 14px;
            border-top: 1px solid rgba(239, 231, 214, .10);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .modal-btn {
            height: 44px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .16);
            cursor: pointer;
            font-family: var(--sans);
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-size: 12px;
            display: inline-grid;
            place-items: center;
            transition: transform .18s, filter .18s, border-color .18s;
            user-select: none;
        }

        .modal-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
            border-color: rgba(239, 231, 214, .22);
        }

        .modal-btn.secondary {
            background: rgba(0, 0, 0, .16);
            color: rgba(239, 231, 214, .82);
        }

        .modal-btn.primary {
            background: radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, .18), transparent 35%), linear-gradient(180deg, rgba(70, 98, 72, .92), rgba(43, 70, 43, .98));
            color: rgba(239, 231, 214, .95);
            box-shadow: 0 18px 52px rgba(0, 0, 0, .50);
        }

        /* Mini tabla nota */
        .nota-table {
            display: grid;
            gap: 8px;
        }

        .nota-row-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .12);
        }

        .nota-key {
            font-size: 11px;
            color: rgba(239, 231, 214, .56);
            text-transform: uppercase;
            letter-spacing: .10em;
        }

        .nota-val {
            font-family: var(--serif);
            font-size: 18px;
            color: rgba(239, 231, 214, .92);
        }

        @media (max-width:1180px) {
            .prog-layout {
                flex-direction: column !important;
            }

            .prog-panel {
                position: relative !important;
                top: 0 !important;
                width: 100% !important;
                flex: none !important;
            }

            .measure-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width:768px) {
            .measure-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        const chartWeights = @json($chartWeights);
        const chartLabels = @json($chartLabels);

        // --- Modal registro ---
        const openModalBtn = document.getElementById('openModal');
        const closeModalBtn = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const modalBackdrop = document.getElementById('modalBackdrop');

        function openModal() {
            modalBackdrop.style.display = 'flex';
            const inputDate = document.getElementById('fecha_registro');
            if (inputDate && !inputDate.value) inputDate.value = new Date().toISOString().split('T')[0];
            setTimeout(() => document.getElementById('peso_kg_registro')?.focus(), 50);
        }

        function closeModal() {
            modalBackdrop.style.display = 'none';
        }

        openModalBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        modalBackdrop.addEventListener('click', e => {
            if (e.target === modalBackdrop) closeModal();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && modalBackdrop.style.display === 'flex') closeModal();
        });

        // --- Modal nota ---
        const notaBackdrop = document.getElementById('notaBackdrop');

        function openNotaModal(el) {
            const fmt = v => v && v !== 'null' ? parseFloat(v).toFixed(2) : '—';
            document.getElementById('notaModalTitle').textContent = 'Registro ' + el.dataset.fecha;
            document.getElementById('nPeso').textContent = fmt(el.dataset.peso) + (el.dataset.peso && el.dataset.peso !==
                'null' ? ' kg' : '');
            document.getElementById('nCintura').textContent = fmt(el.dataset.cintura) + (el.dataset.cintura && el.dataset
                .cintura !== 'null' ? ' cm' : '');
            document.getElementById('nPecho').textContent = fmt(el.dataset.pecho) + (el.dataset.pecho && el.dataset
                .pecho !== 'null' ? ' cm' : '');
            document.getElementById('nCadera').textContent = fmt(el.dataset.cadera) + (el.dataset.cadera && el.dataset
                .cadera !== 'null' ? ' cm' : '');
            document.getElementById('notaText').textContent = el.dataset.nota ? '"' + el.dataset.nota + '"' : 'Sin notas.';
            notaBackdrop.style.display = 'flex';
        }

        function closeNotaModal() {
            notaBackdrop.style.display = 'none';
        }
        notaBackdrop.addEventListener('click', e => {
            if (e.target === notaBackdrop) closeNotaModal();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && notaBackdrop.style.display === 'flex') closeNotaModal();
        });
    </script>
@endpush
