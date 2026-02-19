@extends('layouts.app')

@section('title', 'Detalle de Registro')

@section('content')
    <style>
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
    </style>

    <div class="main-prog">
        <div class="prog-topbar">
            <div class="hero">
                <h1>Detalle</h1>
                <p>Registro del {{ $record->fecha_registro->format('d \d\e F \d\e Y') }}</p>
            </div>
            <a href="{{ route('progreso.historial') }}" class="btn-action" style="text-decoration:none;">← HISTORIAL</a>
        </div>

        <div style="padding:0 6px 10px; display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:18px;">

            {{-- Medidas --}}
            @php
                $items = [
                    [
                        'label' => 'Peso',
                        'val' => $record->peso_kg_registro,
                        'unit' => 'kg',
                        'icon' =>
                            'M12 2a7 7 0 0 0-7 7v6a7 7 0 0 0 14 0V9a7 7 0 0 0-7-7Zm0 2a5 5 0 0 1 5 5v6a5 5 0 0 1-10 0V9a5 5 0 0 1 5-5Z',
                    ],
                    [
                        'label' => 'Cintura',
                        'val' => $record->cintura_cm_registro,
                        'unit' => 'cm',
                        'icon' => 'M7 3h10v2H7V3Zm-2 4h14v14H5V7Zm2 2v10h10V9H7Z',
                    ],
                    [
                        'label' => 'Pecho',
                        'val' => $record->pecho_cm_registro,
                        'unit' => 'cm',
                        'icon' => 'M4 12h16v2H4v-2Zm2-6h12v2H6V6Zm0 10h12v2H6v-2Z',
                    ],
                    [
                        'label' => 'Cadera',
                        'val' => $record->cadera_cm_registro,
                        'unit' => 'cm',
                        'icon' => 'M6 8h12v2H6V8Zm0 4h12v2H6v-2Zm0 4h12v2H6v-2Z',
                    ],
                ];
            @endphp

            @foreach ($items as $item)
                <article class="prog-card" style="text-align:center;">
                    <div class="det-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="{{ $item['icon'] }}" />
                        </svg>
                    </div>
                    <div class="det-val">
                        {{ $item['val'] !== null ? number_format($item['val'], 2) : '—' }}
                        @if ($item['val'] !== null)
                            <span class="det-unit">{{ $item['unit'] }}</span>
                        @endif
                    </div>
                    <div class="det-label">{{ $item['label'] }}</div>
                </article>
            @endforeach

            {{-- Notas --}}
            @if ($record->notas_progreso)
                <article class="prog-card" style="grid-column:1/-1;">
                    <div class="prog-card-title">
                        <span class="prog-badge"><svg viewBox="0 0 24 24">
                                <path d="M4 4h16v10H7l-3 3V4Zm4 14h12v2H8v-2Z" />
                            </svg></span>
                        Notas del registro
                    </div>
                    <p style="margin:0;font-size:14px;color:rgba(239,231,214,.78);line-height:1.6; font-style:italic;">
                        "{{ $record->notas_progreso }}"</p>
                </article>
            @endif
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
            font-family: var(--sans);
            font-weight: 800;
            font-size: 12px;
            letter-spacing: .08em;
            cursor: pointer;
            text-decoration: none;
            transition: transform .18s, filter .18s;
            border: 1px solid rgba(239, 231, 214, .14);
            background: rgba(0, 0, 0, .18);
            color: rgba(239, 231, 214, .82);
            white-space: nowrap;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            filter: brightness(1.08);
        }

        .prog-card {
            position: relative;
            border-radius: 14px;
            padding: 24px 20px;
            background: radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .16), transparent 65%), linear-gradient(180deg, rgba(0, 0, 0, .14), rgba(0, 0, 0, .08)), rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .14);
            box-shadow: 0 18px 52px rgba(0, 0, 0, .40);
            backdrop-filter: blur(16px) saturate(112%);
            overflow: hidden;
        }

        .prog-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(239, 231, 214, .06), transparent 40%);
            opacity: .95;
            pointer-events: none;
        }

        .prog-card>* {
            position: relative;
            z-index: 1;
        }

        .prog-card-title {
            margin: 0 0 16px;
            font-family: var(--serif);
            font-weight: 500;
            color: rgba(239, 231, 214, .92);
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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
        }

        .det-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: rgba(0, 0, 0, .14);
            border: 1px solid rgba(239, 231, 214, .12);
            display: grid;
            place-items: center;
            margin: 0 auto 14px;
            color: rgba(239, 231, 214, .72);
        }

        .det-icon svg {
            width: 26px;
            height: 26px;
            fill: currentColor;
        }

        .det-val {
            font-family: var(--serif);
            font-size: 38px;
            font-weight: 500;
            color: rgba(239, 231, 214, .92);
            line-height: 1;
        }

        .det-unit {
            font-size: 14px;
            font-family: var(--sans);
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            opacity: .7;
            margin-left: 4px;
        }

        .det-label {
            margin-top: 8px;
            font-size: 12px;
            color: rgba(239, 231, 214, .50);
            text-transform: uppercase;
            letter-spacing: .12em;
        }
    </style>
@endpush
