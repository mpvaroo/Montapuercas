<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . "/../controladores/controladorAuth.php";

$ok = false;
$err = false;
$mensajeError = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim((string)($_POST["email"] ?? ""));

    $res = controladorAuth::forgotPassword();

    $ok = (bool)($res["ok"] ?? false);
    $err = (bool)($res["err"] ?? false);
    $mensajeError = (string)($res["mensaje"] ?? "");
}

$emailSafe = htmlspecialchars($email, ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña | AutomAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg0: #050814;
            --bg1: #080C1C;
            --stroke: rgba(255, 255, 255, .14);
            --text: #ECF2FF;
            --muted: rgba(236, 242, 255, .70);
            --a1: #5EEAD4;
            --a2: #60A5FA;
            --a3: #A78BFA;
            --a4: #F472B6;
            --radius: 22px;
            --shadow: 0 22px 70px rgba(0, 0, 0, .55);
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
            color: var(--text);
            overflow: hidden;
            background:
                radial-gradient(1200px 800px at 15% 20%, rgba(94, 234, 212, .14), transparent 60%),
                radial-gradient(900px 600px at 85% 25%, rgba(167, 139, 250, .14), transparent 60%),
                radial-gradient(900px 650px at 55% 90%, rgba(96, 165, 250, .12), transparent 60%),
                linear-gradient(180deg, var(--bg0), var(--bg1));
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
            background-size: 72px 72px;
            transform: rotate(-10deg);
            opacity: .22;
            mask-image: radial-gradient(circle at 50% 40%, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 0) 62%);
            animation: gridFloat 18s ease-in-out infinite;
        }

        @keyframes gridFloat {

            0%,
            100% {
                transform: translate3d(0, 0, 0) rotate(-10deg);
            }

            50% {
                transform: translate3d(-2%, 1.3%, 0) rotate(-10deg);
            }
        }

        .blob {
            position: absolute;
            width: 560px;
            height: 560px;
            filter: blur(28px);
            opacity: .55;
            border-radius: 50%;
            mix-blend-mode: screen;
            animation: blobMove 16s ease-in-out infinite;
        }

        .b1 {
            left: -180px;
            top: -160px;
            background: radial-gradient(circle at 30% 30%, rgba(94, 234, 212, .95), rgba(94, 234, 212, .05) 60%, transparent 72%);
            animation-duration: 18s;
        }

        .b2 {
            right: -190px;
            top: 70px;
            background: radial-gradient(circle at 40% 30%, rgba(167, 139, 250, .95), rgba(167, 139, 250, .05) 60%, transparent 72%);
            animation-duration: 20s;
            animation-delay: -3s;
        }

        .b3 {
            left: 40%;
            bottom: -240px;
            background: radial-gradient(circle at 40% 40%, rgba(96, 165, 250, .95), rgba(96, 165, 250, .05) 60%, transparent 72%);
            animation-duration: 22s;
            animation-delay: -6s;
        }

        @keyframes blobMove {

            0%,
            100% {
                transform: translate3d(0, 0, 0) scale(1);
            }

            33% {
                transform: translate3d(26px, -18px, 0) scale(1.05);
            }

            66% {
                transform: translate3d(-18px, 24px, 0) scale(.98);
            }
        }

        .page {
            position: relative;
            z-index: 1;
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 34px 14px;
        }

        .card-glass {
            width: min(96vw, 980px);
            border-radius: var(--radius);
            border: 1px solid var(--stroke);
            background: linear-gradient(180deg, rgba(255, 255, 255, .10), rgba(255, 255, 255, .06));
            box-shadow: var(--shadow), inset 0 1px 0 rgba(255, 255, 255, .10);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            overflow: hidden;
            animation: pop .55s ease-out both;
        }

        @keyframes pop {
            from {
                transform: translateY(10px) scale(.985);
                opacity: 0;
            }

            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .left {
            padding: 34px;
            min-height: 520px;
            border-right: 1px solid rgba(255, 255, 255, .10);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .right {
            padding: 34px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .16);
            display: grid;
            place-items: center;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .35);
            position: relative;
            overflow: hidden;
        }

        .logo::before {
            content: "";
            position: absolute;
            inset: -2px;
            background: conic-gradient(from 180deg,
                    rgba(94, 234, 212, .35),
                    rgba(96, 165, 250, .35),
                    rgba(167, 139, 250, .35),
                    rgba(244, 114, 182, .35),
                    rgba(94, 234, 212, .35));
            filter: blur(16px);
            opacity: .55;
        }

        .logo svg {
            position: relative;
            z-index: 1;
        }

        .headline {
            margin: 18px 0 10px;
            font-size: 2rem;
            line-height: 1.05;
            letter-spacing: -.5px;
        }

        .sub {
            margin: 0;
            color: var(--muted);
            max-width: 48ch;
            line-height: 1.45;
        }

        .tip {
            margin-top: 16px;
            padding: 12px 14px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .10);
            background: rgba(10, 14, 30, .35);
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-top: 6px;
            background: linear-gradient(90deg, var(--a1), var(--a2), var(--a3));
            box-shadow: 0 0 0 6px rgba(94, 234, 212, .08);
            flex: 0 0 auto;
        }

        .form-title {
            margin: 0 0 10px;
            font-weight: 900;
            letter-spacing: .2px;
            font-size: 1.25rem;
        }

        .hint {
            margin: 0 0 14px;
            color: var(--muted);
        }

        .alert-glass {
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(10, 14, 30, .55);
            padding: 10px 12px;
            margin-bottom: 14px;
            color: rgba(236, 242, 255, .92);
        }

        .alert-glass.error {
            border-color: rgba(244, 114, 182, .28);
            box-shadow: 0 0 0 .12rem rgba(244, 114, 182, .10);
        }

        .alert-glass.success {
            border-color: rgba(94, 234, 212, .28);
            box-shadow: 0 0 0 .12rem rgba(94, 234, 212, .10);
        }

        .form-floating>.form-control {
            background: rgba(10, 14, 30, .55);
            border: 1px solid rgba(255, 255, 255, .14);
            color: var(--text);
            border-radius: 14px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .06);
        }

        .form-floating>label {
            color: rgba(236, 242, 255, .55);
        }

        .btn-glow {
            border: none;
            border-radius: 14px;
            padding: 12px 14px;
            font-weight: 900;
            letter-spacing: .2px;
            color: #06101A;
            background: linear-gradient(90deg, var(--a1), var(--a2), var(--a3));
            box-shadow: 0 14px 40px rgba(0, 0, 0, .45);
            position: relative;
            overflow: hidden;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .btn-glow::after {
            content: "";
            position: absolute;
            inset: -120% -60%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .30), transparent);
            transform: rotate(18deg);
            animation: shine 3.6s ease-in-out infinite;
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

        .btn-glow:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 52px rgba(0, 0, 0, .55);
        }

        .fineprint {
            color: rgba(236, 242, 255, .60);
            font-size: .88rem;
            margin: 14px 0 0;
            text-align: center;
        }

        a {
            color: rgba(94, 234, 212, .95);
            text-decoration: none;
        }

        a:hover {
            color: rgba(96, 165, 250, .95);
        }

        @media (max-width: 992px) {
            .left {
                display: none;
            }

            .right {
                padding: 28px 22px;
            }

            .card-glass {
                width: min(96vw, 520px);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .grid,
            .blob,
            .btn-glow::after,
            .card-glass {
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
    </div>

    <main class="page">
        <section class="card-glass">
            <div class="row g-0">
                <!-- LEFT -->
                <div class="col-lg-6">
                    <div class="left">
                        <div>
                            <div class="brand">
                                <div class="logo">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 2l8.5 5v10L12 22 3.5 17V7L12 2Z" stroke="white" stroke-opacity=".9" stroke-width="1.6" />
                                        <path d="M7.5 9.2l4.5 2.7 4.5-2.7" stroke="white" stroke-opacity=".85" stroke-width="1.6" stroke-linecap="round" />
                                        <path d="M12 11.9v6.3" stroke="white" stroke-opacity=".75" stroke-width="1.6" stroke-linecap="round" />
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-weight:900; font-size:1.1rem;">AutomAI Solutions</div>
                                    <div style="color: var(--muted); font-size:.92rem;">Recuperación</div>
                                </div>
                            </div>

                            <h2 class="headline">Recupera el acceso<br>sin perder tiempo.</h2>
                            <p class="sub">
                                Te enviaremos un enlace por correo si existe una cuenta con ese email.
                            </p>

                            <div class="tip">
                                <div class="dot"></div>
                                <div>
                                    <div style="font-weight:900;">Privacidad</div>
                                    <div style="color: var(--muted); font-size:.92rem; margin-top:2px;">
                                        El mensaje de éxito es el mismo exista o no exista el email.
                                    </div>
                                </div>
                            </div>

                            <div class="tip">
                                <div class="dot"></div>
                                <div>
                                    <div style="font-weight:900;">Seguridad</div>
                                    <div style="color: var(--muted); font-size:.92rem; margin-top:2px;">
                                        El enlace expira y solo se puede usar una vez.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="color: rgba(236,242,255,.55); font-size:.9rem;">
                            Volver: <a href="login.php">Login</a> · <a href="register.php">Registro</a>
                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-lg-6">
                    <div class="right">
                        <h3 class="form-title">Recuperar contraseña</h3>
                        <p class="hint">Introduce tu correo para recibir un enlace de restablecimiento.</p>

                        <?php if ($err): ?>
                            <div class="alert-glass error">❌ <?= htmlspecialchars($mensajeError, ENT_QUOTES, "UTF-8"); ?></div>
                        <?php endif; ?>

                        <?php if ($ok): ?>
                            <div class="alert-glass success">
                                ✅ Si el correo existe en AutomAI, te enviaremos un enlace para restablecer la contraseña.
                            </div>
                        <?php endif; ?>

                        <form id="forgotForm" action="forgotPassword.php" method="post">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="nombre@empresa.com"
                                    value="<?= $emailSafe; ?>" required>
                                <label for="email">Correo electrónico</label>
                            </div>

                            <button class="btn btn-glow w-100" type="submit">Enviar enlace</button>

                            <p class="fineprint">
                                <a href="login.php">← Volver al login</a>
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>