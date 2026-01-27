@extends('layouts.app')

@section('title', 'Progreso')

@section('content')
    <div class="main">
        <header class="topbar">
            <div class="hero">
                <h1>Tu Progreso</h1>
                <p>Evolución y logros semanales.</p>
            </div>
            <div class="actions">
                <a href="#" class="btn-action primary">Exportar Reporte</a>
            </div>
        </header>

        <div class="layout">
            <div class="col">
                <div class="card">
                    <h3 class="card-title">
                        <span class="badge"><svg viewBox="0 0 24 24">
                                <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z" />
                            </svg></span>
                        Actividad Reciente
                    </h3>

                    <div class="activity">
                        <div class="cover">
                            <img src="{{ asset('img/rutina-1.png') }}" alt="Espalda">
                        </div>
                        <div>
                            <div class="act-head">
                                <div>
                                    <h4 style="margin:0; font-family:var(--serif); font-size:24px; color:var(--cream);">
                                        Espalda & Bíceps</h4>
                                    <div class="act-sub">Completado hoy · 50 min</div>
                                </div>
                                <div class="pill"><i></i> Intensidad Moderada</div>
                            </div>

                            <div class="act-stats">
                                <div class="stat">
                                    <div class="stat-top">
                                        <div class="mini"><svg viewBox="0 0 24 24">
                                                <path
                                                    d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12s4.48 10 10 10 10-4.48 10-10zM10 16.5v-9l6 4.5-6 4.5z" />
                                            </svg></div>
                                    </div>
                                    <div class="stat-val">340</div>
                                    <div class="stat-label">Calorías</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-top">
                                        <div class="mini"><svg viewBox="0 0 24 24">
                                                <path
                                                    d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
                                                <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                                            </svg></div>
                                    </div>
                                    <div class="stat-val">50m</div>
                                    <div class="stat-label">Tiempo</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-top">
                                        <div class="mini"><svg viewBox="0 0 24 24">
                                                <path d="M7 5H5v14h2V5zm12 0h-2v14h2V5zm-6 0h-2v14h2V5z" />
                                            </svg></div>
                                    </div>
                                    <div class="stat-val">4/5</div>
                                    <div class="stat-label">Ejercicios</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="col right-sticky">
                <div class="card">
                    <div class="subhead">
                        <h3 class="card-title" style="margin:0;">Ranking Semanal</h3>
                        <a href="#">Ver todos</a>
                    </div>
                    <div class="list">
                        <div class="row">
                            <div class="row-left">
                                <div class="label">Tú</div>
                            </div>
                            <div class="xp">2,400 XP</div>
                        </div>
                    </div>
                    <button class="btn-full">Mi Historial Completo</button>
                </div>
            </aside>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* --------------------------------------------------------------------------
          PROGRESO STYLES
        -------------------------------------------------------------------------- */
        .main {
            min-width: 0;
            display: grid;
            grid-template-rows: auto 1fr;
            gap: 18px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
        }

        .hero h1 {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            font-size: 42px;
            color: var(--cream);
        }

        .hero p {
            margin: 6px 0 0;
            color: rgba(239, 231, 214, .60);
            font-size: 12px;
            text-transform: uppercase;
        }

        .layout {
            display: grid;
            grid-template-columns: 1.9fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .card {
            background: rgba(0, 0, 0, .20);
            border: 1px solid rgba(239, 231, 214, .10);
            border-radius: 16px;
            padding: 20px;
            backdrop-filter: blur(12px);
        }

        .card-title {
            margin: 0 0 16px;
            font-family: var(--serif);
            font-size: 20px;
            color: var(--cream);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .activity {
            display: grid;
            grid-template-columns: 210px 1fr;
            gap: 18px;
        }

        .cover {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(239, 231, 214, .12);
            height: 150px;
        }

        .cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .act-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .stat {
            padding: 12px 12px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .14);
        }

        .stat-val {
            font-family: var(--serif);
            font-size: 22px;
            color: var(--cream);
        }

        @media (max-width: 1200px) {
            .layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
