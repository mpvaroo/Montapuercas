@extends('layouts.app')

@section('title', 'Calendario')

@section('content')
    <div class="main">
        <div class="surface">
            <header class="surface-head">
                <strong>Mi Calendario</strong>
                <div class="head-actions">
                    <button class="icon-btn" aria-label="Buscar"><svg viewBox="0 0 24 24">
                            <path
                                d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16a6.471 6.471 0 0 0 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                        </svg></button>
                    <div class="pill">Vista Mensual</div>
                </div>
            </header>

            <div class="calendar-wrap">
                <div class="monthbar">
                    <div class="month">Enero 2026</div>
                    <div class="month-actions">
                        <button class="icon-btn">‹</button>
                        <button class="icon-btn">›</button>
                    </div>
                </div>

                <div class="dow">
                    <div>Lun</div>
                    <div>Mar</div>
                    <div>Mié</div>
                    <div>Jue</div>
                    <div>Vie</div>
                    <div>Sáb</div>
                    <div>Dom</div>
                </div>

                <div class="grid">
                    <!-- Days... logic simplified for template -->
                    @for ($i = 1; $i <= 31; $i++)
                        <div class="day {{ $i == 25 ? 'today' : '' }}">
                            <div class="num">
                                <span>{{ $i }}</span>
                                @if ($i == 24 || $i == 26 || $i == 29)
                                    <span class="badge"></span>
                                @endif
                            </div>
                            <div class="events">
                                @if ($i == 24)
                                    <div class="event">
                                        <i class="tag green"></i>
                                        <span class="time">11:00</span> Espalda
                                    </div>
                                @endif
                                @if ($i == 25)
                                    <div class="event">
                                        <i class="tag"></i>
                                        <span class="time">08:00</span> Spinning
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <aside class="panel">
            <div class="panel-card">
                <header class="panel-head">
                    <strong>25 de Enero</strong>
                </header>
                <div class="panel-body">
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-left">
                                <i class="tag green"></i>
                                <span>Entrenamientos</span>
                            </div>
                            <div class="legend-right">3 Hoy</div>
                        </div>
                    </div>

                    <div class="schedule">
                        <div class="slot">
                            <div class="left">
                                <div class="info">
                                    <div class="title">Spinning</div>
                                    <div class="sub">Sala Cycling · Laura</div>
                                </div>
                            </div>
                            <div class="right">08:00</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
@endsection

@push('styles')
    <style>
        /* --------------------------------------------------------------------------
          CALENDARIO STYLES
        -------------------------------------------------------------------------- */
        .main {
            min-width: 0;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 22px;
            align-content: start;
        }

        .surface {
            border-radius: 18px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .18);
            backdrop-filter: blur(12px);
            overflow: hidden;
            position: relative;
            box-shadow: 0 18px 52px rgba(0, 0, 0, .45);
        }

        .surface::before {
            content: "";
            position: absolute;
            inset: -1px;
            pointer-events: none;
            background:
                radial-gradient(900px 520px at 20% 20%, rgba(190, 145, 85, .10), transparent 60%),
                radial-gradient(900px 520px at 75% 10%, rgba(22, 250, 22, .06), transparent 55%);
            opacity: .55;
        }

        .surface-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .10);
        }

        .surface-head strong {
            font-family: var(--serif);
            font-weight: 500;
            color: var(--cream);
            font-size: 18px;
        }

        .calendar-wrap {
            padding: 14px;
            display: grid;
            gap: 12px;
        }

        .monthbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 10px 10px;
            border: 1px solid rgba(239, 231, 214, .10);
            border-radius: 14px;
            background: rgba(0, 0, 0, .14);
        }

        .monthbar .month {
            font-family: var(--serif);
            font-weight: 500;
            color: var(--cream);
            font-size: 20px;
        }

        .dow {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            padding: 0 4px;
            color: rgba(239, 231, 214, .55);
            font-size: 11px;
            text-transform: uppercase;
        }

        .dow div {
            padding: 6px 6px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            padding: 0 4px 6px;
        }

        .day {
            min-height: 96px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .14);
            padding: 10px 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            cursor: pointer;
        }

        .day.today {
            border-color: rgba(22, 250, 22, .20);
            background: rgba(0, 0, 0, .14);
        }

        .day .num {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: rgba(239, 231, 214, .86);
            font-weight: 800;
            font-size: 12px;
        }

        .badge {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: rgba(190, 145, 85, .9);
        }

        .events {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .event {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 8px;
            border-radius: 12px;
            border: 1px solid rgba(239, 231, 214, .10);
            background: rgba(0, 0, 0, .18);
            color: rgba(239, 231, 214, .82);
            font-size: 11px;
        }

        .tag {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(190, 145, 85, .85);
        }

        .tag.green {
            background: rgba(22, 250, 22, .35);
        }

        .panel {
            position: sticky;
            top: 28px;
        }

        .panel-card {
            border-radius: 18px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .18);
            backdrop-filter: blur(12px);
        }

        .panel-head {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
        }

        .panel-head strong {
            font-family: var(--serif);
            color: var(--cream);
        }

        @media (max-width: 1200px) {
            .main {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
