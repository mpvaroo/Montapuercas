<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . "/modelos/Conexion.php";

$bd = (new Conexion())->getConexion();

$ok  = isset($_GET["ok"]);
$err = isset($_GET["err"]);

$errores = [];
$prefillEmail = $_POST["email"] ?? "";

// Si ya hay sesión, fuera
if (isset($_SESSION["usuario_id"])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim((string)($_POST["email"] ?? ""));
    $pass  = (string)($_POST["password"] ?? "");

    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico inválido.";
    }

    if ($pass === "") {
        $errores[] = "La contraseña es obligatoria.";
    }

    if (empty($errores)) {
        $stmt = $bd->prepare("
            SELECT id, empresa_id, email, password_hash, estado
            FROM usuario
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);

        // Mensaje genérico (no damos pistas)
        if (!$u) {
            $errores[] = "Credenciales incorrectas.";
        } else if (($u["estado"] ?? "") !== "ACTIVO") {
            $errores[] = "Tu cuenta no está activa.";
        } else {
            $hash = (string)($u["password_hash"] ?? "");
            if (!password_verify($pass, $hash)) {
                $errores[] = "Credenciales incorrectas.";
            } else {
                // OK login
                $_SESSION["usuario_id"] = (int)$u["id"];
                $_SESSION["empresa_id"] = (int)$u["empresa_id"];
                $_SESSION["email"]      = (string)$u["email"];

                header("Location: dashboard.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | AutomAI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg0: #050814;
            --bg1: #080C1C;

            --card: rgba(255, 255, 255, .08);
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
            background:
                radial-gradient(1200px 800px at 15% 20%, rgba(94, 234, 212, .14), transparent 60%),
                radial-gradient(900px 600px at 85% 25%, rgba(167, 139, 250, .14), transparent 60%),
                radial-gradient(900px 650px at 55% 90%, rgba(96, 165, 250, .12), transparent 60%),
                linear-gradient(180deg, var(--bg0), var(--bg1));
            overflow: hidden;
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
            min-height: 540px;
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

        .brand h1 {
            margin: 0;
            font-size: 1.15rem;
            letter-spacing: .2px;
        }

        .brand p {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: .92rem;
        }

        .headline {
            margin: 22px 0 10px;
            font-size: 2rem;
            line-height: 1.05;
            letter-spacing: -.5px;
        }

        .sub {
            margin: 0;
            color: var(--muted);
            max-width: 42ch;
            line-height: 1.45;
        }

        .feature {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-top: 18px;
            padding: 12px 14px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .10);
            background: rgba(10, 14, 30, .35);
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
            margin: 0 0 12px;
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

        .form-check-label {
            color: rgba(236, 242, 255, .74);
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

        .row-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0 14px;
            gap: 10px;
        }

        a {
            color: rgba(94, 234, 212, .95);
            text-decoration: none;
        }

        a:hover {
            color: rgba(96, 165, 250, .95);
        }

        .fineprint {
            color: rgba(236, 242, 255, .60);
            font-size: .88rem;
            margin: 14px 0 0;
            text-align: center;
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
                <!-- Izquierda -->
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
                                    <h1>AutomAI Solutions</h1>
                                    <p>Panel web · UI Premium</p>
                                </div>
                            </div>

                            <h2 class="headline">Accede al panel</h2>
                            <p class="sub">
                                Login real con MySQL + sesiones.
                            </p>

                            <div class="feature">
                                <div class="dot"></div>
                                <div>
                                    <div style="font-weight:900;">Diseño “producto real”</div>
                                    <div style="color: var(--muted); font-size:.92rem; margin-top:2px;">
                                        Glass, gradientes, cards y tipografía limpia.
                                    </div>
                                </div>
                            </div>

                            <div class="feature">
                                <div class="dot"></div>
                                <div>
                                    <div style="font-weight:900;">Prototipo ahora sí seguro</div>
                                    <div style="color: var(--muted); font-size:.92rem; margin-top:2px;">
                                        El dashboard debe comprobar sesión (te lo preparo luego).
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="color: rgba(236,242,255,.55); font-size:.9rem;">
                            Siguiente páginas: <a href="register.php">Register</a> · <a href="ForgotPassword.php">Forgot</a>
                        </div>
                    </div>
                </div>

                <!-- Derecha -->
                <div class="col-lg-6">
                    <div class="right">
                        <h3 class="form-title">Iniciar sesión</h3>
                        <p class="hint">Introduce tu email y contraseña.</p>

                        <?php if (!empty($errores)): ?>
                            <div class="alert-glass error">
                                <div style="font-weight:900; margin-bottom:6px;">❌ Revisa esto:</div>
                                <ul style="margin:0; padding-left:18px;">
                                    <?php foreach ($errores as $e): ?>
                                        <li><?= htmlspecialchars($e, ENT_QUOTES, "UTF-8") ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if ($err): ?>
                            <div class="alert-glass error">❌ Error.</div>
                        <?php endif; ?>

                        <?php if ($ok): ?>
                            <div class="alert-glass success">✅ Cuenta creada. Ya puedes iniciar sesión.</div>
                        <?php endif; ?>

                        <form action="" method="post">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="nombre@empresa.com"
                                    value="<?= htmlspecialchars((string)$prefillEmail, ENT_QUOTES, "UTF-8") ?>">
                                <label for="email">Correo electrónico</label>
                            </div>

                            <div class="form-floating mb-2">
                                <input type="password" class="form-control" id="password" name="password" placeholder="********">
                                <label for="password">Contraseña</label>
                            </div>

                            <div class="row-links">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" disabled>
                                    <label class="form-check-label" for="remember">Recordarme (pendiente)</label>
                                </div>
                                <a href="forgotPassword.php">¿Olvidaste tu contraseña?</a>
                            </div>

                            <button class="btn btn-glow w-100" type="submit">Entrar</button>

                            <p class="fineprint">
                                ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>
                            </p>
                        </form>

                        <div class="mt-3 text-center" style="color: rgba(236,242,255,.55); font-size:.88rem;">
                            (Opcional) Acceso directo: <a href="dashboard.php">Dashboard</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>