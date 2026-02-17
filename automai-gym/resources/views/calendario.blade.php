@extends('layouts.app')

@section('title', 'Calendario')

@section('content')
    <div class="main">
        <div class="surface">
            <header class="surface-head">
                <strong>Mi Calendario</strong>
                <div class="head-actions">
                   
                    <div class="pill">Vista Mensual</div>
                </div>
            </header>

            <div class="calendar-wrap">
                <div class="monthbar">
                    <div class="month">{{ ucfirst($monthName) }}</div>
                    <div class="month-actions">
                        <a href="{{ route('calendario', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                            class="icon-btn" style="text-decoration:none;">‹</a>
                        <a href="{{ route('calendario', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                            class="icon-btn" style="text-decoration:none;">›</a>
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
                    @php
                        $today = \Carbon\Carbon::now();
                        $isCurrentMonth = ($today->month == $month && $today->year == $year);
                    @endphp

                    {{-- Empty days at start --}}
                    @for ($i = 1; $i < $startOfWeek; $i++)
                        <div class="day empty" style="opacity: 0.3; pointer-events: none;"></div>
                    @endfor

                    {{-- Days of the month --}}
                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $hasEvents = isset($reservasPorDia[$d]);
                            $isToday = ($isCurrentMonth && $today->day == $d);
                        @endphp
                        <div class="day {{ $isToday ? 'today' : '' }}" onclick="selectDay({{ $d }})">
                            <div class="num">
                                <span>{{ $d }}</span>
                                @if ($hasEvents)
                                    <span class="badge"></span>
                                @endif
                            </div>
                            <div class="events">
                                @if ($hasEvents)
                                    @foreach($reservasPorDia[$d] as $reserva)
                                        <div class="event">
                                            <i class="tag {{ $reserva->clase->id_clase_gimnasio % 2 == 0 ? 'green' : '' }}"></i>
                                            <span class="time">{{ $reserva->clase->fecha_inicio_clase->format('H:i') }}</span>
                                            {{ Str::limit($reserva->clase->titulo_clase, 10) }}
                                        </div>
                                    @endforeach
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
                    <strong
                        id="selected-date">{{ $isCurrentMonth ? $today->translatedFormat('j \d\e F') : 'Selecciona un día' }}</strong>
                </header>
                <div class="panel-body">
                    <div id="no-events"
                        style="{{ ($isCurrentMonth && isset($reservasPorDia[$today->day])) ? 'display:none;' : '' }}">
                        <p style="padding: 20px; color: rgba(239,231,214,.4); font-size: 13px; text-align: center;">
                            No tienes clases reservadas para este día.
                        </p>
                    </div>

                    <div id="events-list">
                        @php
                            $selectedDayReservas = ($isCurrentMonth && isset($reservasPorDia[$today->day])) ? $reservasPorDia[$today->day] : [];
                        @endphp
                        @foreach($selectedDayReservas as $reserva)
                            <div class="schedule-item" data-day="{{ $today->day }}">
                                <div class="slot">
                                    <div class="left">
                                        <div class="info">
                                            <div class="title">{{ $reserva->clase->titulo_clase }}</div>
                                            <div class="sub">{{ $reserva->clase->instructor_clase }}</div>
                                        </div>
                                    </div>
                                    <div class="right">{{ $reserva->clase->fecha_inicio_clase->format('H:i') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <script>
        const reservas = @json($reservasPorDia);
        const monthName = @json(ucfirst($date->translatedFormat('F')));

        function selectDay(day) {
            document.getElementById('selected-date').innerText = day + " de " + monthName;
            const list = document.getElementById('events-list');
            const noEvents = document.getElementById('no-events');
            list.innerHTML = '';

            if (reservas[day]) {
                noEvents.style.display = 'none';
                reservas[day].forEach(reserva => {
                    const time = new Date(reserva.clase.fecha_inicio_clase).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
                    list.innerHTML += `
                                <div class="schedule">
                                    <div class="slot">
                                        <div class="left">
                                            <div class="info">
                                                <div class="title">${reserva.clase.titulo_clase}</div>
                                                <div class="sub">${reserva.clase.instructor_clase}</div>
                                            </div>
                                        </div>
                                        <div class="right">${time}</div>
                                    </div>
                                </div>
                            `;
                });
            } else {
                noEvents.style.display = 'block';
            }
        }
    </script>
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

        .schedule {
            display: grid;
            gap: 12px;
            padding: 16px;
        }

        .slot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border-radius: 14px;
            background: rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .06);
        }

        .slot .left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .slot .title {
            color: var(--cream);
            font-family: var(--serif);
            font-size: 15px;
        }

        .slot .sub {
            color: rgba(239, 231, 214, .45);
            font-size: 11px;
            margin-top: 2px;
        }

        .slot .right {
            color: var(--gold);
            font-weight: 700;
            font-size: 14px;
        }

        @media (max-width: 1200px) {
            .main {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush