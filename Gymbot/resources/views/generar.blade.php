<?php
// gymbot/vistas/rutinas/generar.php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/../_seguridad.php";
requiereLogin();

// SOLO SOCIO
if (!isset($_SESSION["tipo_cuenta"]) || $_SESSION["tipo_cuenta"] !== "SOCIO") {
    header("Location: ../dashboard/index.php");
    exit;
}

require_once __DIR__ . "/../../controladores/controladorRutinas.php";

$datos = controladorRutinas::estadoInicialGenerar();

if (isset($_POST["accion"])) {
    if ($_POST["accion"] === "generar") $datos = controladorRutinas::generarPreview();
    else if ($_POST["accion"] === "guardar") $datos = controladorRutinas::guardarRutina();
}

$musculosOpc = ["PECHO", "ESPALDA", "HOMBRO", "BICEPS", "TRICEPS", "PIERNA", "GLUTEOS", "CORE"];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Generar rutina - GymBot</title>
    <style>
        /* Reutiliza la vibra del dashboard */
        :root {
            --bg0: #070A12;
            --bg1: #0B1226;
            --bg2: #0D1B2A;
            --glass: rgba(255, 255, 255, .08);
            --stroke: rgba(255, 255, 255, .14);
            --text: #F2F6FF;
            --muted: rgba(242, 246, 255, .74);
            --orange: #F39C12;
            --purple: #B57BFF;
            --blue: #4AA3FF;
            --green: #2ECC71;
            --red: #FF4D4D;
            --shadow: 0 18px 55px rgba(0, 0, 0, .55);
            --r: 18px;
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

        .grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 12px
        }

        .col6 {
            grid-column: span 6
        }

        .col12 {
            grid-column: span 12
        }

        label {
            display: block;
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 6px
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(0, 0, 0, .18);
            color: var(--text);
        }

        textarea {
            min-height: 90px;
            resize: vertical
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 10px
        }

        .chip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 999px;
            background: rgba(0, 0, 0, .18);
            border: 1px solid rgba(255, 255, 255, .12);
            color: rgba(242, 246, 255, .85);
            font-size: 13px;
        }

        .chip input {
            width: auto
        }

        .msg {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 14px;
            background: rgba(46, 204, 113, .10);
            border: 1px solid rgba(46, 204, 113, .25)
        }

        .err {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 14px;
            background: rgba(255, 77, 77, .10);
            border: 1px solid rgba(255, 77, 77, .25)
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px
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

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px
        }

        .primary {
            border-color: rgba(243, 156, 18, .35);
            background: rgba(243, 156, 18, .12)
        }

        .primary:hover {
            background: rgba(243, 156, 18, .16)
        }

        @media(max-width:900px) {
            .col6 {
                grid-column: span 12
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="topbar">
            <div>
                <div style="font-weight:900;letter-spacing:.4px;">GymBot • Rutinas</div>
                <div style="color:rgba(242,246,255,.75);font-size:12px;margin-top:2px;">Genera una rutina y guárdala</div>
            </div>
            <div style="display:flex;gap:10px;">
                <a class="btn" href="../dashboard/index.php">← Dashboard</a>
                <a class="btn" href="../rutinas/mis_rutinas.php">Mis rutinas</a>
            </div>
        </div>

        <?php if (!empty($datos["mensaje"])) { ?>
            <div class="msg"><?php echo $datos["mensaje"]; ?></div>
        <?php } ?>
        <?php if (!empty($datos["errores"])) { ?>
            <div class="err">
                <?php foreach ($datos["errores"] as $e) echo "<div>$e</div>"; ?>
            </div>
        <?php } ?>

        <div class="panel">
            <h2 style="margin:0 0 12px;">Generar rutina</h2>

            <form method="post">
                <input type="hidden" name="accion" value="generar" />

                <div class="grid">
                    <div class="col12">
                        <label>Nombre (opcional)</label>
                        <input type="text" name="nombre" value="<?php echo $datos["nombre"]; ?>" placeholder="Ej: Push 60' (principiante)" />
                    </div>

                    <div class="col6">
                        <label>Objetivo</label>
                        <select name="objetivo">
                            <?php
                            foreach (["HIPERTROFIA", "FUERZA", "RESISTENCIA", "PERDIDA_GRASA"] as $o) {
                                $s = ($datos["objetivo"] === $o) ? "selected" : "";
                                echo "<option value='$o' $s>$o</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col6">
                        <label>Nivel</label>
                        <select name="nivel">
                            <?php
                            foreach (["PRINCIPIANTE", "INTERMEDIO", "AVANZADO"] as $n) {
                                $s = ($datos["nivel"] === $n) ? "selected" : "";
                                echo "<option value='$n' $s>$n</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col6">
                        <label>Duración (min)</label>
                        <input type="number" name="duracion_min" min="20" max="180" value="<?php echo (int)$datos["duracion_min"]; ?>" />
                    </div>

                    <div class="col6">
                        <label>Notas (opcional)</label>
                        <input type="text" name="notas" value="<?php echo $datos["notas"]; ?>" placeholder="Ej: evita press militar por hombro" />
                    </div>

                    <div class="col12">
                        <label>Grupos musculares</label>
                        <div class="chips">
                            <?php foreach ($musculosOpc as $m) {
                                $checked = in_array($m, $datos["musculos"]) ? "checked" : "";
                                echo "<label class='chip'><input type='checkbox' name='musculos[]' value='$m' $checked> $m</label>";
                            } ?>
                        </div>
                    </div>

                    <div class="col12 actions">
                        <button class="btn primary" type="submit">⚡ Generar</button>
                        <a class="btn" href="generar.php">Reset</a>
                    </div>
                </div>
            </form>

            <?php if (is_array($datos["preview"]) && !empty($datos["preview"])) { ?>
                <hr style="border:none;border-top:1px solid rgba(255,255,255,.10);margin:18px 0;" />

                <h3 style="margin:0 0 10px;">Vista previa</h3>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ejercicio</th>
                            <th>Series</th>
                            <th>Reps</th>
                            <th>Descanso</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($datos["preview"] as $ej) { ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $ej["ejercicio"]; ?></td>
                                <td><?php echo (int)$ej["series"]; ?></td>
                                <td><?php echo $ej["reps"]; ?></td>
                                <td><?php echo (int)$ej["descanso_seg"]; ?>s</td>
                                <td><?php echo $ej["notas"] ?? ""; ?></td>
                            </tr>
                        <?php $i++;
                        } ?>
                    </tbody>
                </table>

                <form method="post" style="margin-top:12px;">
                    <input type="hidden" name="accion" value="guardar" />
                    <input type="hidden" name="nombre" value="<?php echo $datos["nombre"]; ?>" />
                    <input type="hidden" name="objetivo" value="<?php echo $datos["objetivo"]; ?>" />
                    <input type="hidden" name="nivel" value="<?php echo $datos["nivel"]; ?>" />
                    <input type="hidden" name="duracion_min" value="<?php echo (int)$datos["duracion_min"]; ?>" />
                    <input type="hidden" name="notas" value="<?php echo $datos["notas"]; ?>" />
                    <input type="hidden" name="preview_json" value='<?php echo json_encode($datos["preview"], JSON_UNESCAPED_UNICODE); ?>' />
                    <button class="btn primary" type="submit">💾 Guardar rutina</button>
                </form>
            <?php } ?>
        </div>
    </div>
</body>

</html>