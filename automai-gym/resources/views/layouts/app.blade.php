<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>AutomAI Gym — @yield('title', 'Dashboard')</title>

    <!-- Premium Serif Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap"
        rel="stylesheet">

    <style>
        /* --------------------------------------------------------------------------
      RESET & ROOT
    -------------------------------------------------------------------------- */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            background: #070605;
        }

        :root {
            --serif: 'Playfair Display', ui-serif, Georgia, "Times New Roman", Times, serif;
            --sans: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial;

            --cream: rgba(239, 231, 214, .92);
            --cream-2: rgba(239, 231, 214, .76);
            --cream-3: rgba(239, 231, 214, .58);

            --stroke: rgba(239, 231, 214, .18);
            --stroke2: rgba(255, 195, 32, 0.10);

            --glass: rgba(0, 0, 0, 0.10);
            --glass-strong: rgba(0, 0, 0, 0.18);

            --bronze1: rgba(190, 145, 85, .26);
            --bronze2: rgba(190, 145, 85, .10);

            --greenGlow: rgba(22, 250, 22, 0.18);
            --greenBtn1: rgba(70, 98, 72, .92);
            --greenBtn2: rgba(43, 70, 43, 0.98);

            --shadow-xl: 0 34px 110px rgba(56, 52, 32, 0.72);
            --shadow-lg: 0 18px 52px rgba(0, 0, 0, .55);

            --r: 14px;
            --r2: 14px;
        }

        /* --------------------------------------------------------------------------
      STAGE & BACKGROUND
    -------------------------------------------------------------------------- */
        .stage {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            isolation: isolate;
            font-family: var(--sans);
        }

        .bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.02);
            filter: saturate(.82) contrast(1.06) brightness(.62);
            z-index: -4;
        }

        .vignette {
            position: absolute;
            inset: 0;
            z-index: -3;
            pointer-events: none;
            background:
                radial-gradient(1200px 800px at 55% 45%, rgba(0, 0, 0, .08), transparent 58%),
                radial-gradient(1100px 900px at 20% 50%, rgba(0, 0, 0, .34), transparent 60%),
                radial-gradient(1100px 900px at 85% 50%, rgba(0, 0, 0, .38), transparent 62%),
                linear-gradient(180deg, rgba(0, 0, 0, .62), rgba(0, 0, 0, .28), rgba(0, 0, 0, .62));
        }

        .grade {
            position: absolute;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            background:
                linear-gradient(180deg, rgba(30, 18, 10, .62), rgba(18, 12, 8, .22), rgba(12, 10, 8, .65)),
                radial-gradient(900px 650px at 70% 40%, rgba(210, 160, 95, .10), transparent 60%),
                radial-gradient(900px 650px at 25% 42%, rgba(210, 160, 95, .08), transparent 62%);
            mix-blend-mode: multiply;
            opacity: .95;
        }

        .haze {
            position: absolute;
            inset: -8%;
            z-index: -1;
            pointer-events: none;
            background:
                radial-gradient(900px 520px at 55% 45%, rgba(255, 235, 205, .08), transparent 62%),
                radial-gradient(900px 520px at 30% 40%, rgba(255, 235, 205, .05), transparent 64%),
                radial-gradient(900px 520px at 80% 55%, rgba(255, 235, 205, .04), transparent 66%);
            filter: blur(18px);
            opacity: .38;
        }

        .grain {
            position: absolute;
            inset: -20%;
            z-index: 0;
            pointer-events: none;
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='220' height='220' filter='url(%23n)' opacity='.18'/%3E%3C/svg%3E");
            mix-blend-mode: soft-light;
            opacity: .16;
            transform: rotate(-6deg);
        }

        /* --------------------------------------------------------------------------
      APP LAYOUT
    -------------------------------------------------------------------------- */
        .app {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: block;
            padding: 28px 28px 28px 272px;
            /* 240px sidebar + 10px left + 22px gap */
        }

        /* --------------------------------------------------------------------------
      STANDARDIZED MODALS (Based on "Añadir registro")
    -------------------------------------------------------------------------- */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .62);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 20px;
        }

        .modal {
            width: min(720px, 100%);
            border-radius: 16px;
            border: 1px solid rgba(239, 231, 214, .16);
            background: radial-gradient(140% 120% at 18% 10%, rgba(190, 145, 85, .18), transparent 60%), linear-gradient(180deg, rgba(0, 0, 0, .22), rgba(0, 0, 0, .16)), rgba(0, 0, 0, .22);
            box-shadow: 0 34px 110px rgba(56, 52, 32, .72);
            overflow: hidden;
            max-height: 90vh;
            /* Prevents overflow on short screens */
            display: flex;
            flex-direction: column;
        }

        .modal-head {
            padding: 14px 14px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid rgba(239, 231, 214, .10);
            flex-shrink: 0;
        }

        .modal-head h3 {
            margin: 0;
            font-family: var(--serif);
            font-weight: 500;
            color: rgba(239, 231, 214, .92);
            letter-spacing: .01em;
            font-size: 22px;
        }

        .modal-head p {
            margin: 2px 0 0;
            color: rgba(239, 231, 214, .56);
            font-size: 12px;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .modal-close,
        .modal-head .close {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .74);
            cursor: pointer;
            transition: transform .18s, border-color .18s;
            display: grid;
            place-items: center;
            user-select: none;
            font-size: 16px;
            line-height: 1;
        }

        .modal-close:hover,
        .modal-head .close:hover {
            transform: translateY(-1px);
            border-color: rgba(239, 231, 214, .18);
        }

        .modal-body {
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
        }

        .modal-foot {
            padding: 14px;
            border-top: 1px solid rgba(239, 231, 214, .10);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            flex-shrink: 0;
        }

        /* Form fields standard */
        .field {
            display: grid;
            gap: 8px;
        }

        .field label,
        .field .label {
            font-size: 11px;
            color: rgba(239, 231, 214, .58);
            text-transform: uppercase;
            letter-spacing: .10em;
            font-weight: 700;
        }

        .field-input,
        .input-modal,
        .input {
            height: 44px;
            border-radius: 14px;
            border: 1px solid rgba(239, 231, 214, .12);
            background: rgba(0, 0, 0, .12);
            color: rgba(239, 231, 214, .92);
            padding: 0 12px;
            outline: none;
            font-family: var(--sans);
            font-size: 13px;
            transition: border-color .18s, box-shadow .18s;
            width: 100%;
        }

        textarea.field-input,
        textarea.input-modal,
        textarea.input {
            height: 96px;
            padding: 10px 12px;
            resize: vertical;
        }

        .field-input::placeholder,
        .input-modal::placeholder,
        .input::placeholder {
            color: rgba(239, 231, 214, .52) !important;
        }

        .field-input:focus,
        .input-modal:focus,
        .input:focus {
            border-color: rgba(239, 231, 214, .20);
            box-shadow: 0 0 0 4px rgba(190, 145, 85, .10);
        }

        /* Select styling fix */
        select.field-input,
        select.input-modal,
        select.input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='rgba(239, 231, 214, 0.5)'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        select.field-input option,
        select.input-modal option,
        select.input option {
            background-color: #1c1917;
            color: rgba(239, 231, 214, .92);
        }

        /* Date input picker icon fix */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.85) sepia(0.2) saturate(0.5);
            opacity: .75;
            cursor: pointer;
        }

        /* Modal buttons */
        .modal-btn,
        .btn-wide {
            height: 44px;
            border-radius: 999px;
            border: 1px solid rgba(239, 231, 214, .16);
            cursor: pointer;
            font-family: var(--sans);
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-size: 12px;
            display: inline-grid;
            place-items: center;
            transition: transform .18s, filter .18s, border-color .18s;
            user-select: none;
            background: rgba(0, 0, 0, .16);
            color: rgba(239, 231, 214, .82);
            width: 100%;
            text-decoration: none;
        }

        .modal-btn.primary,
        .btn-wide.primary {
            background: radial-gradient(120% 160% at 30% 0%, rgba(22, 250, 22, .18), transparent 35%), linear-gradient(180deg, rgba(70, 98, 72, .92), rgba(43, 70, 43, .98));
            color: rgba(239, 231, 214, .95);
            box-shadow: 0 18px 52px rgba(0, 0, 0, .50);
            border: 1px solid rgba(239, 231, 214, .16);
        }

        .modal-btn:hover,
        .btn-wide:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
            border-color: rgba(239, 231, 214, .22);
        }

        @media (max-width: 900px) {
            .app {
                padding: 18px;
            }
        }

        @stack('styles')
    </style>
    @livewireStyles
</head>

<body>
    <main class="stage">
        <img class="bg" src="{{ asset('img/login-bg.png') }}" alt="" />

        <div class="vignette"></div>
        <div class="grade"></div>
        <div class="haze"></div>
        <div class="grain"></div>

        <section class="app">
            <x-sidebar />

            @yield('content')
        </section>
    </main>

    @stack('scripts')
    @livewireScripts
</body>

</html>
