@extends('layouts.app')

@section('title', 'Mis Rutinas')

@section('content')
    <style>
        /* LAYOUT CRÍTICO RUTINAS */
        div.main {
            display: grid !important;
            grid-template-columns: 1fr 380px;
            gap: 24px;
            width: 100% !important;
        }

        @media (max-width: 1200px) {
            div.main {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
    <div class="main">
        <div class="content">
            <header class="hero">
                <h1>Mis Rutinas</h1>
                <p>Tus entrenamientos personalizados.</p>
            </header>

            @livewire('user.routine-management')
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

    @endpush
