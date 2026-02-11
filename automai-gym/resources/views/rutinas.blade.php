@extends('layouts.app')

@section('title', 'Mis Rutinas')

@section('content')
    <div class="main">
        <div class="content">
            <header class="hero">
                <h1>Mis Rutinas</h1>
                <p>Tus entrenamientos personalizados.</p>
            </header>

            <!-- Action Bar -->
            <div class="actions">
                <a href="#" class="btn-action btn-primary">
                    <span>+</span> Crear Nueva Rutina
                </a>
                <a href="#" class="btn-action btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z" />
                    </svg>
                    Calendario de Entrenamientos
                </a>
            </div>

            <!-- Routine Cards Grid -->
            <div class="routines-grid">
                <!-- Card 1 -->
                <div class="routine-card focus-top">
                    <img class="routine-img" src="{{ asset('img/rutina-1.png') }}" alt="Fuerza">
                    <div class="routine-frame"></div>
                    <div class="routine-overlay">
                        <h3>Fuerza y Musculación</h3>
                        <div class="routine-details">
                            <div class="detail"><span class="check-ico">✓</span> 4 Días a la semana</div>
                            <div class="detail"><span class="check-ico">✓</span> Duración: 60 min</div>
                            <div class="detail"><span class="check-ico">✓</span> Nivel: Intermedio</div>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('detalle-rutina') }}" class="btn-card"
                                style="text-decoration:none; display:flex; align-items:center; justify-content:center;">Ver
                                Rutina</a>
                            <button class="btn-card highlight">Iniciar Rutina</button>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="routine-card">
                    <img class="routine-img" src="{{ asset('img/rutina-2.png') }}" alt="Cardio">
                    <div class="routine-frame"></div>
                    <div class="routine-overlay">
                        <h3>Cardio y Quema Grasa</h3>
                        <div class="routine-details">
                            <div class="detail"><span class="check-ico">✓</span> 3 Días a la semana</div>
                            <div class="detail"><span class="check-ico">✓</span> Duración: 45 min</div>
                            <div class="detail"><span class="check-ico">✓</span> Nivel: Principiante</div>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('detalle-rutina') }}" class="btn-card"
                                style="text-decoration:none; display:flex; align-items:center; justify-content:center;">Ver
                                Rutina</a>
                            <button class="btn-card highlight">Iniciar Rutina</button>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="routine-card full-width focus-low">
                    <img class="routine-img" src="{{ asset('img/rutina-3.png') }}" alt="Full Body">
                    <div class="routine-frame"></div>
                    <div class="routine-overlay">
                        <div style="max-width: 520px;">
                            <h3>Rutina Full Body</h3>
                            <div class="routine-details">
                                <div class="detail"><span class="check-ico">✓</span> 2 Días a la semana</div>
                                <div class="detail"><span class="check-ico">✓</span> Duración: 50 min</div>
                                <div class="detail"><span class="check-ico">✓</span> Nivel: Avanzado</div>
                            </div>
                            <div class="card-actions" style="grid-template-columns: 140px 140px; justify-content: start;">
                                <a href="{{ route('detalle-rutina') }}" class="btn-card"
                                    style="text-decoration:none; display:flex; align-items:center; justify-content:center;">Ver
                                    Rutina</a>
                                <button class="btn-card highlight">Iniciar Rutina</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="panel">
            <!-- Objectives -->
            <div class="panel-card">
                <h3 class="panel-head">Mis Objetivos Semanales</h3>

                <div class="stat-row">
                    <div class="stat-head"><span>Entrenamientos:</span> <span>3 / 5</span></div>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 60%"></div>
                    </div>
                </div>

                <div class="stat-row">
                    <div class="stat-head"><span>Calorías Quemadas:</span> <span>1,200 / 2,000 kcal</span></div>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 55%"></div>
                    </div>
                </div>

                <div class="stat-row">
                    <div class="stat-head"><span>Tiempo de Ejercicio:</span> <span>90 / 180 min</span></div>
                    <div class="stat-bar">
                        <div class="stat-fill" style="width: 50%"></div>
                    </div>
                </div>
            </div>

            <!-- Saved Routines -->
            <div class="panel-card">
                <div style="display:flex; justify-content:space-between; align-items:baseline; gap:12px;">
                    <h3 class="panel-head" style="margin:0;">Rutinas Guardadas</h3>
                    <a href="#"
                        style="font-size:12px; color:rgba(239,231,214,.5); text-decoration:none; white-space:nowrap;">
                        Ver Todas >
                    </a>
                </div>

                <div class="saved-list" style="margin-top:18px;">
                    <div class="saved-item">
                        <div class="saved-icon"><svg width="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M20.57 14.86L22 13.43 20.57 12 17 15.57 8.43 7 12 3.43 10.57 2 9.14 3.43 7.71 2 5.57 4.14 4.14 2.71 2.71 4.14l1.43 1.43L2 7.71l1.43 1.43L2 10.57 3.43 12 7 8.43 15.57 17 12 20.57 13.43 22 14.86 20.57 16.29 22 18.43 19.86 19.86 21.29 21.29 19.86l-1.43-1.43 1.43-1.43 1.43 1.43-1.43 1.43zM6 6h.01v.01H6V6zm12 12h.01v.01H18V18z" />
                            </svg></div>
                        <span style="font-size:13px; color:var(--cream);">Estiramientos y Movilidad</span>
                    </div>

                    <div class="saved-item">
                        <div class="saved-icon"><svg width="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M15 1H9v2h6V1zm-4 13h2V8h-2v6zm8.03-6.61l1.42-1.42c-.43-.51-.9-.99-1.41-1.41l-1.42 1.42A8.962 8.962 0 0 0 12 4c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-1.52-.42-2.95-1.12-4.19C19.33 8.3 19.12 7.8 18.88 7.39zM12 20c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z" />
                            </svg></div>
                        <span style="font-size:13px; color:var(--cream);">HIIT Express</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* --------------------------------------------------------------------------
              PAGE SPECIFIC
            -------------------------------------------------------------------------- */
        .main {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
            align-content: start;
            min-width: 0;
        }

        .content {
            min-width: 0;
        }

        /* Header */
        .hero {
            margin-bottom: 20px;
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

        /* Action Bar */
        .actions {
            display: flex;
            gap: 14px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .btn-action {
            height: 48px;
            padding: 0 24px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: var(--sans);
            font-weight: 700;
            font-size: 13px;
            letter-spacing: .04em;
            cursor: pointer;
            text-decoration: none;
            transition: transform .18s ease, filter .18s ease;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(180deg, rgba(70, 98, 72, .9), rgba(40, 65, 40, .95));
            border: 1px solid rgba(239, 231, 214, .16);
            color: var(--cream);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .3);
        }

        .btn-secondary {
            background: rgba(0, 0, 0, .2);
            border: 1px solid rgba(239, 231, 214, .14);
            color: rgba(239, 231, 214, .8);
        }

        .btn-action:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }

        /* --------------------------------------------------------------------------
              ROUTINES GRID
            -------------------------------------------------------------------------- */
        .routines-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            min-width: 0;
        }

        .routine-card {
            position: relative;
            height: 340px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(239, 231, 214, .16);
            cursor: pointer;
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
            background: rgba(0, 0, 0, .15);
            isolation: isolate;
        }

        .routine-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .5);
            border-color: rgba(255, 255, 255, .25);
        }

        .routine-card.full-width {
            grid-column: 1 / -1;
            height: 280px;
        }

        .routine-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transform: scale(1.01);
            transition: transform .55s ease;
            z-index: -2;
            filter: contrast(1.05) saturate(.95) brightness(.92);
        }

        .routine-card:hover .routine-img {
            transform: scale(1.06);
        }

        .routine-card.focus-top .routine-img {
            object-position: center 18%;
        }

        .routine-card.focus-low .routine-img {
            object-position: center 70%;
        }

        .routine-frame {
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                radial-gradient(900px 520px at 40% 55%, rgba(0, 0, 0, .18), transparent 60%),
                radial-gradient(900px 520px at 80% 10%, rgba(0, 0, 0, .26), transparent 55%),
                linear-gradient(180deg, rgba(0, 0, 0, .20), rgba(0, 0, 0, .30));
        }

        .routine-overlay {
            position: absolute;
            inset: 0;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: 14px;
            background:
                linear-gradient(0deg, rgba(0, 0, 0, .92) 0%, rgba(0, 0, 0, .48) 52%, rgba(0, 0, 0, 0.12) 100%),
                linear-gradient(90deg, rgba(0, 0, 0, .62) 0%, rgba(0, 0, 0, .22) 52%, rgba(0, 0, 0, 0) 100%);
        }

        .routine-overlay h3 {
            font-family: var(--serif);
            font-size: 26px;
            color: var(--cream);
            margin: 0;
            line-height: 1.1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, .85);
        }

        .routine-details {
            display: grid;
            gap: 5px;
            margin: 0;
        }

        .detail {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(239, 231, 214, .72);
            font-size: 13px;
            line-height: 1.2;
        }

        .check-ico {
            color: #6b8c6e;
            font-size: 14px;
        }

        .card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            max-width: 360px;
        }

        .btn-card {
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 650;
            letter-spacing: .02em;
            cursor: pointer;
            border: 1px solid rgba(239, 231, 214, .14);
            background: rgba(255, 255, 255, .06);
            color: var(--cream);
            transition: background .2s, transform .18s ease, border-color .2s;
            backdrop-filter: blur(8px);
        }

        .btn-card:hover {
            background: rgba(255, 255, 255, .10);
            transform: translateY(-1px);
            border-color: rgba(239, 231, 214, .22);
        }

        .btn-card.highlight {
            background: linear-gradient(135deg, rgba(190, 145, 85, .34), rgba(190, 145, 85, .12));
            border-color: rgba(190, 145, 85, .45);
        }

        /* Right Panel */
        .panel {
            position: sticky;
            top: 28px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            min-width: 0;
        }

        .panel-card {
            background: rgba(0, 0, 0, .2);
            border: 1px solid rgba(239, 231, 214, .1);
            border-radius: 16px;
            padding: 20px;
            backdrop-filter: blur(12px);
        }

        .panel-head {
            margin: 0 0 18px;
            font-family: var(--serif);
            font-size: 20px;
            color: var(--cream);
        }

        .stat-row {
            margin-bottom: 16px;
        }

        .stat-head {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 13px;
            color: rgba(239, 231, 214, .6);
            gap: 12px;
        }

        .stat-bar {
            height: 6px;
            background: rgba(255, 255, 255, .1);
            border-radius: 99px;
            overflow: hidden;
        }

        .stat-fill {
            height: 100%;
            background: linear-gradient(90deg, #6b8c6e, #8fbf91);
            box-shadow: 0 0 10px rgba(107, 140, 110, .4);
        }

        .saved-list {
            display: grid;
            gap: 12px;
        }

        .saved-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .03);
            border: 1px solid rgba(239, 231, 214, .08);
            transition: background .2s;
            cursor: pointer;
        }

        .saved-item:hover {
            background: rgba(255, 255, 255, .06);
        }

        .saved-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .1);
            display: grid;
            place-items: center;
            color: var(--cream);
            flex: 0 0 32px;
        }

        @media (max-width: 1200px) {
            .main {
                grid-template-columns: 1fr;
            }

            .panel {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .routines-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }

            .card-actions {
                grid-template-columns: 1fr;
                max-width: 100%;
            }
        }
    </style>
@endpush
