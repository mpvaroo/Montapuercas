<?php
// gymbot/vistas/rutinas/mis_rutinas.php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/../_seguridad.php";
requiereLogin();

if (!isset($_SESSION["tipo_cuenta"]) || $_SESSION["tipo_cuenta"] !== "SOCIO") {
    header("Location: ../dashboard/index.php");
    exit;
}

require_once __DIR__ . "/../../controladores/controladorRutinas.php";
$datos = controladorRutinas::misRutinas();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mis rutinas - GymBot</title>
    <style>
        :root {
            --bg0: #070A12;
            --bg1: #0B1226;
            --bg2: #0D1B2A;
            --stroke: rgba(255, 255, 255, .14);
            --text: #F2F6FF;
            --muted: rgba(242, 246, 255, .74);
            --shadow: 0 18px 55px rgba(0, 0, 0, .55);
            --r2: 22px;
            --ease: cubic-bezier(.2, .8, .2, 1);
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: system-ui, "Segoe UI", Roboto, Arial;
            color: var(--text);
            min-height: 100vh;
            background:
                radial-gradient(900px 520px at 15% 10%, rgba(181, 123, 255, .28), transparent 55%),
                radial-gradient(820px 540px at 85% 20%, rgba(74, 163, 255, .26), transparent 55%),
                radial-gradient(900px 620px at 50% 90%, rgba(46, 204, 113, .16), transparent 60%),
                linear-gradient(180deg, var(--bg0), var(--bg1) 40%, var(--bg2));
        }

        .wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 26px 18px 60px
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: var(--r2);
            background: linear-gradient(180deg, rgba(255, 255, 255, .10), rgba(255, 255, 255, .06));
            border: 1px solid var(--stroke);
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px)
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border-radius: 999px;
            padding: 10px 14px;
            border: 1px solid rgba(255, 255, 255, .18);
            background: rgba(255, 255, 255, .08);
            color: var(--text);
            text-decoration: none;
            font-weight: 800;
            font-size: 13px;
            transition: transform .25s var(--ease), background .25s var(--ease);
        }

        .btn:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, .12)
        }

        .panel {
            margin-top: 16px;
            padding: 18px;
            border-radius: var(--r2);
            border: 1px solid rgba(255, 255, 255, .14);
            background: linear-gradient(180deg, rgba(255, 255, 255, .10), rgba(255, 255, 255, .06));
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px)
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, .10);
            text-align: left;
            font-size: 13px
        }

        th {
            font-size: 12px;
            color: rgba(242, 246, 255, .82)
        }

        .muted {
            color: var(--muted)
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="topbar">
            <div>
                <div style="font-weight:900;letter-spacing:.4px;">GymBot • Rutinas</div>
                <div class="muted" style="font-size:12px;margin-top:2px;">Tus rutinas guardadas</div>
            </div>
            <div style="display:flex;gap:10px;">
                <a class="btn" href="../dashboard/index.php">← Dashboard</a>
                <a class="btn" href="../rutinas/generar.php">+ Generar</a>
            </div>
        </div>

        <div class="panel">
            <h2 style="margin:0 0 10px;">Mis rutinas</h2>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Objetivo</th>
                        <th>Nivel</th>
                        <th>Duración</th>
                        <th>Origen</th>
                        <th>Creada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($datos["items"])) {
                        echo "<tr><td colspan='7' class='muted'>Aún no tienes rutinas guardadas.</td></tr>";
                    } else {
                        foreach ($datos["items"] as $r) {
                            echo "<tr>";
                            echo "<td>{$r["id"]}</td>";
                            echo "<td>{$r["nombre"]}</td>";
                            echo "<td>{$r["objetivo"]}</td>";
                            echo "<td>{$r["nivel"]}</td>";
                            echo "<td>{$r["duracion_min"]} min</td>";
                            echo "<td>{$r["origen"]}</td>";
                            echo "<td>{$r["creado_en"]}</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>