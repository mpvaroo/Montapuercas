@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
    <div class="main">
        <div class="content">
            <!-- HEADER FIX -->
            <div class="hero-row">
                <header class="hero">
                    <h1>Reservas</h1>
                    <p>Tus clases y sesiones reservadas.</p>
                </header>

                <a href="{{ route('calendario') }}" class="btn-new"
                    style="display:flex; align-items:center; justify-content:center; text-decoration:none;">+ Nueva
                    Reserva</a>
            </div>

            <!-- LISTA RESERVAS -->
            <div class="reservation-container">
                <h2 class="section-title">Tu Próxima Reserva</h2>

                <div class="res-list">
                    <!-- Item 1: Spinning -->
                    <div class="res-card">
                        <div class="res-img-box">
                            <div class="res-tag"><svg viewBox="0 0 24 24">
                                    <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z" />
                                </svg></div>
                            <img src="{{ asset('img/reserva-1.png') }}" class="res-img" alt="Spinning">
                            <div class="res-time">08:00 AM</div>
                        </div>

                        <div class="res-info">
                            <h3 class="res-title">Spinning</h3>
                            <span class="res-detail">Instructora: Laura</span>
                            <span class="res-detail">Clase de ciclismo de alta intensidad</span>
                            <div class="res-meta">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                Agregado a calendario
                            </div>
                        </div>

                        <div class="res-actions">
                            <span class="res-date">Jueves 25 de Enero</span>
                            <a href="{{ route('detalle-reserva') }}" class="btn-action-sm">Ver Detalles ›</a>
                        </div>
                    </div>

                    <!-- Item 2: HIIT -->
                    <div class="res-card">
                        <div class="res-img-box">
                            <div class="res-tag"><svg viewBox="0 0 24 24">
                                    <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z" />
                                </svg></div>
                            <img src="{{ asset('img/reserva-2.png') }}" class="res-img" alt="HIIT">
                            <div class="res-time">11:00 AM</div>
                        </div>

                        <div class="res-info">
                            <h3 class="res-title">HIIT Express</h3>
                            <span class="res-detail">Instructora: Rocío</span>
                            <span class="res-detail">Rutina de HIIT, ritmo intenso</span>
                            <div class="res-meta" style="color:rgba(239,231,214,.45)">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                                8 Asistentes
                            </div>
                        </div>

                        <div class="res-actions">
                            <span class="res-date">Jueves 25 de Enero</span>
                            <button class="btn-action-sm"
                                style="border-color:rgba(255,100,100,.30); color:rgba(255,160,160,.92);">Cancelar</button>
                        </div>
                    </div>

                    <!-- Item 3: Personal Training -->
                    <div class="res-card">
                        <div class="res-img-box">
                            <div class="res-tag"><svg viewBox="0 0 24 24">
                                    <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z" />
                                </svg></div>
                            <img src="{{ asset('img/reserva-3.png') }}" class="res-img" alt="PT">
                            <div class="res-time">05:00 PM</div>
                        </div>

                        <div class="res-info">
                            <h3 class="res-title">Entrenamiento Personal</h3>
                            <span class="res-detail">Sesión individual</span>
                            <div class="res-meta">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                Agregado a calendario
                            </div>
                        </div>

                        <div class="res-actions">
                            <span class="res-date">Jueves 25 de Enero</span>
                            <button class="btn-action-sm"
                                style="border-color:rgba(255,100,100,.30); color:rgba(255,160,160,.92);">Cancelar</button>
                        </div>
                    </div>

                </div>
            </div>
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
            margin-bottom: 18px;
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

        .btn-new {
            height: 42px;
            padding: 0 18px;
            border-radius: 12px;
            background: rgba(0, 0, 0, .30);
            border: 1px solid rgba(190, 145, 85, .30);
            color: var(--cream);
            font-size: 13px;
            font-weight: 650;
            letter-spacing: .04em;
            cursor: pointer;
            transition: all .2s;
            white-space: nowrap;
            flex: 0 0 auto;
        }

        .btn-new:hover {
            background: rgba(190, 145, 85, .15);
            border-color: rgba(190, 145, 85, .50);
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

        .section-title {
            font-family: var(--serif);
            font-size: 20px;
            color: var(--cream);
            margin: 0 0 16px;
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

            .btn-new {
                width: 100%;
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
