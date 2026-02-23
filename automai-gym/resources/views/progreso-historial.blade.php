@extends('layouts.app')

@section('title', 'Historial de Progreso')

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
                <h1>Historial</h1>
                <p>Todos tus registros · registros_progreso</p>
            </div>
            <div style="display:flex; gap:10px;">
                <a href="{{ route('progreso.pdf') }}" class="btn-action primary-gold">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 16l-5-5h3V4h4v7h3l-5 5zm9-9h-6v2h4v10H5V9h4V7H3v14h18V7z" />
                    </svg>
                    GENERAR PDF
                </a>
                <a href="{{ route('progreso') }}" class="btn-action" style="text-decoration:none;">← VOLVER</a>
            </div>
        </div>

        <div style="padding:0 6px 10px;">
            <article class="prog-card">
                <div class="prog-card-title">
                    <span class="prog-badge">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M12 8a1 1 0 0 1 1 1v3.6l2.1 1.26a1 1 0 1 1-1.02 1.72l-2.6-1.56A1 1 0 0 1 11 14V9a1 1 0 0 1 1-1Zm0-6a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16Z" />
                        </svg>
                    </span>
                    Todos los registros
                </div>

                {{-- Componente Livewire con filtros --}}
                @livewire('historial-progreso')
            </article>
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

        .btn-action.primary-gold {
            background: radial-gradient(120% 160% at 30% 0%, rgba(190, 145, 85, .20), transparent 45%), rgba(0, 0, 0, .18);
            border-color: rgba(190, 145, 85, .35);
            color: #BE9155;
            box-shadow: 0 12px 32px rgba(0, 0, 0, .30);
        }

        .btn-action svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
            margin-right: 8px;
        }

        .prog-card {
            position: relative;
            border-radius: 14px;
            padding: 16px 16px 14px;
            background: radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .16), transparent 65%), linear-gradient(180deg, rgba(0, 0, 0, .14), rgba(0, 0, 0, .08)), rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .14);
            box-shadow: 0 18px 52px rgba(0, 0, 0, .40);
            backdrop-filter: blur(16px) saturate(112%);
            overflow: hidden;
            min-width: 0;
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

        /* Barra de filtros */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
            padding: 4px 0 16px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
            margin-bottom: 16px;
            position: relative;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .filter-label {
            font-size: 10px;
            color: rgba(239, 231, 214, .50);
            text-transform: uppercase;
            letter-spacing: .10em;
        }

        .filter-input {
            height: 38px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .14);
            color: rgba(239, 231, 214, .88);
            padding: 0 10px;
            outline: none;
            font-family: var(--sans);
            font-size: 13px;
            transition: border-color .18s, box-shadow .18s;
            min-width: 130px;
        }

        .filter-input:focus {
            border-color: rgba(239, 231, 214, .22);
            box-shadow: 0 0 0 3px rgba(190, 145, 85, .12);
        }

        /* Select customizado — sin appearance nativa */
        .filter-select-wrap {
            position: relative;
        }

        .filter-select-wrap::after {
            content: "";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 6px;
            background: rgba(239, 231, 214, .55);
            clip-path: polygon(0 0, 100% 0, 50% 100%);
            pointer-events: none;
        }

        .filter-select {
            appearance: none;
            -webkit-appearance: none;
            padding-right: 30px !important;
            cursor: pointer;
            min-width: 140px;
            width: 100%;
        }

        /* Opciones — background oscuro */
        .filter-select option {
            background: #0d0c0a;
            color: rgba(239, 231, 214, .88);
        }

        input[type="date"].filter-input::-webkit-calendar-picker-indicator {
            filter: invert(0.85) sepia(0.2) saturate(0.5);
            opacity: .7;
            cursor: pointer;
        }

        .filter-clear {
            height: 38px;
            padding: 0 14px;
            border-radius: 12px;
            border: 1px solid rgba(239, 100, 100, .25);
            background: rgba(200, 60, 60, .08);
            color: rgba(239, 150, 150, .80);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
            cursor: pointer;
            white-space: nowrap;
            transition: background .18s, border-color .18s;
        }

        .filter-clear:hover {
            background: rgba(200, 60, 60, .14);
            border-color: rgba(239, 100, 100, .35);
        }

        .filter-loading {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-60%);
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(239, 231, 214, .50);
            font-size: 12px;
        }

        .filter-spinner {
            width: 14px;
            height: 14px;
            border: 2px solid rgba(239, 231, 214, .20);
            border-top-color: rgba(190, 145, 85, .70);
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Tabla */
        .hist-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            min-width: 600px;
        }

        .hist-table thead th {
            font-size: 11px;
            color: rgba(239, 231, 214, .48);
            text-transform: uppercase;
            letter-spacing: .10em;
            text-align: left;
            padding: 4px 12px;
            font-weight: 600;
            font-family: var(--sans);
            transition: color .18s;
        }

        .th-sortable:hover {
            color: rgba(239, 231, 214, .72) !important;
        }

        .sort-icon {
            color: rgba(190, 145, 85, .90);
            margin-left: 4px;
            font-size: 13px;
        }

        .sort-icon-muted {
            color: rgba(239, 231, 214, .22);
            margin-left: 4px;
            font-size: 13px;
        }

        .hist-table tbody tr {
            background: rgba(0, 0, 0, .12);
            border-radius: 12px;
            transition: background .2s, transform .18s;
        }

        .hist-table tbody tr:hover {
            background: rgba(0, 0, 0, .18);
            transform: translateY(-1px);
        }

        .hist-table tbody td {
            padding: 12px 12px;
            font-size: 13px;
            color: rgba(239, 231, 214, .84);
            vertical-align: middle;
            border-top: 1px solid rgba(239, 231, 214, .08);
            border-bottom: 1px solid rgba(239, 231, 214, .08);
        }

        .hist-table tbody td:first-child {
            border-left: 1px solid rgba(239, 231, 214, .08);
            border-radius: 12px 0 0 12px;
        }

        .hist-table tbody td:last-child {
            border-right: 1px solid rgba(239, 231, 214, .08);
            border-radius: 0 12px 12px 0;
        }

        .td-fecha {
            font-family: var(--serif);
            font-size: 15px;
            color: rgba(239, 231, 214, .90) !important;
        }

        .td-nota {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: rgba(239, 231, 214, .50) !important;
            font-style: italic;
            font-size: 12px !important;
        }

        .td-btn {
            display: inline-flex;
            align-items: center;
            height: 30px;
            padding: 0 12px;
            border-radius: 8px;
            border: 1px solid rgba(239, 231, 214, .14);
            background: rgba(0, 0, 0, .14);
            color: rgba(239, 231, 214, .72);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .06em;
            text-decoration: none;
            transition: background .18s, border-color .18s;
            white-space: nowrap;
        }

        .td-btn:hover {
            background: rgba(0, 0, 0, .22);
            border-color: rgba(239, 231, 214, .22);
            color: rgba(239, 231, 214, .95);
        }

        .hist-empty {
            padding: 36px 0;
            text-align: center;
            color: rgba(239, 231, 214, .46);
            font-size: 14px;
        }

        .hist-pagination {
            margin-top: 16px;
            display: flex;
            justify-content: center;
        }

        .hist-count {
            margin-top: 10px;
            text-align: right;
            font-size: 11px;
            color: rgba(239, 231, 214, .38);
            letter-spacing: .06em;
        }

        /* Paginación de Laravel — override */
        nav[role="navigation"] svg {
            fill: rgba(239, 231, 214, .60);
        }

        nav[role="navigation"] span,
        nav[role="navigation"] a {
            color: rgba(239, 231, 214, .60) !important;
            font-size: 12px;
            border-radius: 8px;
            border-color: rgba(239, 231, 214, .12) !important;
            background: rgba(0, 0, 0, .12);
        }

        nav[role="navigation"] a:hover {
            background: rgba(0, 0, 0, .18) !important;
        }

        @media (max-width:768px) {
            .filter-bar {
                flex-direction: column;
            }

            .filter-input {
                min-width: 100%;
            }
        }
    </style>
@endpush
