<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>AutomAI Gym — @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap"
        rel="stylesheet">

    <style>
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
            --bronze1: rgba(190, 145, 85, .28);
            --bronze2: rgba(190, 145, 85, .12);
            --greenGlow: rgba(22, 250, 22, 0.18);
            --greenBtn1: rgba(70, 98, 72, .92);
            --greenBtn2: rgba(43, 70, 43, 0.98);
            --shadow-xl: 0 34px 110px rgba(56, 52, 32, 0.72);
            --shadow-lg: 0 18px 52px rgba(0, 0, 0, .55);
            --r: 22px;
        }

        .stage {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: grid;
            place-items: center;
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

        .wrap {
            width: min(1200px, calc(100% - 56px));
            display: grid;
            grid-template-columns: 440px 1fr;
            gap: clamp(60px, 10vw, 160px);
            align-items: center;
            padding: 56px 0;
            position: relative;
            z-index: 2;
        }

        .card {
            position: relative;
            border-radius: var(--r);
            min-height: 620px;
            padding: 34px 28px 28px;
            background:
                radial-gradient(140% 120% at 18% 10%, var(--bronze1), transparent 65%),
                radial-gradient(140% 120% at 88% 45%, var(--bronze2), transparent 62%),
                linear-gradient(180deg, rgba(14, 56, 17, 0.08), rgba(255, 226, 226, 0.02)),
                var(--glass);
            border: 1px solid var(--stroke);
            box-shadow: var(--shadow-xl);
            backdrop-filter: blur(18px) saturate(115%);
            -webkit-backdrop-filter: blur(18px) saturate(115%);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(239, 231, 214, .10), transparent 40%, rgba(239, 231, 214, .06)),
                radial-gradient(140% 120% at 50% 15%, rgba(0, 0, 0, .00), rgba(0, 0, 0, .26));
            pointer-events: none;
            opacity: .95;
        }

        .card::after {
            content: "";
            position: absolute;
            inset: -20%;
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='220' height='220' filter='url(%23n)' opacity='.16'/%3E%3C/svg%3E");
            mix-blend-mode: soft-light;
            opacity: .22;
            pointer-events: none;
            transform: rotate(8deg);
        }

        @media (max-width: 980px) {
            .wrap {
                grid-template-columns: 1fr;
                gap: 26px;
                padding: 44px 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <main class="stage">
        <img class="bg" src="{{ asset('img/login-bg.png') }}" alt="" />
        <div class="vignette"></div>
        <div class="grade"></div>
        <div class="haze"></div>
        <div class="grain"></div>

        <section class="wrap">
            @yield('content')

            @yield('right-content')
        </section>
    </main>

    @stack('scripts')
</body>

</html>