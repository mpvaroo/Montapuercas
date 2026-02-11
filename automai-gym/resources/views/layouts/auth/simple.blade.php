<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <style>
        :root {
            --serif: 'Playfair Display', ui-serif, Georgia, serif;
            --sans: ui-sans-serif, system-ui, -apple-system, sans-serif;
            --cream: rgba(239, 231, 214, .92);
            --cream-2: rgba(239, 231, 214, .76);
            --cream-3: rgba(239, 231, 214, .58);
        }

        body {
            margin: 0;
            background: #070605;
            color: var(--cream);
            font-family: var(--sans);
            overflow: hidden;
        }

        .split-stage {
            display: grid;
            grid-template-columns: 1fr 500px;
            height: 100vh;
            width: 100vw;
        }

        /* LEFT SIDE: CINEMATIC BACKGROUND */
        .visual {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 60px;
            isolation: isolate;
        }

        .visual .bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -4;
            filter: saturate(.8) contrast(1.1) brightness(.7);
        }

        .vignette {
            position: absolute;
            inset: 0;
            z-index: -3;
            background: radial-gradient(circle at center, transparent 20%, rgba(0, 0, 0, 0.4) 100%),
                linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 50%);
        }

        .visual-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
        }

        .visual-content h1 {
            font-family: var(--serif);
            font-size: clamp(40px, 5vw, 72px);
            line-height: 1;
            margin: 0 0 20px;
            color: var(--cream);
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .visual-content p {
            font-size: 18px;
            color: var(--cream-2);
            line-height: 1.6;
            letter-spacing: 0.02em;
        }

        /* RIGHT SIDE: FORM */
        .form-side {
            background: #0a0a0a;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            border-left: 1px solid rgba(239, 231, 214, .1);
            position: relative;
            z-index: 10;
        }

        .logo-header {
            position: absolute;
            top: 40px;
            left: 60px;
        }

        .auth-card {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
        }

        @media (max-width: 1024px) {
            .split-stage {
                grid-template-columns: 1fr;
            }

            .visual {
                display: none;
            }

            .form-side {
                border-left: none;
                width: 100%;
                padding: 40px 20px;
            }
        }
    </style>
</head>

<body class="antialiased">
    <div class="split-stage">
        <section class="visual">
            <img class="bg" src="{{ asset('img/login-bg.png') }}" alt="Gym background" />
            <div class="vignette"></div>

            <div class="visual-content">
                <h1>AutomAI<br>Gym System</h1>
                <p>Potencia tu entrenamiento con IA y un diseño editorial diseñado para atletas de alto rendimiento.</p>
            </div>
        </section>

        <section class="form-side">
            <div class="logo-header">
                <a href="{{ route('home') }}" wire:navigate>
                    <x-app-logo-icon class="size-10 fill-current text-white" />
                </a>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </section>
    </div>
    @fluxScripts
</body>

</html>
