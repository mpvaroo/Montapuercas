<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . "/../controladores/Conexion.php";
require_once __DIR__ . "/../controladores/controladorAuth.php";

$bd = (new Conexion())->getConexion();

$ok  = isset($_GET["ok"]);
$err = isset($_GET["err"]);

$errores = [];

// -------------------------
// Cargar sectores y planes desde BD (visual)
/// -------------------------
$sectores = $bd->query("SELECT id, nombre FROM sector WHERE activo = 1 ORDER BY id")->fetchAll();
$planes   = $bd->query("SELECT id, codigo, nombre, precio_mensual FROM plan WHERE activo = 1 ORDER BY id")->fetchAll();

// -------------------------
// Prefill (para no perder datos)
/// -------------------------
$prefillCompany = (string)($_POST["company"] ?? "");
$prefillEmail   = (string)($_POST["email"] ?? "");
$prefillPhone   = (string)($_POST["phone"] ?? "");
$prefillSector  = (string)($_POST["sector_id"] ?? "");
$prefillPlan    = (string)($_POST["plan_id"] ?? "");

// -------------------------
// POST -> controlador
// -------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errores = controladorAuth::registrar();

    // si OK -> el controlador redirige a login.php?ok=1
    // si hay errores -> se muestran y el prefill ya está con $_POST
    $prefillCompany = (string)($_POST["company"] ?? "");
    $prefillEmail   = (string)($_POST["email"] ?? "");
    $prefillPhone   = (string)($_POST["phone"] ?? "");
    $prefillSector  = (string)($_POST["sector_id"] ?? "");
    $prefillPlan    = (string)($_POST["plan_id"] ?? "");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | AutomAI</title>
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
            overflow-x: hidden;
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
            width: min(96vw, 560px);
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

        .right {
            padding: 34px;
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

        .form-floating>.form-control,
        .form-select {
            background: rgba(10, 14, 30, .55);
            border: 1px solid rgba(255, 255, 255, .14);
            color: var(--text);
            border-radius: 14px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .06);
        }

        .form-floating>label {
            color: rgba(236, 242, 255, .55);
        }

        .form-select option {
            background: #0B1020;
            color: var(--text);
        }

        .plans {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 6px;
        }

        .plan {
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(10, 14, 30, .40);
            padding: 12px 12px;
            cursor: pointer;
            transition: transform .15s ease, background .15s ease, border-color .15s ease;
            user-select: none;
            height: 100%;
        }

        .plan:hover {
            transform: translateY(-2px);
            background: rgba(10, 14, 30, .55);
            border-color: rgba(94, 234, 212, .35);
        }

        .plan.selected {
            border-color: rgba(94, 234, 212, .70);
            box-shadow: 0 0 0 .16rem rgba(94, 234, 212, .10);
            background: rgba(10, 14, 30, .70);
        }

        .plan .name {
            font-weight: 900;
            margin: 0;
        }

        .plan .price {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: .90rem;
        }

        .pill {
            display: inline-block;
            margin-top: 10px;
            padding: 3px 9px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(255, 255, 255, .06);
            color: rgba(236, 242, 255, .78);
            font-size: .75rem;
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
            .right {
                padding: 28px 22px;
            }

            .plans {
                grid-template-columns: 1fr;
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
            <div class="right">
                <h3 class="form-title">Registro</h3>
                <p class="hint">Crea una empresa + su usuario inicial.</p>

                <?php if (!empty($errores)): ?>
                    <div class="alert-glass error">
                        <div style="font-weight:900; margin-bottom:6px;">❌ Revisa esto:</div>
                        <ul style="margin:0; padding-left:18px;">
                            <?php foreach ($errores as $e): ?>
                                <li><?= htmlspecialchars((string)$e, ENT_QUOTES, "UTF-8") ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($ok): ?>
                    <div class="alert-glass success">✅ Registro completado. Ya puedes iniciar sesión.</div>
                <?php endif; ?>

                <form action="" method="post" autocomplete="off">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="company" name="company" placeholder="Empresa"
                                    value="<?= htmlspecialchars($prefillCompany, ENT_QUOTES, "UTF-8") ?>">
                                <label for="company">Nombre de la empresa</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating mt-2">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                    value="<?= htmlspecialchars($prefillEmail, ENT_QUOTES, "UTF-8") ?>">
                                <label for="email">Correo electrónico</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mt-2">
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Teléfono"
                                    value="<?= htmlspecialchars($prefillPhone, ENT_QUOTES, "UTF-8") ?>">
                                <label for="phone">Teléfono</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mt-2">
                                <select class="form-select py-3" id="sector_id" name="sector_id">
                                    <option value="">Sector</option>
                                    <?php foreach ($sectores as $s): ?>
                                        <option value="<?= (int)$s["id"] ?>" <?= ($prefillSector === (string)$s["id"]) ? "selected" : "" ?>>
                                            <?= htmlspecialchars((string)$s["nombre"], ENT_QUOTES, "UTF-8") ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating mt-2">
                                <input type="password" class="form-control" id="pass" name="pass" placeholder="Contraseña">
                                <label for="pass">Contraseña (mín. 8)</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3" style="font-weight:900; color: rgba(236,242,255,.88);">
                        Elige un plan
                    </div>

                    <input type="hidden" name="plan_id" id="plan_id" value="<?= htmlspecialchars($prefillPlan, ENT_QUOTES, "UTF-8") ?>">

                    <div class="plans mt-2">
                        <?php foreach ($planes as $p): ?>
                            <div class="plan" data-plan-id="<?= (int)$p["id"] ?>">
                                <p class="name" style="color: rgba(94,234,212,.95);">
                                    <?= htmlspecialchars((string)$p["nombre"], ENT_QUOTES, "UTF-8") ?>
                                </p>
                                <p class="price"><?= number_format((float)$p["precio_mensual"], 2) ?> €/mes</p>
                                <span class="pill"><?= htmlspecialchars((string)$p["codigo"], ENT_QUOTES, "UTF-8") ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="btn btn-glow w-100 mt-3" type="submit">Crear cuenta</button>

                    <p class="fineprint">
                        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
                    </p>
                </form>
            </div>
        </section>
    </main>

    <script>
        (function() {
            const input = document.getElementById('plan_id');
            const items = Array.from(document.querySelectorAll('.plan'));

            function selectPlan(planId) {
                items.forEach(i => i.classList.toggle('selected', i.dataset.planId === String(planId)));
                input.value = planId || '';
            }

            items.forEach(i => {
                i.addEventListener('click', () => selectPlan(i.dataset.planId));
            });

            if (input.value) selectPlan(input.value);
        })();
    </script>

</body>

</html>