<?php

declare(strict_types=1);

session_start();
require_once __DIR__ . "../modelos/Conexion.php";

$bd = (new Conexion())->getConexion();

$token = trim((string)($_GET["token"] ?? ""));
$tokenSafe = htmlspecialchars($token, ENT_QUOTES, "UTF-8");

$error = "";
$success = "";

$okToken = false;
$resetId = null;
$usuarioId = null;

if ($token === "") {
    $error = "Enlace inválido o incompleto. Solicita uno nuevo.";
} else {
    $tokenHash = hash("sha256", $token);

    $stmt = $bd->prepare("
        SELECT id, usuario_id, expires_at, used_at
        FROM password_reset
        WHERE token_hash = ?
        ORDER BY id DESC
        LIMIT 1
    ");
    $stmt->execute([$tokenHash]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$r) {
        $error = "Token inválido o no encontrado. Solicita un nuevo enlace.";
    } else if ($r["used_at"] !== null) {
        $error = "Este enlace ya fue usado. Solicita uno nuevo.";
    } else if (strtotime((string)$r["expires_at"]) < time()) {
        $error = "Token expirado. Solicita un nuevo enlace.";
    } else {
        $okToken = true;
        $resetId = (int)$r["id"];
        $usuarioId = (int)$r["usuario_id"];
    }
}

// Si token OK y vienen contraseñas por POST -> actualizar
if ($okToken && $_SERVER["REQUEST_METHOD"] === "POST") {
    $pass1 = (string)($_POST["pass1"] ?? "");
    $pass2 = (string)($_POST["pass2"] ?? "");

    if ($pass1 === "" || $pass2 === "") {
        $error = "Debes escribir la contraseña dos veces.";
    } else if ($pass1 !== $pass2) {
        $error = "Las contraseñas no coinciden.";
    } else if (strlen($pass1) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        $hash = password_hash($pass1, PASSWORD_BCRYPT);

        try {
            $bd->beginTransaction();

            $up = $bd->prepare("UPDATE usuario SET password_hash = ? WHERE id = ? AND estado = 'ACTIVO'");
            $up->execute([$hash, $usuarioId]);

            // Si por lo que sea el usuario no existe/está bloqueado, lo tratamos como error
            if ($up->rowCount() !== 1) {
                throw new RuntimeException("Usuario no válido para reset.");
            }

            $mark = $bd->prepare("UPDATE password_reset SET used_at = NOW() WHERE id = ? AND used_at IS NULL");
            $mark->execute([$resetId]);

            if ($mark->rowCount() !== 1) {
                throw new RuntimeException("Token ya usado.");
            }
            $bd->commit();

            // ✅ Marca para permitir entrar en SuccessPassword.php
            $_SESSION["password_reset_ok"] = 1;

            header("Location: successPassword.php");
            exit;
        } catch (Throwable $e) {
            if ($bd->inTransaction()) $bd->rollBack();
            $error = "No se pudo actualizar la contraseña. Solicita un nuevo enlace.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña | AutomAI</title>
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
            max-width: 52ch;
            line-height: 1.45;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 14px;
            padding: 10px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .10);
            background: rgba(10, 14, 30, .35);
            color: rgba(236, 242, 255, .78);
            font-size: .92rem;
            max-width: 100%;
        }

        .pill code {
            color: rgba(94, 234, 212, .95);
            background: rgba(94, 234, 212, .10);
            border: 1px solid rgba(94, 234, 212, .18);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: .85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 340px;
            display: inline-block;
            vertical-align: middle;
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

        .strength {
            margin-top: 10px;
            padding: 12px 12px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .10);
            background: rgba(10, 14, 30, .35);
            color: rgba(236, 242, 255, .72);
            font-size: .92rem;
        }

        .bars {
            display: flex;
            gap: 6px;
            margin-top: 8px;
        }

        .bar {
            height: 6px;
            flex: 1;
            border-radius: 999px;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .10);
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

            .pill code {
                max-width: 220px;
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
                                    <div style="color: var(--muted); font-size:.92rem;">Reset</div>
                                </div>
                            </div>

                            <h2 class="headline">Crea una contraseña<br>nueva y segura.</h2>
                            <p class="sub">
                                Esta pantalla valida el <strong>token</strong> contra la BD (expira / usado).
                            </p>

                            <?php if ($token !== ""): ?>
                                <div class="pill">
                                    <span style="opacity:.8;">Token:</span>
                                    <code><?= $tokenSafe; ?></code>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="color: rgba(236,242,255,.55); font-size:.9rem;">
                            Volver: <a href="forgotPassword.php">Recuperar</a> · <a href="login.php">Login</a>
                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-lg-6">
                    <div class="right">
                        <h3 class="form-title">Restablecer contraseña</h3>
                        <p class="hint">Introduce tu nueva contraseña y confírmala.</p>

                        <?php if ($error !== ""): ?>
                            <div class="alert-glass error"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></div>
                        <?php endif; ?>

                        <?php if ($success !== ""): ?>
                            <div class="alert-glass success"><?= htmlspecialchars($success, ENT_QUOTES, "UTF-8"); ?></div>
                        <?php endif; ?>

                        <?php if ($okToken): ?>
                            <!-- FORM REAL (POST) -->
                            <form id="resetForm" action="resetPassword.php?token=<?= $tokenSafe; ?>" method="post" autocomplete="off">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="pass1" placeholder="Nueva contraseña" required>
                                    <label for="password">Nueva contraseña</label>
                                </div>

                                <div class="form-floating mb-2">
                                    <input type="password" class="form-control" id="confirm" name="pass2" placeholder="Repite la contraseña" required>
                                    <label for="confirm">Repite la nueva contraseña</label>
                                </div>

                                <div class="strength">
                                    <div style="font-weight:900; color: rgba(236,242,255,.85);">Consejos rápidos</div>
                                    <div style="margin-top:4px;">
                                        8+ caracteres · mayúscula · número · símbolo
                                    </div>
                                    <div class="bars">
                                        <div class="bar" id="b1"></div>
                                        <div class="bar" id="b2"></div>
                                        <div class="bar" id="b3"></div>
                                        <div class="bar" id="b4"></div>
                                    </div>
                                </div>

                                <button class="btn btn-glow w-100 mt-3" type="submit">
                                    Guardar nueva contraseña
                                </button>

                                <p class="fineprint">
                                    <a href="login.php">← Volver al login</a>
                                </p>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        (function() {
            const p = document.getElementById('password');
            const b1 = document.getElementById('b1');
            const b2 = document.getElementById('b2');
            const b3 = document.getElementById('b3');
            const b4 = document.getElementById('b4');

            if (!p) return;

            function score(pass) {
                let s = 0;
                if (pass.length >= 8) s++;
                if (/[A-Z]/.test(pass)) s++;
                if (/[0-9]/.test(pass)) s++;
                if (/[^A-Za-z0-9]/.test(pass)) s++;
                return s;
            }

            function paint(s) {
                const bars = [b1, b2, b3, b4];
                bars.forEach((bar, idx) => {
                    bar.style.background = (idx < s) ?
                        'linear-gradient(90deg, var(--a1), var(--a2), var(--a3))' :
                        'rgba(255,255,255,.10)';
                    bar.style.borderColor = (idx < s) ? 'rgba(94,234,212,.18)' : 'rgba(255,255,255,.10)';
                });
            }

            p.addEventListener('input', () => paint(score(p.value)));
            paint(0);
        })();
    </script>
</body>

</html>