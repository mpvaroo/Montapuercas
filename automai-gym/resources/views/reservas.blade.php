@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
    <div class="main">
        <div class="content">
            <!-- HEADER -->
            <div class="hero-row">
                <header class="hero">
                    <h1>Reservas</h1>
                    <p>Tus clases y sesiones reservadas.</p>
                </header>
            </div>

            <!-- FILTER TABS -->
            <div class="filter-tabs" role="tablist" aria-label="Filtrar reservas">
                <a href="{{ route('reservas') }}?ver=todas"
                   class="filter-tab {{ $ver === 'todas' ? 'active' : '' }}"
                   role="tab" aria-selected="{{ $ver === 'todas' ? 'true' : 'false' }}">
                    Todas
                </a>
                <a href="{{ route('reservas') }}?ver=mis"
                   class="filter-tab filter-tab--mine {{ $ver === 'mis' ? 'active' : '' }}"
                   role="tab" aria-selected="{{ $ver === 'mis' ? 'true' : 'false' }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0;">
                        <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
                    </svg>
                    Mis Reservas
                    @if($misReservas->isNotEmpty())
                        <span class="tab-badge">{{ $misReservas->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('reservas') }}?ver=disponibles"
                   class="filter-tab {{ $ver === 'disponibles' ? 'active' : '' }}"
                   role="tab" aria-selected="{{ $ver === 'disponibles' ? 'true' : 'false' }}">
                    Disponibles
                </a>
            </div>

            <!-- MIS RESERVAS ACTIVAS -->
            @if(in_array($ver, ['todas', 'mis']))
                @if($misReservas->isNotEmpty())
                    <div class="reservation-container reservation-container--mine" style="margin-bottom: 20px;">
                        <div class="section-header">
                            <h2 class="section-title section-title--mine">
                                <span class="section-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
                                    </svg>
                                </span>
                                Mis Reservas Activas
                            </h2>
                            <span class="section-count">{{ $misReservas->count() }} {{ $misReservas->count() === 1 ? 'reserva' : 'reservas' }}</span>
                        </div>
                        <div class="res-list">
                            @foreach($misReservas as $reserva)
                                @php
                                    $clase = $reserva->clase;
                                    $imgIndex = ($clase->id_clase_gimnasio % 3) + 1;
                                    $esPasada = \Carbon\Carbon::parse($clase->fecha_inicio_clase)->isPast();
                                @endphp
                                <div class="res-card res-card--mine" style="{{ $esPasada ? 'opacity:.65;' : '' }}">
                                    <div class="res-img-box">
                                        <div class="res-tag res-tag--mine">
                                            <svg viewBox="0 0 24 24"><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/></svg>
                                        </div>
                                        <img src="{{ asset('img/reserva-' . $imgIndex . '.png') }}" class="res-img" alt="{{ $clase->titulo_clase }}">
                                        <div class="res-time">{{ \Carbon\Carbon::parse($clase->fecha_inicio_clase)->format('h:i A') }}</div>
                                    </div>
                                    <div class="res-info">
                                        <div class="mine-badge">✓ Reservado</div>
                                        <h3 class="res-title">{{ $clase->titulo_clase }}</h3>
                                        <span class="res-detail">Instructor: {{ $clase->instructor_clase }}</span>
                                        @if($reserva->origen_reserva === 'ia_coach')
                                            <span class="res-detail" style="color:rgba(107,140,110,.9); font-size:11px; text-transform:uppercase; letter-spacing:.08em;">✦ Reservado por IA Coach</span>
                                        @endif
                                        <div class="res-meta res-meta--mine">
                                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                            {{ $esPasada ? 'Clase finalizada' : 'Confirmado' }}
                                        </div>
                                    </div>
                                    <div class="res-actions">
                                        <span class="res-date">{{ \Carbon\Carbon::parse($clase->fecha_inicio_clase)->translatedFormat('l j \d\e F Y') }}</span>
                                        <div style="display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end;">
                                            <a href="{{ route('detalle-reserva', ['id' => $clase->id_clase_gimnasio]) }}" class="btn-action-sm">Ver Detalles ›</a>
                                            @if(!$esPasada)
                                                <form action="{{ route('reservas.cancelar', $clase->id_clase_gimnasio) }}" method="POST"
                                                    onsubmit="return confirm('¿Cancelar esta reserva?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-sm" style="border-color:rgba(255,100,100,.30); color:rgba(255,160,160,.92);">Cancelar</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($ver === 'mis')
                    <div style="text-align:center; padding:40px; color:rgba(239,231,214,.5);">
                        <p>No tienes ninguna reserva activa. ¡Apúntate a una clase disponible!</p>
                    </div>
                @endif
            @endif

            <!-- CLASES DISPONIBLES -->
            @if(in_array($ver, ['todas', 'disponibles']))
                <div class="reservation-container">
                    <div class="section-header">
                        <h2 class="section-title">Clases Disponibles</h2>
                        <span class="section-count">{{ $clases->count() }} {{ $clases->count() === 1 ? 'clase' : 'clases' }}</span>
                    </div>

                    <div class="res-list">
                        @forelse($clases as $clase)
                            @php
                                $reservada = in_array($clase->id_clase_gimnasio, $reservasUsuarioIds);
                                $imgIndex = ($clase->id_clase_gimnasio % 3) + 1;
                            @endphp
                            <div class="res-card">
                                <div class="res-img-box">
                                    <div class="res-tag"><svg viewBox="0 0 24 24">
                                            <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z" />
                                        </svg></div>
                                    <img src="{{ asset('img/reserva-' . $imgIndex . '.png') }}" class="res-img"
                                        alt="{{ $clase->titulo_clase }}">
                                    <div class="res-time">{{ $clase->fecha_inicio_clase->format('h:i A') }}</div>
                                </div>

                                <div class="res-info">
                                    <h3 class="res-title">{{ $clase->titulo_clase }}</h3>
                                    <span class="res-detail">Instructor: {{ $clase->instructor_clase }}</span>
                                    <span class="res-detail">{{ Str::limit($clase->descripcion_clase, 50) }}</span>
                                    <div class="res-meta" style="{{ $reservada ? '' : 'color:rgba(239,231,214,.45)' }}">
                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                        </svg>
                                        @if($reservada)
                                            Agregado a calendario
                                        @else
                                            {{ $clase->cupo_maximo_clase }} Cupos
                                        @endif
                                    </div>
                                </div>

                                <div class="res-actions">
                                    <span class="res-date">{{ $clase->fecha_inicio_clase->translatedFormat('l j \d\e F') }}</span>

                                    @if($reservada)
                                        <div style="display:flex; gap:8px;">
                                            <a href="{{ route('detalle-reserva', ['id' => $clase->id_clase_gimnasio]) }}"
                                                class="btn-action-sm">Ver Detalles ›</a>
                                            <form action="{{ route('reservas.cancelar', $clase->id_clase_gimnasio) }}" method="POST"
                                                onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-sm"
                                                    style="border-color:rgba(255,100,100,.30); color:rgba(255,160,160,.92);">Cancelar</button>
                                            </form>
                                        </div>
                                    @else
                                        <form action="{{ route('reservas.apuntar', $clase->id_clase_gimnasio) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-premium">Apuntarse</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="text-align:center; padding: 40px; color: rgba(239,231,214,.6);">
                                <p>No hay clases disponibles próximamente.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* --------------------------------------------------------------------------
                      RESERVAS PAGE
                    -------------------------------------------------------------------------- */
        .main {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            align-content: start;
            min-width: 0;
        }

        .content {
            min-width: 0;
        }

        /* Header: Título + Botón */
        .hero-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 4px;
        }

        .hero {
            margin: 0;
            min-width: 0;
        }

        .hero h1 {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            font-size: 42px;
            color: var(--cream);
            letter-spacing: .01em;
            line-height: 1.05;
        }

        .hero p {
            margin: 6px 0 0;
            color: rgba(239, 231, 214, .6);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        /* ---- Filter Tabs ---- */
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .filter-tab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, 0.12);
            background: rgba(0, 0, 0, 0.18);
            color: rgba(239, 231, 214, 0.6);
            font-size: 12px;
            font-weight: 650;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-tab:hover {
            border-color: rgba(239, 231, 214, 0.25);
            color: rgba(239, 231, 214, 0.9);
            background: rgba(255, 255, 255, 0.05);
        }

        .filter-tab.active {
            border-color: rgba(239, 231, 214, 0.30);
            color: var(--cream);
            background: rgba(255, 255, 255, 0.08);
        }

        /* Gold active for "Mis Reservas" tab */
        .filter-tab--mine.active {
            background: rgba(190, 145, 85, 0.18);
            border-color: rgba(190, 145, 85, 0.45);
            color: #d4a373;
        }

        .filter-tab--mine:hover {
            background: rgba(190, 145, 85, 0.10);
            border-color: rgba(190, 145, 85, 0.30);
            color: #d4a373;
        }

        .tab-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: rgba(190, 145, 85, 0.35);
            color: #e0bc88;
            font-size: 10px;
            font-weight: 800;
        }

        /* Section header */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .section-count {
            font-size: 11px;
            color: rgba(239, 231, 214, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* List Container */
        .reservation-container {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.10), rgba(0, 0, 0, 0.20));
            border: 1px solid rgba(239, 231, 214, 0.08);
            border-radius: 16px;
            padding: 22px;
            backdrop-filter: blur(10px);
            min-width: 0;
        }

        /* ---- Mine container: gold accent ---- */
        .reservation-container--mine {
            background: linear-gradient(180deg, rgba(190, 145, 85, 0.07), rgba(190, 145, 85, 0.03));
            border-color: rgba(190, 145, 85, 0.22);
        }

        .section-title {
            font-family: var(--serif);
            font-size: 20px;
            color: var(--cream);
            margin: 0;
        }

        .section-title--mine {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: #d4a373;
        }

        .section-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(190, 145, 85, 0.20);
            border: 1px solid rgba(190, 145, 85, 0.30);
            color: #d4a373;
            flex-shrink: 0;
        }

        /* == Mine badge inside card == */
        .mine-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            background: rgba(190, 145, 85, 0.22);
            border: 1px solid rgba(190, 145, 85, 0.40);
            color: #d4a373;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            width: fit-content;
            margin-bottom: 2px;
        }

        /* Lists items */
        .res-list {
            display: grid;
            gap: 16px;
            min-width: 0;
        }

        .res-card {
            display: grid;
            grid-template-columns: 240px 1fr 170px;
            gap: 0;
            min-height: 140px;
            background: rgba(0, 0, 0, 0.30);
            border: 1px solid rgba(239, 231, 214, 0.10);
            border-radius: 12px;
            overflow: hidden;
            transition: transform .2s, border-color .2s, background .2s;
            min-width: 0;
        }

        .res-card:hover {
            transform: translateY(-2px);
            border-color: rgba(239, 231, 214, 0.22);
            background: rgba(0, 0, 0, 0.38);
        }

        /* ---- Mine card: gold left border + tinted background ---- */
        .res-card--mine {
            border-color: rgba(190, 145, 85, 0.30);
            border-left: 3px solid rgba(190, 145, 85, 0.75);
            background: linear-gradient(90deg, rgba(190, 145, 85, 0.10) 0%, rgba(0, 0, 0, 0.28) 35%);
        }

        .res-card--mine:hover {
            border-color: rgba(190, 145, 85, 0.50);
            background: linear-gradient(90deg, rgba(190, 145, 85, 0.16) 0%, rgba(0, 0, 0, 0.35) 40%);
        }

        .res-img-box {
            position: relative;
            width: 100%;
            height: 100%;
            min-height: 140px;
        }

        .res-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            mask-image: linear-gradient(to right, black 82%, transparent 100%);
            filter: contrast(1.05) saturate(.95);
        }

        .res-time {
            position: absolute;
            bottom: 12px;
            left: 12px;
            font-family: var(--serif);
            font-size: 22px;
            color: #fff;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
        }

        .res-tag {
            position: absolute;
            top: 12px;
            left: 12px;
            width: 24px;
            height: 28px;
            background: rgba(190, 145, 85, 0.9);
            clip-path: polygon(0 0, 100% 0, 100% 85%, 50% 100%, 0 85%);
            display: grid;
            place-items: center;
        }

        .res-tag--mine {
            background: rgba(190, 145, 85, 1);
        }

        .res-tag svg {
            width: 12px;
            height: 12px;
            fill: #000;
        }

        .res-info {
            position: relative;
            min-width: 0;
            padding: 18px 18px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 6px;
            background:
                linear-gradient(90deg, rgba(0, 0, 0, 0.55) 0%, rgba(0, 0, 0, 0.25) 45%, transparent 100%);
        }

        .res-title {
            font-family: var(--serif);
            font-size: 26px;
            color: #fff;
            margin: 0;
            line-height: 1.08;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.7);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .res-detail {
            display: block;
            font-size: 14px;
            color: rgba(239, 231, 214, 0.88);
            margin: 0;
            line-height: 1.25;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.75);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .res-meta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: rgba(190, 145, 85, 1);
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 650;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.75);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .res-meta--mine {
            color: #d4a373;
        }

        .res-actions {
            min-width: 0;
            padding: 16px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-end;
            gap: 10px;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.18), rgba(0, 0, 0, 0.08));
        }

        .res-date {
            font-size: 13px;
            color: rgba(239, 231, 214, 0.80);
            letter-spacing: 0.05em;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
            white-space: nowrap;
        }

        .btn-action-sm {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid rgba(239, 231, 214, 0.15);
            background: transparent;
            color: var(--cream);
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s, border-color .2s;
            white-space: nowrap;
        }

        .btn-premium {
            padding: 9px 18px;
            border-radius: 10px;
            background: linear-gradient(135deg, #be9155 0%, #d4a373 100%);
            color: #000;
            font-size: 13px;
            font-weight: 750;
            border: none;
            cursor: pointer;
            transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            box-shadow: 0 4px 12px rgba(190, 145, 85, 0.25);
            white-space: nowrap;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(190, 145, 85, 0.4);
            filter: brightness(1.1);
        }

        .btn-premium:active {
            transform: translateY(0);
        }

        .btn-action-sm:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(239, 231, 214, 0.22);
        }

        @media (max-width: 1100px) {
            .res-card {
                grid-template-columns: 200px 1fr 170px;
            }
        }

        @media (max-width: 768px) {
            .hero-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .res-card {
                grid-template-columns: 1fr;
            }

            .res-img-box {
                height: 160px;
            }

            .res-img {
                mask-image: none;
            }

            .res-actions {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                border-top: 1px solid rgba(255, 255, 255, 0.06);
            }
        }
    </style>
@endpush