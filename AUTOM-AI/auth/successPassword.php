<?php

declare(strict_types=1);

session_start();

// ✅ Protección: solo se puede entrar si vienes de resetPassword.php
if (!isset($_SESSION["password_reset_ok"]) || $_SESSION["password_reset_ok"] !== 1) {
    header("Location: login.php");
    exit;
}

// ✅ Consumimos la marca para que no puedan refrescar y volver a entrar
unset($_SESSION["password_reset_ok"]);

class SuccessPassword
{
    public static function render(): void
    {
?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Contraseña actualizada | AutomAI Solutions</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

            <style>
                :root {
                    --bg0: #070A12;
                    --bg1: #0B1020;
                    --stroke: rgba(255, 255, 255, .14);
                    --text: #EAF0FF;
                    --muted: rgba(234, 240, 255, .72);

                    --a1: #5EEAD4;
                    --a2: #60A5FA;
                    --a3: #A78BFA;
                    --a4: #F472B6;

                    --ok: rgba(94, 234, 212, .95);
                }

                html,
                body {
                    height: 100%;
                }

                body {
                    margin: 0;
                    font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
                    color: var(--text);
                    overflow: hidden;
                    background:
                        radial-gradient(1200px 800px at 15% 20%, rgba(94, 234, 212, .15), transparent 60%),
                        radial-gradient(900px 600px at 85% 25%, rgba(167, 139, 250, .14), transparent 60%),
                        radial-gradient(800px 600px at 55% 90%, rgba(96, 165, 250, .12), transparent 60%),
                        linear-gradient(180deg, var(--bg0), var(--bg1));
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .bg {
                    position: fixed;
                    inset: 0;
                    pointer-events: none;
                    z-index: 0;
                }

                .grid {
                    position: absolute;
                    inset: -20%;
                    background:
                        linear-gradient(to right, rgba(255, 255, 255, .05) 1px, transparent 1px),
                        linear-gradient(to bottom, rgba(255, 255, 255, .05) 1px, transparent 1px);
                    background-size: 70px 70px;
                    transform: rotate(-12deg);
                    opacity: .25;
                    mask-image: radial-gradient(circle at 50% 40%, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, .0) 62%);
                    animation: gridFloat 18s ease-in-out infinite;
                }

                @keyframes gridFloat {

                    0%,
                    100% {
                        transform: translate3d(0, 0, 0) rotate(-12deg);
                    }

                    50% {
                        transform: translate3d(-2%, 1.5%, 0) rotate(-12deg);
                    }
                }

                .blob {
                    position: absolute;
                    width: 520px;
                    height: 520px;
                    filter: blur(26px);
                    opacity: .55;
                    border-radius: 50%;
                    mix-blend-mode: screen;
                    animation: blobMove 14s ease-in-out infinite;
                }

                .blob.b1 {
                    left: -140px;
                    top: -120px;
                    background: radial-gradient(circle at 30% 30%, rgba(94, 234, 212, .95), rgba(94, 234, 212, .05) 60%, transparent 70%);
                    animation-duration: 16s;
                }

                .blob.b2 {
                    right: -170px;
                    top: 80px;
                    background: radial-gradient(circle at 40% 30%, rgba(167, 139, 250, .95), rgba(167, 139, 250, .05) 60%, transparent 70%);
                    animation-duration: 18s;
                    animation-delay: -3s;
                }

                .blob.b3 {
                    left: 40%;
                    bottom: -220px;
                    background: radial-gradient(circle at 40% 40%, rgba(96, 165, 250, .95), rgba(96, 165, 250, .05) 60%, transparent 70%);
                    animation-duration: 20s;
                    animation-delay: -5s;
                }

                @keyframes blobMove {

                    0%,
                    100% {
                        transform: translate3d(0, 0, 0) scale(1);
                    }

                    33% {
                        transform: translate3d(22px, -18px, 0) scale(1.05);
                    }

                    66% {
                        transform: translate3d(-18px, 24px, 0) scale(0.98);
                    }
                }

                .spark {
                    position: absolute;
                    inset: 0;
                    background-image:
                        radial-gradient(circle at 10% 20%, rgba(255, 255, 255, .18) 0 1px, transparent 2px),
                        radial-gradient(circle at 30% 70%, rgba(255, 255, 255, .14) 0 1px, transparent 2px),
                        radial-gradient(circle at 70% 25%, rgba(255, 255, 255, .16) 0 1px, transparent 2px),
                        radial-gradient(circle at 85% 80%, rgba(255, 255, 255, .12) 0 1px, transparent 2px);
                    background-size: 520px 380px;
                    opacity: .45;
                    animation: sparkle 9s ease-in-out infinite;
                }

                @keyframes sparkle {

                    0%,
                    100% {
                        transform: translate3d(0, 0, 0);
                        opacity: .40;
                    }

                    50% {
                        transform: translate3d(-1.5%, 1.2%, 0);
                        opacity: .55;
                    }
                }

                .wrap {
                    position: relative;
                    z-index: 2;
                    width: min(92vw, 430px);
                    padding: 16px;
                }

                .card-premium {
                    position: relative;
                    background: linear-gradient(180deg, rgba(255, 255, 255, .10), rgba(255, 255, 255, .06));
                    border: 1px solid var(--stroke);
                    border-radius: 18px;
                    box-shadow: 0 18px 55px rgba(0, 0, 0, .45), inset 0 1px 0 rgba(255, 255, 255, .10);
                    backdrop-filter: blur(14px);
                    -webkit-backdrop-filter: blur(14px);
                    padding: 22px 20px;
                    overflow: hidden;
                    animation: popIn .6s ease-out both;
                    text-align: center;
                }

                .card-premium::before {
                    content: "";
                    position: absolute;
                    inset: -2px;
                    background: conic-gradient(from 180deg,
                            rgba(94, 234, 212, .18),
                            rgba(96, 165, 250, .18),
                            rgba(167, 139, 250, .18),
                            rgba(244, 114, 182, .18),
                            rgba(94, 234, 212, .18));
                    filter: blur(18px);
                    opacity: .35;
                    z-index: -1;
                }

                @keyframes popIn {
                    from {
                        transform: translateY(10px) scale(.985);
                        opacity: 0;
                    }

                    to {
                        transform: translateY(0) scale(1);
                        opacity: 1;
                    }
                }

                .check {
                    width: 62px;
                    height: 62px;
                    border-radius: 18px;
                    margin: 0 auto 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: rgba(94, 234, 212, .10);
                    border: 1px solid rgba(94, 234, 212, .22);
                    box-shadow: 0 12px 28px rgba(0, 0, 0, .35);
                    position: relative;
                    overflow: hidden;
                }

                .check::after {
                    content: "";
                    position: absolute;
                    inset: -120% -60%;
                    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .18), transparent);
                    transform: rotate(18deg);
                    animation: shine 3.8s ease-in-out infinite;
                }

                @keyframes shine {
                    0% {
                        transform: translateX(-40%) rotate(18deg);
                        opacity: 0;
                    }

                    25% {
                        opacity: .85;
                    }

                    55% {
                        opacity: .25;
                    }

                    100% {
                        transform: translateX(40%) rotate(18deg);
                        opacity: 0;
                    }
                }

                .check span {
                    font-size: 1.75rem;
                    color: var(--ok);
                    position: relative;
                    z-index: 1;
                }

                .title {
                    margin: 0 0 6px 0;
                    font-size: 1.1rem;
                    font-weight: 800;
                    letter-spacing: .2px;
                }

                .subtitle {
                    margin: 0 0 14px 0;
                    color: var(--muted);
                    font-size: .88rem;
                    line-height: 1.35;
                }

                .btn-premium {
                    display: inline-block;
                    width: 100%;
                    text-align: center;
                    text-decoration: none;
                    border: none;
                    border-radius: 12px;
                    padding: 11px 14px;
                    font-weight: 800;
                    letter-spacing: .2px;
                    color: #071018;
                    background: linear-gradient(90deg, var(--a1), var(--a2), var(--a3));
                    box-shadow: 0 12px 30px rgba(0, 0, 0, .35);
                    position: relative;
                    overflow: hidden;
                    transition: transform .15s ease, box-shadow .15s ease;
                }

                .btn-premium:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 16px 40px rgba(0, 0, 0, .45);
                }

                .btn-ghost {
                    display: inline-block;
                    width: 100%;
                    margin-top: 10px;
                    text-align: center;
                    text-decoration: none;
                    border-radius: 12px;
                    padding: 11px 14px;
                    font-weight: 800;
                    letter-spacing: .2px;
                    color: rgba(234, 240, 255, .88);
                    background: rgba(255, 255, 255, .06);
                    border: 1px solid rgba(255, 255, 255, .14);
                    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
                }

                .btn-ghost:hover {
                    transform: translateY(-1px);
                    background: rgba(255, 255, 255, .10);
                    box-shadow: 0 16px 40px rgba(0, 0, 0, .35);
                }

                @media (prefers-reduced-motion: reduce) {

                    .grid,
                    .blob,
                    .spark,
                    .check::after,
                    .card-premium {
                        animation: none !important;
                    }
                }
            </style>
        </head>

        <body>
            <div class="bg">
                <div class="grid"></div>
                <div class="blob b1"></div>
                <div class="blob b2"></div>
                <div class="blob b3"></div>
                <div class="spark"></div>
            </div>

            <div class="wrap">
                <div class="card-premium">
                    <div class="check"><span>✅</span></div>
                    <h1 class="title">Contraseña actualizada</h1>
                    <p class="subtitle">
                        Tu nueva contraseña se ha guardado correctamente.<br>
                        Te redirigimos al login en <span id="seconds">4</span> segundos…
                    </p>

                    <a href="login.php" class="btn-premium">Volver al inicio de sesión</a>
                    <a href="register.php" class="btn-ghost">Crear una cuenta nueva</a>
                </div>
            </div>

            <script>
                (function() {
                    let s = 4;
                    const el = document.getElementById('seconds');
                    const timer = setInterval(() => {
                        s--;
                        if (el) el.textContent = String(s);
                        if (s <= 0) {
                            clearInterval(timer);
                            window.location.href = "login.php";
                        }
                    }, 1000);
                })();
            </script>
        </body>

        </html>
<?php
    }
}

SuccessPassword::render();
