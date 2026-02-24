@extends('layouts.app')

@section('title', 'Detalle de Reserva')

@section('content')
    <div class="main">
        <div>
            <header class="hero">
                <a href="{{ route('reservas') }}"
                    style="color:rgba(239,231,214,.5); text-decoration:none; font-size:12px; margin-bottom:8px; display:block;">&larr;
                    Volver a Reservas</a>
                <h1>Detalle de Reserva</h1>
                <p>Gestiona tu tiempo, maximiza tu rendimiento.</p>
            </header>

            <div class="section-title">
                <div class="left">
                    <span class="dot"></span>
                    <span>Información General</span>
                </div>

            </div>

            <section class="panel">
                <div class="head-grid">
                    <div class="title-block">
                        <h2>{{ $clase->titulo_clase }}</h2>
                        <div class="sub">{{ $clase->tipoClase->nombre_tipo_clase ?? 'Clase General' }}</div>
                    </div>
                    <div class="tags">
                        @if($reserva)
                            <span class="tag green ">{{ ucfirst($reserva->estado_reserva) }}</span>
                        @else
                            <span class="tag bronze">Disponible</span>
                        @endif

                    </div>
                </div>

                <div class="metrics">
                    <div class="metric">
                        <div class="k">Fecha</div>
                        <div class="v">{{ $clase->fecha_inicio_clase->format('d M Y') }}</div>
                    </div>
                    <div class="metric">
                        <div class="k">Hora</div>
                        <div class="v">{{ $clase->fecha_inicio_clase->format('H:i') }} —
                            {{ $clase->fecha_fin_clase->format('H:i') }}
                        </div>
                    </div>
                    <div class="metric">
                        <div class="k">Lugar</div>
                        <div class="v">Sala Principal</div>
                    </div>
                    <div class="metric">
                        <div class="k">Coach</div>
                        <div class="v">{{ $clase->instructor_clase }}</div>
                    </div>
                </div>

                <div class="btn-row">
                    @if($reserva)
                        <form action="{{ route('reservas.cancelar', $clase->id_clase_gimnasio) }}" method="POST"
                            onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Cancelar reserva</button>
                        </form>
                    @else
                        <form action="{{ route('reservas.apuntar', $clase->id_clase_gimnasio) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-premium">Apuntarse ahora</button>
                        </form>
                    @endif
                </div>

                <p class="note">Nota: Las cancelaciones con menos de 2 horas de antelación pueden incurrir en
                    penalizaciones según tu plan.</p>
            </section>

            <div class="section-title">
                <div class="left">
                    <span class="dot"></span>
                    <span>Planificación de la Sesión</span>
                </div>
            </div>

            <div class="class-card">
                <article class="class-main">
                    <h3>Resumen de la clase</h3>
                    <p class="class-title">{{ $clase->titulo_clase }}</p>
                    <div class="class-meta">
                        {{ $clase->descripcion_clase }}
                    </div>
                </article>

                <aside class="class-right">
                    <div class="mini-box ">
                        <div class="k">Ocupación</div>
                        <div class="v">{{ $clase->reservas()->count() }} / {{ $clase->cupo_maximo_clase }} plazas</div>
                        <div class="bar"><span
                                style="width: {{ ($clase->reservas()->count() / $clase->cupo_maximo_clase) * 100 }}%"></span>
                        </div>
                    </div>

                </aside>
            </div>


        </div>

    </div>
@endsection

@push('styles')
    <style>
        /* Estilos específicos de detalle-reserva (portados del HTML original) */
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
            font-weight: 500;
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

        .section-title .left {
            display: flex;
            align-items: center;
            gap: 10px;
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
        }

        .panel {
            margin: 0 6px 14px;
            border-radius: var(--r);
            padding: 22px;
            background: rgba(0, 0, 0, .12);
            border: 1px solid rgba(239, 231, 214, .12);
            backdrop-filter: blur(16px);
        }

        .head-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
            padding-bottom: 14px;
            margin-bottom: 14px;
        }

        .title-block h2 {
            margin: 0;
            font-family: var(--serif);
            font-size: 26px;
            color: var(--cream);
        }

        .title-block .sub {
            margin-top: 8px;
            color: rgba(239, 231, 214, .56);
            font-size: 13px;
        }

        .tags {
            display: flex;
            gap: 8px;
        }

        .tag {
            padding: 0 14px;
            height: 26px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid rgba(239, 231, 214, .12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 90px;
            line-height: 1;
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

        .btn-premium {
            height: 44px;
            padding: 0 24px;
            border-radius: 999px;
            background: linear-gradient(135deg, #be9155 0%, #d4a373 100%);
            color: #000;
            font-size: 13px;
            font-weight: 750;
            border: none;
            cursor: pointer;
            transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            box-shadow: 0 4px 15px rgba(190, 145, 85, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(190, 145, 85, 0.45);
            filter: brightness(1.1);
        }

        .btn-premium:active {
            transform: translateY(0);
        }

        .btn-ghost {
            height: 42px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .16);
            background: transparent;
            color: var(--cream);
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-ghost:hover {
            background: rgba(239, 231, 214, .05);
            border-color: var(--cream);
        }

        .btn-danger {
            height: 42px;
            border-radius: 999px;
            border: 1px solid rgba(255, 100, 100, .3);
            background: rgba(255, 50, 50, .1);
            color: #ff8888;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-danger:hover {
            background: rgba(255, 50, 50, .2);
            border-color: #ff5555;
        }

        .note {
            margin-top: 15px;
            font-size: 12px;
            color: rgba(239, 231, 214, .45);
            font-style: italic;
        }

        .class-card {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 12px;
            margin: 0 6px 14px;
        }

        .class-main {
            border-radius: var(--r);
            padding: 20px;
            background: rgba(0, 0, 0, .1);
            border: 1px solid rgba(239, 231, 214, .12);
        }

        .class-main h3 {
            margin: 0 0 6px;
            font-size: 11px;
            text-transform: uppercase;
            color: var(--cream-3);
        }

        .class-title {
            font-family: var(--serif);
            font-size: 22px;
            color: var(--cream);
            margin: 0;
        }

        .class-meta {
            margin-top: 10px;
            font-size: 13px;
            color: var(--cream-2);
            line-height: 1.5;
        }

        .class-right {
            display: grid;
            gap: 12px;
        }

        .mini-box {
            border-radius: 14px;
            padding: 15px;
            background: rgba(0, 0, 0, .15);
            border: 1px solid rgba(239, 231, 214, .1);
        }

        .mini-box .k {
            font-size: 10px;
            color: var(--cream-3);
            text-transform: uppercase;
        }

        .mini-box .v {
            font-size: 16px;
            color: var(--cream);
            margin-top: 5px;
            font-family: var(--serif);
        }

        .bar {
            height: 8px;
            background: rgba(0, 0, 0, .3);
            border-radius: 4px;
            margin-top: 10px;
            overflow: hidden;
            border: 1px solid rgba(239, 231, 214, .1);
        }

        .bar span {
            display: block;
            height: 100%;
            width: 66%;
            background: #4ade80;
        }



        /* IA Coach specific */
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

        .chat-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(239, 231, 214, .1);
        }

        .chip {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(22, 250, 22, .1);
            display: grid;
            place-items: center;
            color: #4ade80;
            border: 1px solid rgba(239, 231, 214, .1);
        }

        .chat-head h2 {
            font-size: 15px;
            margin: 0;
            color: var(--cream);
        }

        .chat-head p {
            font-size: 11px;
            margin: 2px 0 0;
            color: var(--cream-3);
        }

        .chat-body {
            padding: 15px 0;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .bubble {
            max-width: 85%;
            padding: 10px 14px;
            border-radius: 15px;
            font-size: 13px;
            line-height: 1.4;
            color: var(--cream-2);
            border: 1px solid rgba(239, 231, 214, .1);
        }

        .bubble.ai {
            background: rgba(255, 255, 255, .05);
            align-self: flex-start;
            border-top-left-radius: 4px;
        }

        .bubble.user {
            background: rgba(22, 250, 22, .05);
            align-self: flex-end;
            border-top-right-radius: 4px;
            color: var(--cream);
        }

        .chat-foot {
            display: flex;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid rgba(239, 231, 214, .1);
        }

        .iconbtn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid rgba(239, 231, 214, .1);
            background: rgba(0, 0, 0, .2);
            color: var(--cream-2);
            cursor: pointer;
        }

        .chat-input {
            flex: 1;
            border-radius: 10px;
            background: rgba(0, 0, 0, .2);
            border: 1px solid rgba(239, 231, 214, .1);
            color: #fff;
            padding: 0 12px;
            font-size: 13px;
            outline: none;
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