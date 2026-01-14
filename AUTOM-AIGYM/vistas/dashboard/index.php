<?php
// gymbot/vistas/dashboard/index.php
if (session_status() === PHP_SESSION_NONE) session_start();

/*
  Sesión recomendada (por si aún estás migrando):
  $_SESSION["tipo_cuenta"] = "SOCIO" | "USUARIO"
  $_SESSION["socio_id"] / $_SESSION["usuario_id"]
  $_SESSION["gimnasio_id"]
  $_SESSION["nombre"], $_SESSION["apellidos"]
  $_SESSION["roles"] = ['admin','monitor'] (solo usuario)
*/

$nombre = $_SESSION["nombre"] ?? "Usuario";
$apellidos = $_SESSION["apellidos"] ?? "";
$nombreCompleto = trim($nombre . " " . $apellidos);

$tipoCuenta = $_SESSION["tipo_cuenta"] ?? "SOCIO";
$roles = $_SESSION["roles"] ?? [];
$esAdmin = ($tipoCuenta === "USUARIO" && is_array($roles) && in_array("admin", $roles));

$hora = (int)date("H");
$saludo = "Bienvenido";
if ($hora >= 6 && $hora <= 12) $saludo = "Buenos días";
else if ($hora >= 13 && $hora <= 20) $saludo = "Buenas tardes";
else $saludo = "Buenas noches";

$frases = [
    "Hoy se entrena aunque sea poco.",
    "Constancia > motivación.",
    "Un paso más, otra semana más fuerte.",
    "Hazlo simple: calienta, trabaja, repite.",
    "Tu futuro yo te lo agradece."
];
$frase = $frases[array_rand($frases)];

// Rutas (mantengo tus href)
$rutas = [
    "reservar"     => "../reservas/nueva.php",
    "mis_reservas" => "../reservas/mis_reservas.php",
    "generar"      => "../rutinas/generar.php",
    "mis_rutinas"  => "../rutinas/mis_rutinas.php",
    "admin"        => "../admin/configuracion.php",
    "logout"       => "../auth/logout.php",
];

// Sección activa (si quieres marcarla según query)
$seccion = $_GET["s"] ?? "home"; // home | reservas | rutinas
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - GymBot</title>

    <style>
        :root {
            /* Premium Gym: negro/blanco + acento rojo/naranja */
            --bg: #070707;
            --panel: #0C0C0C;
            --panel2: #101010;
            --stroke: rgba(255, 255, 255, .10);
            --stroke2: rgba(255, 255, 255, .14);

            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .62);

            --accent: #FF2D2D;
            /* rojo */
            --accent2: #FF8A00;
            /* naranja */

            --shadow: 0 18px 60px rgba(0, 0, 0, .55);
            --shadow2: 0 10px 30px rgba(0, 0, 0, .45);

            --r: 18px;
            --r2: 22px;

            --ease: cubic-bezier(.2, .8, .2, 1);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(800px 420px at 10% 0%, rgba(255, 45, 45, .10), transparent 55%),
                radial-gradient(900px 520px at 90% 10%, rgba(255, 138, 0, .10), transparent 60%),
                radial-gradient(900px 520px at 50% 100%, rgba(255, 255, 255, .06), transparent 62%),
                linear-gradient(180deg, #050505, var(--bg));
            overflow: hidden;
        }

        /* Granulado premium */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .10;
            background-image: radial-gradient(rgba(255, 255, 255, .22) 1px, transparent 1px);
            background-size: 28px 28px;
            mask-image: radial-gradient(closest-side at 50% 18%, #000 35%, transparent 85%);
        }

        /* Layout ChatGPT-like */
        .app {
            height: 100%;
            display: grid;
            grid-template-columns: 290px 1fr;
        }

        /* Sidebar */
        .sidebar {
            height: 100%;
            background: linear-gradient(180deg, rgba(255, 255, 255, .04), rgba(255, 255, 255, .02));
            border-right: 1px solid var(--stroke);
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            overflow: hidden;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 12px;
            border-radius: var(--r2);
            border: 1px solid var(--stroke);
            background: rgba(0, 0, 0, .35);
            box-shadow: var(--shadow2);
        }

        .logo {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .14);
            background:
                radial-gradient(circle at 30% 25%, rgba(255, 255, 255, .20), transparent 55%),
                linear-gradient(135deg, rgba(255, 45, 45, .92), rgba(255, 138, 0, .90));
        }

        .logo::after {
            content: "";
            position: absolute;
            inset: -35%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .38), transparent);
            transform: rotate(25deg) translateX(-30%);
            animation: shine 3.6s var(--ease) infinite;
            opacity: .0;
        }

        @keyframes shine {
            0% {
                transform: rotate(25deg) translateX(-40%);
                opacity: 0;
            }

            25% {
                opacity: .85;
            }

            55% {
                opacity: 0;
            }

            100% {
                transform: rotate(25deg) translateX(60%);
                opacity: 0;
            }
        }

        .brand h1 {
            margin: 0;
            font-size: 14px;
            letter-spacing: .6px;
            font-weight: 850;
            line-height: 1.1;
        }

        .brand p {
            margin: 3px 0 0;
            font-size: 12px;
            color: var(--muted);
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 6px 4px;
            overflow: auto;
        }

        .navSectionTitle {
            margin: 10px 8px 6px;
            font-size: 11px;
            letter-spacing: .9px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .48);
        }

        .nav a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 12px;
            border-radius: 14px;
            text-decoration: none;
            color: var(--text);
            border: 1px solid transparent;
            background: rgba(255, 255, 255, .03);
            transition: transform .18s var(--ease), background .18s var(--ease), border-color .18s var(--ease);
        }

        .nav a:hover {
            transform: translateY(-1px);
            background: rgba(255, 255, 255, .05);
            border-color: rgba(255, 255, 255, .10);
        }

        .nav .left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .ico {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(0, 0, 0, .35);
            border: 1px solid rgba(255, 255, 255, .10);
            box-shadow: 0 10px 22px rgba(0, 0, 0, .25);
            flex: 0 0 auto;
        }

        .label {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .label strong {
            font-size: 13px;
            font-weight: 820;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .label span {
            font-size: 12px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .k {
            font-size: 11px;
            color: rgba(255, 255, 255, .55);
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(0, 0, 0, .28);
            padding: 5px 8px;
            border-radius: 999px;
            flex: 0 0 auto;
        }

        .nav a.primary {
            background: linear-gradient(135deg, rgba(255, 45, 45, .16), rgba(255, 138, 0, .14));
            border-color: rgba(255, 255, 255, .10);
        }

        .nav a.primary .ico {
            border-color: rgba(255, 138, 0, .24);
        }

        .nav a.danger {
            background: rgba(255, 45, 45, .09);
            border-color: rgba(255, 45, 45, .18);
        }

        /* Footer sidebar */
        .sideFooter {
            margin-top: auto;
            padding: 12px 12px;
            border-radius: var(--r2);
            border: 1px solid var(--stroke);
            background: rgba(0, 0, 0, .28);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .status {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 12px;
            white-space: nowrap;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: rgba(255, 138, 0, .95);
            box-shadow: 0 0 0 6px rgba(255, 138, 0, .14);
        }

        /* Main */
        .main {
            height: 100%;
            overflow: auto;
            padding: 22px 22px 40px;
        }

        .topRow {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            max-width: 1100px;
            margin: 0 auto;
            padding-top: 8px;
        }

        .hello {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .hello h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: .2px;
        }

        .hello p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            max-width: 70ch;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(0, 0, 0, .30);
            color: rgba(255, 255, 255, .78);
            font-size: 12px;
            box-shadow: var(--shadow2);
            white-space: nowrap;
            height: fit-content;
        }

        .pill b {
            color: rgba(255, 255, 255, .90);
            font-weight: 850;
        }

        /* Centro tipo ChatGPT: un panel con acciones */
        .center {
            max-width: 1100px;
            margin: 18px auto 0;
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .panel {
            border-radius: var(--r2);
            border: 1px solid var(--stroke2);
            background: linear-gradient(180deg, rgba(255, 255, 255, .05), rgba(255, 255, 255, .03));
            box-shadow: var(--shadow);
            padding: 18px;
            overflow: hidden;
            position: relative;
        }

        .panel::before {
            content: "";
            position: absolute;
            inset: -2px;
            pointer-events: none;
            opacity: .55;
            background:
                radial-gradient(520px 220px at 10% 10%, rgba(255, 45, 45, .18), transparent 55%),
                radial-gradient(520px 220px at 90% 20%, rgba(255, 138, 0, .16), transparent 55%);
        }

        .panelInner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .panelTitle {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 280px;
            flex: 1 1 420px;
        }

        .panelTitle h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 900;
            letter-spacing: .2px;
        }

        .panelTitle p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .quick {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
            flex: 1 1 360px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 999px;
            text-decoration: none;
            color: var(--text);
            font-size: 13px;
            font-weight: 850;
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(0, 0, 0, .35);
            transition: transform .18s var(--ease), border-color .18s var(--ease), background .18s var(--ease);
            box-shadow: 0 14px 28px rgba(0, 0, 0, .28);
        }

        .btn:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, .22);
            background: rgba(255, 255, 255, .06);
        }

        .btn.primary {
            background: linear-gradient(135deg, rgba(255, 45, 45, .22), rgba(255, 138, 0, .20));
            border-color: rgba(255, 255, 255, .16);
        }

        .btn.primary:hover {
            border-color: rgba(255, 255, 255, .26);
        }

        /* Grid de “tiles” tipo ChatGPT */
        .tiles {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 12px;
        }

        .tile {
            grid-column: span 6;
            border-radius: var(--r2);
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(0, 0, 0, .25);
            box-shadow: var(--shadow2);
            padding: 16px;
            text-decoration: none;
            color: var(--text);
            position: relative;
            overflow: hidden;
            transform: translateY(10px);
            opacity: 0;
            transition: transform .35s var(--ease), border-color .35s var(--ease), background .35s var(--ease);
        }

        .tile::after {
            content: "";
            position: absolute;
            left: 14px;
            right: 14px;
            top: 12px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            opacity: .9;
            pointer-events: none;
        }

        .tile:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, .20);
            background: rgba(255, 255, 255, .04);
        }

        .tile.is-in {
            animation: tileIn .6s var(--ease) forwards;
        }

        @keyframes tileIn {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .tileTop {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-top: 6px;
        }

        .tileIco {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: rgba(0, 0, 0, .40);
            border: 1px solid rgba(255, 255, 255, .12);
            box-shadow: 0 14px 24px rgba(0, 0, 0, .25);
            font-size: 20px;
        }

        .tileArrow {
            font-size: 18px;
            color: rgba(255, 255, 255, .65);
            transform: translateX(0);
            transition: transform .25s var(--ease), color .25s var(--ease);
        }

        .tile:hover .tileArrow {
            transform: translateX(4px);
            color: rgba(255, 255, 255, .92);
        }

        .tile h4 {
            margin: 12px 0 6px;
            font-size: 15px;
            font-weight: 900;
            letter-spacing: .2px;
        }

        .tile p {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
            line-height: 1.45;
        }

        .hint {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, .55);
            font-size: 12px;
        }

        .hint .bar {
            height: 1px;
            flex: 1;
            background: rgba(255, 255, 255, .10);
        }

        /* Responsive */
        @media (max-width: 980px) {
            .app {
                grid-template-columns: 1fr;
            }

            .sidebar {
                height: auto;
                border-right: none;
                border-bottom: 1px solid var(--stroke);
            }

            body {
                overflow: auto;
            }
        }

        @media (max-width: 720px) {
            .tile {
                grid-column: span 12;
            }

            .topRow {
                flex-direction: column;
                align-items: flex-start;
            }

            .pill {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }

            .tile {
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="app">

        <!-- SIDEBAR estilo ChatGPT -->
        <aside class="sidebar" aria-label="Navegación principal">
            <div class="brand">
                <div class="logo" aria-hidden="true"></div>
                <div>
                    <h1>GymBot</h1>
                    <p>Reservas • Rutinas • IA</p>
                </div>
            </div>

            <nav class="nav">
                <div class="navSectionTitle">Acciones</div>

                <a class="primary" href="<?php echo $rutas["reservar"]; ?>">
                    <div class="left">
                        <div class="ico" aria-hidden="true">🏋️</div>
                        <div class="label">
                            <strong>Reservar</strong>
                            <span>Nueva clase/servicio</span>
                        </div>
                    </div>
                    <span class="k">R</span>
                </a>

                <a href="<?php echo $rutas["mis_reservas"]; ?>">
                    <div class="left">
                        <div class="ico" aria-hidden="true">📅</div>
                        <div class="label">
                            <strong>Mis reservas</strong>
                            <span>Agenda y estado</span>
                        </div>
                    </div>
                    <span class="k">M</span>
                </a>

                <div class="navSectionTitle">Entreno</div>

                <a href="<?php echo $rutas["generar"]; ?>">
                    <div class="left">
                        <div class="ico" aria-hidden="true">⚡</div>
                        <div class="label">
                            <strong>Generar rutina</strong>
                            <span>Por objetivo y nivel</span>
                        </div>
                    </div>
                    <span class="k">G</span>
                </a>

                <a href="<?php echo $rutas["mis_rutinas"]; ?>">
                    <div class="left">
                        <div class="ico" aria-hidden="true">🗒️</div>
                        <div class="label">
                            <strong>Mis rutinas</strong>
                            <span>Planes guardados</span>
                        </div>
                    </div>
                    <span class="k">P</span>
                </a>

                <?php if ($esAdmin) { ?>
                    <div class="navSectionTitle">Admin</div>

                    <a href="<?php echo $rutas["admin"]; ?>">
                        <div class="left">
                            <div class="ico" aria-hidden="true">🛠️</div>
                            <div class="label">
                                <strong>Configuración</strong>
                                <span>Contenido IA y sistema</span>
                            </div>
                        </div>
                        <span class="k">A</span>
                    </a>
                <?php } ?>

                <div class="navSectionTitle">Sesión</div>

                <a class="danger" href="<?php echo $rutas["logout"]; ?>">
                    <div class="left">
                        <div class="ico" aria-hidden="true">🚪</div>
                        <div class="label">
                            <strong>Salir</strong>
                            <span>Cerrar sesión</span>
                        </div>
                    </div>
                    <span class="k">⎋</span>
                </a>
            </nav>

            <div class="sideFooter">
                <div class="status" title="Estado del sistema">
                    <span class="dot" aria-hidden="true"></span>
                    <span>Sesión activa</span>
                </div>
                <span style="color: rgba(255,255,255,.55); font-size:12px;">
                    <?php echo $esAdmin ? "Admin" : ($tipoCuenta === "SOCIO" ? "Socio" : "Usuario"); ?>
                </span>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="main">
            <div class="topRow">
                <div class="hello">
                    <h2><?php echo $saludo; ?>, <?php echo htmlspecialchars($nombreCompleto, ENT_QUOTES, "UTF-8"); ?></h2>
                    <p><?php echo htmlspecialchars($frase, ENT_QUOTES, "UTF-8"); ?></p>
                </div>

                <div class="pill">
                    <span aria-hidden="true"><?php echo $esAdmin ? "🛠️" : "👤"; ?></span>
                    <span>Cuenta: <b><?php echo $esAdmin ? "Administrador" : ($tipoCuenta === "SOCIO" ? "Socio" : "Usuario"); ?></b></span>
                </div>
            </div>

            <section class="center">
                <!-- Panel superior tipo “entrada ChatGPT” -->
                <div class="panel">
                    <div class="panelInner">
                        <div class="panelTitle">
                            <h3>¿Qué quieres hacer hoy?</h3>
                            <p>
                                Reserva una clase, revisa tu agenda o genera una rutina con estética premium y navegación rápida.
                                Todo lo importante, sin cards “infantiles”.
                            </p>
                            <div class="hint">
                                <span>Tip</span>
                                <span class="bar"></span>
                                <span>menos ruido, más acción</span>
                            </div>
                        </div>

                        <div class="quick">
                            <a class="btn primary" href="<?php echo $rutas["reservar"]; ?>">
                                <span aria-hidden="true">🏋️</span> Reservar
                            </a>
                            <a class="btn" href="<?php echo $rutas["mis_reservas"]; ?>">
                                <span aria-hidden="true">📅</span> Mis reservas
                            </a>
                            <a class="btn" href="<?php echo $rutas["generar"]; ?>">
                                <span aria-hidden="true">⚡</span> Generar rutina
                            </a>
                            <a class="btn" href="<?php echo $rutas["mis_rutinas"]; ?>">
                                <span aria-hidden="true">🗒️</span> Mis rutinas
                            </a>
                            <?php if ($esAdmin) { ?>
                                <a class="btn" href="<?php echo $rutas["admin"]; ?>">
                                    <span aria-hidden="true">🛠️</span> Configuración
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Tiles principales (limpio pero potente) -->
                <div class="tiles" aria-label="Accesos rápidos">
                    <a class="tile js-tile" data-delay="60" href="<?php echo $rutas["reservar"]; ?>">
                        <div class="tileTop">
                            <div class="tileIco" aria-hidden="true">🏋️</div>
                            <div class="tileArrow" aria-hidden="true">›</div>
                        </div>
                        <h4>Reservar clase/servicio</h4>
                        <p>Elige actividad, día y hora. Diseñado para ir rápido, sin fricción.</p>
                    </a>

                    <a class="tile js-tile" data-delay="120" href="<?php echo $rutas["mis_reservas"]; ?>">
                        <div class="tileTop">
                            <div class="tileIco" aria-hidden="true">📅</div>
                            <div class="tileArrow" aria-hidden="true">›</div>
                        </div>
                        <h4>Mis reservas</h4>
                        <p>Tu agenda, estados y próximas sesiones en una vista clara.</p>
                    </a>

                    <a class="tile js-tile" data-delay="180" href="<?php echo $rutas["generar"]; ?>">
                        <div class="tileTop">
                            <div class="tileIco" aria-hidden="true">⚡</div>
                            <div class="tileArrow" aria-hidden="true">›</div>
                        </div>
                        <h4>Generar rutina</h4>
                        <p>Pide una rutina por objetivo y nivel. Guardada para ti.</p>
                    </a>

                    <a class="tile js-tile" data-delay="240" href="<?php echo $rutas["mis_rutinas"]; ?>">
                        <div class="tileTop">
                            <div class="tileIco" aria-hidden="true">🗒️</div>
                            <div class="tileArrow" aria-hidden="true">›</div>
                        </div>
                        <h4>Mis rutinas</h4>
                        <p>Reutiliza tus planes y entrena sin pensarlo demasiado.</p>
                    </a>

                    <?php if ($esAdmin) { ?>
                        <a class="tile js-tile" data-delay="300" href="<?php echo $rutas["admin"]; ?>">
                            <div class="tileTop">
                                <div class="tileIco" aria-hidden="true">🛠️</div>
                                <div class="tileArrow" aria-hidden="true">›</div>
                            </div>
                            <h4>Configuración (Admin)</h4>
                            <p>Contenido del gimnasio para la IA, usuarios y ajustes del sistema.</p>
                        </a>
                    <?php } ?>
                </div>
            </section>
        </main>

    </div>

    <script>
        // Entrada escalonada sin librerías
        (function() {
            const tiles = document.querySelectorAll(".js-tile");
            let extra = 0;
            tiles.forEach((t) => {
                const d = parseInt(t.getAttribute("data-delay") || "0", 10);
                t.style.animationDelay = ((d / 1000) + extra).toFixed(2) + "s";
                extra += 0.02;
                requestAnimationFrame(() => t.classList.add("is-in"));
            });
        })();
    </script>
</body>

</html>