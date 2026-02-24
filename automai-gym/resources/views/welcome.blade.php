<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>AutomAI Gym — Tu Entrenamiento Inteligencia Artificial</title>
    <meta name="description"
        content="AutomAI Gym: La plataforma líder en fitness con IA. Rutinas personalizadas, seguimiento de progreso y reservas de clases en una experiencia premium." />

    <!-- Premium Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap"
        rel="stylesheet">

    <style>
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

            --r: 22px;
            --transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #070605;
            color: var(--cream);
            font-family: var(--sans);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ─── BACKGROUND AND EFFECTS (Matching layouts.app) ─── */
        .stage {
            position: relative;
            min-height: 100vh;
            isolation: isolate;
        }

        .bg-fixed {
            position: fixed;
            inset: 0;
            z-index: -10;
            background-image: url('{{ asset('img/login-bg.png') }}');
            background-size: cover;
            background-position: center;
            filter: saturate(.82) contrast(1.06) brightness(.50);
            transform: scale(1.02);
        }

        .vignette {
            position: fixed;
            inset: 0;
            z-index: -9;
            pointer-events: none;
            background:
                radial-gradient(1200px 800px at 55% 45%, rgba(0, 0, 0, .08), transparent 58%),
                radial-gradient(1100px 900px at 20% 50%, rgba(0, 0, 0, .34), transparent 60%),
                radial-gradient(1100px 900px at 85% 50%, rgba(0, 0, 0, .38), transparent 62%),
                linear-gradient(180deg, rgba(0, 0, 0, .62), rgba(0, 0, 0, .28), rgba(0, 0, 0, .62));
        }

        .grade {
            position: fixed;
            inset: 0;
            z-index: -8;
            pointer-events: none;
            background:
                linear-gradient(180deg, rgba(30, 18, 10, .62), rgba(18, 12, 8, .22), rgba(12, 10, 8, .65)),
                radial-gradient(900px 650px at 70% 40%, rgba(210, 160, 95, .10), transparent 60%),
                radial-gradient(900px 650px at 25% 42%, rgba(210, 160, 95, .08), transparent 62%);
            mix-blend-mode: multiply;
            opacity: .95;
        }

        .haze {
            position: fixed;
            inset: -8%;
            z-index: -7;
            pointer-events: none;
            background:
                radial-gradient(900px 520px at 55% 45%, rgba(255, 235, 205, .08), transparent 62%),
                radial-gradient(900px 520px at 30% 40%, rgba(255, 235, 205, .05), transparent 64%),
                radial-gradient(900px 520px at 80% 55%, rgba(255, 235, 205, .04), transparent 66%);
            filter: blur(18px);
            opacity: .38;
        }

        .grain {
            position: fixed;
            inset: -20%;
            z-index: -6;
            pointer-events: none;
            background-image:
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='220' height='220' filter='url(%23n)' opacity='.18'/%3E%3C/svg%3E");
            mix-blend-mode: soft-light;
            opacity: .16;
            transform: rotate(-6deg);
        }

        /* ─── NAVIGATION ─── */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 24px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        nav.scrolled {
            padding: 16px 6%;
            background: rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(12px) saturate(110%);
            border-bottom: 1px solid var(--stroke);
        }

        .logo {
            font-family: var(--serif);
            font-size: 26px;
            font-weight: 500;
            text-decoration: none;
            color: var(--cream);
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: .02em;
        }

        .logo span {
            display: inline-block;
            background: rgba(190, 145, 85, .15);
            border: 1px solid rgba(190, 145, 85, .3);
            color: var(--cream-2);
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            font-family: var(--sans);
        }

        .nav-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* ─── BUTTONS ─── */
        .btn {
            font-family: var(--sans);
            font-weight: 800;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 999px;
            font-size: 13px;
            letter-spacing: .06em;
            text-transform: uppercase;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
        }

        .btn-ghost {
            color: var(--cream-2);
            background: transparent;
        }

        .btn-ghost:hover {
            color: var(--cream);
            background: rgba(239, 231, 214, .05);
        }

        .btn-primary {
            background:
                radial-gradient(120% 160% at 30% 0%, var(--greenGlow), transparent 35%),
                linear-gradient(180deg, var(--greenBtn1), var(--greenBtn2));
            color: var(--cream);
            border: 1px solid rgba(239, 231, 214, .16);
            box-shadow: 0 18px 52px rgba(0, 0, 0, .50);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.06);
            border-color: rgba(239, 231, 214, .22);
        }

        .btn-outline {
            background: rgba(0, 0, 0, .15);
            color: var(--cream-2);
            border: 1px solid var(--stroke);
        }

        .btn-outline:hover {
            background: rgba(0, 0, 0, .3);
            border-color: var(--cream-3);
            color: var(--cream);
        }

        /* ─── HERO ─── */
        .hero {
            padding: 180px 6% 100px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            max-width: 1400px;
            margin: 0 auto;
        }

        .badge {
            display: inline-block;
            margin-bottom: 24px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(190, 145, 85, .10);
            border: 1px solid rgba(190, 145, 85, .25);
            color: var(--cream-2);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .15em;
            text-transform: uppercase;
            animation: revealUp 1s ease backwards;
        }

        .hero h1 {
            font-family: var(--serif);
            font-size: clamp(3rem, 7vw, 5.5rem);
            line-height: 1.05;
            font-weight: 500;
            max-width: 900px;
            margin-bottom: 28px;
            color: var(--cream);
            text-shadow: 0 12px 40px rgba(0, 0, 0, .62);
            animation: revealUp 1s ease 0.2s backwards;
        }

        .hero h1 i {
            color: rgba(190, 145, 85, .9);
            font-style: italic;
        }

        .hero p {
            font-size: clamp(1rem, 1.2vw, 1.15rem);
            color: var(--cream-3);
            max-width: 600px;
            margin-bottom: 48px;
            animation: revealUp 1s ease 0.4s backwards;
            letter-spacing: .02em;
        }

        .hero-btns {
            display: flex;
            gap: 16px;
            animation: revealUp 1s ease 0.6s backwards;
        }

        /* ─── GLASS CARDS (Matching Dashboard) & SECTIONS ─── */
        .section {
            padding: 120px 6%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-header h2 {
            font-family: var(--serif);
            font-size: clamp(2.2rem, 5vw, 4rem);
            font-weight: 500;
            margin-bottom: 20px;
            color: var(--cream);
        }

        .section-header p {
            color: var(--cream-3);
            max-width: 650px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .glass-card {
            position: relative;
            background:
                radial-gradient(140% 120% at 18% 10%, var(--bronze1), transparent 65%),
                radial-gradient(140% 120% at 88% 45%, var(--bronze2), transparent 62%),
                linear-gradient(180deg, rgba(0, 0, 0, .10), rgba(0, 0, 0, .05)),
                var(--glass);
            border: 1px solid var(--stroke);
            padding: 44px 38px;
            border-radius: var(--r);
            backdrop-filter: blur(16px) saturate(115%);
            -webkit-backdrop-filter: blur(16px) saturate(115%);
            transition: var(--transition);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-xl);
        }

        .glass-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(239, 231, 214, .08), transparent 40%, rgba(239, 231, 214, .04)),
                radial-gradient(140% 120% at 50% 15%, rgba(0, 0, 0, 0), rgba(0, 0, 0, .18));
            pointer-events: none;
            opacity: .95;
        }

        .glass-card:hover {
            transform: translateY(-8px);
            border-color: rgba(239, 231, 214, .25);
            background: rgba(255, 255, 255, 0.02);
        }

        .glass-card .icon {
            font-size: 44px;
            margin-bottom: 28px;
            color: rgba(190, 145, 85, .9);
        }

        .glass-card h3 {
            font-family: var(--serif);
            font-size: 24px;
            margin-bottom: 16px;
            font-weight: 500;
            letter-spacing: .01em;
        }

        .glass-card p {
            color: var(--cream-3);
            font-size: 14.5px;
            line-height: 1.7;
            letter-spacing: .02em;
        }

        /* ─── STEPS (How it works) ─── */
        .steps-box {
            background: rgba(0, 0, 0, 0.15);
            border: 1px solid var(--stroke);
            border-radius: 32px;
            padding: 80px 40px;
            margin-top: 60px;
            backdrop-filter: blur(8px);
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
        }

        .step-item {
            text-align: center;
            position: relative;
        }

        .step-num {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            border: 1px solid rgba(190, 145, 85, .3);
            background: rgba(190, 145, 85, .1);
            color: rgba(190, 145, 85, .9);
            display: grid;
            place-items: center;
            margin: 0 auto 24px;
            font-family: var(--serif);
            font-size: 20px;
            font-weight: 500;
        }

        .step-item h4 {
            font-family: var(--serif);
            font-size: 20px;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .step-item p {
            color: var(--cream-3);
            font-size: 14px;
        }

        /* ─── CALL TO ACTION (Public Facing) ─── */
        .final-cta {
            padding: 160px 6%;
            text-align: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .cta-box {
            padding: 100px 40px;
            border-radius: 32px;
            background: linear-gradient(135deg, rgba(190, 145, 85, 0.05), transparent);
            border: 1px solid var(--stroke);
            backdrop-filter: blur(10px);
        }

        .final-cta h2 {
            font-family: var(--serif);
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            margin-bottom: 30px;
            font-weight: 500;
        }

        /* ─── ANIMATIONS ─── */
        @keyframes revealUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: 1s all cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* ─── FOOTER ─── */
        footer {
            padding: 80px 6%;
            border-top: 1px solid var(--stroke);
            text-align: center;
        }

        .footer-logo {
            font-family: var(--serif);
            font-size: 22px;
            color: var(--cream);
            text-decoration: none;
            margin-bottom: 24px;
            display: inline-block;
        }

        footer p {
            color: var(--cream-3);
            font-size: 13px;
            letter-spacing: .05em;
        }

        @media (max-width: 768px) {
            nav {
                padding: 16px 20px;
            }

            .hero {
                padding-top: 140px;
                text-align: center;
                align-items: center;
            }

            .hero-btns {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="stage">
        <!-- Background Elements matching app.blade.php -->
        <div class="bg-fixed"></div>
        <div class="vignette"></div>
        <div class="grade"></div>
        <div class="haze"></div>
        <div class="grain"></div>

        <!-- Navigation -->
        <nav id="navbar">
            <a href="{{ route('welcome') }}" class="logo">
                AutomAI Gym <span>IA Coach</span>
            </a>
            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn btn-ghost">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Empezar</a>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="hero">
            <div class="badge">Ecosistema Inteligente de Fitness</div>
            <h1>
                Entrena con <i>intención</i>.<br>
                Avanza con <i>precisión</i>.
            </h1>
            <p>
                Diseñamos el futuro del entrenamiento. Combinamos la elegancia de una gestión premium con el poder de la
                IA para elevar cada serie, cada repetición y cada detalle de tu progreso.
            </p>
            <div class="hero-btns">
                <a href="{{ route('register') }}" class="btn btn-primary"
                    style="padding: 18px 48px; font-size: 15px;">Crear cuenta gratuita</a>
                <a href="#features" class="btn btn-outline" style="padding: 18px 48px; font-size: 15px;">Descubrir
                    más</a>
            </div>
        </header>

        <!-- Features Section -->
        <section class="section" id="features">
            <div class="section-header reveal">
                <div class="badge" style="margin-bottom: 16px;">La Experiencia AutomAI</div>
                <h2>Tecnología de Vanguardia</h2>
                <p>Nuestra plataforma asimila tu ADN deportivo para ofrecerte una interfaz ultra-rápida y resultados
                    matemáticamente exactos.</p>
            </div>

            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="glass-card reveal">
                    <div class="icon">🤖</div>
                    <h3>IA Coach Personal</h3>
                    <p>Algoritmos dinámicos que recalibran tus pesos óptimos tras cada sesión. Tu rutina no es estática;
                        evoluciona al ritmo de tu fisiología.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card reveal">
                    <div class="icon">📅</div>
                    <h3>Reservas sin Fricción</h3>
                    <p>Asegura tu plaza en clases de fuerza o movilidad en segundos. Consulta métricas de afluencia y
                        gestiona tu calendario con un toque.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card reveal">
                    <div class="icon">�</div>
                    <h3>Visión Absoluta</h3>
                    <p>Gráficos de progresión histórica y reportes en PDF. Mantén un control total sobre tus marcas
                        personales y métricas biométricas.</p>
                </div>
            </div>
        </section>

        <!-- How it works (The Path) -->
        <section class="section">
            <div class="steps-box reveal">
                <div class="section-header" style="margin-bottom: 60px;">
                    <h2>Tu Camino a la Élite</h2>
                </div>
                <div class="steps-grid">
                    <div class="step-item">
                        <div class="step-num">01</div>
                        <h4>Registro Premium</h4>
                        <p>Accede a nuestro ecosistema en menos de 60 segundos.</p>
                    </div>
                    <div class="step-item">
                        <div class="step-num">02</div>
                        <h4>Configura tu IA</h4>
                        <p>Define tus objetivos y deja que nuestros algoritmos diseñen el plan inicial.</p>
                    </div>
                    <div class="step-item">
                        <div class="step-num">03</div>
                        <h4>Entrena y Vence</h4>
                        <p>Sigue tu rutina guiada, registra tus pesos y observa tu evolución en tiempo real.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="final-cta reveal">
            <div class="cta-box">
                <div class="badge" style="margin-bottom: 16px;">¿Listo para el cambio?</div>
                <h2>Únete a la nueva era del entrenamiento.</h2>
                <div style="margin-top: 48px; display: flex; justify-content: center;">
                    <a href="{{ route('register') }}" class="btn btn-primary"
                        style="padding: 22px 64px; font-size: 16px;">Empezar ahora</a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer>
            <a href="{{ route('welcome') }}" class="footer-logo">AutomAI Gym</a>
            <p>&copy; {{ date('Y') }} — Todos los derechos reservados.</p>
            <p style="margin-top: 8px; opacity: 0.5;">Entrena con Inteligencia Artificial.</p>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Scroll Reveal Animation logic
        function reveal() {
            const reveals = document.querySelectorAll(".reveal");
            for (let i = 0; i < reveals.length; i++) {
                const windowHeight = window.innerHeight;
                const elementTop = reveals[i].getBoundingClientRect().top;
                const elementVisible = 100;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }

        window.addEventListener("scroll", reveal);
        // Run once on load
        document.addEventListener("DOMContentLoaded", reveal);
    </script>
</body>

</html>
