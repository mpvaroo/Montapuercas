<?php

declare(strict_types=1);

/**
 * Reportes-Historico.php (SOLO VISUAL)
 * - No sesiones
 * - No BD
 * - Datos MOCK en arrays (equivalentes a tablas futuras)
 * - Tabla "glass dark" + paginación visual
 */

$usuario = [
  "email" => "admin@empresa.com",
  "empresa" => "AutomAI Demo",
  "plan" => "Premium"
];

/* =========================================================
   B) MOCK DATA (equivalente a BD futura)
   ========================================================= */

$canales = [
  ["codigo" => "", "nombre" => "Todos"],
  ["codigo" => "WHATSAPP", "nombre" => "WhatsApp"],
  ["codigo" => "TELEGRAM", "nombre" => "Telegram"],
  ["codigo" => "WEB_CHAT", "nombre" => "Web Chat"],
  ["codigo" => "GMAIL", "nombre" => "Gmail"],
];

$filtro = [
  "desde" => "2025-10-01",
  "hasta" => "2025-10-31",
  "canal" => "",
  "buscar" => "",
  "cols" => [
    "fecha" => true,
    "canal" => true,
    "cliente" => true,
    "intencion" => true,
    "resultado" => true,
    "csat" => true,
  ]
];

$registros = [
  [
    "id" => 98231,
    "fecha_hora" => "31/10/2025 12:41",
    "canal" => "WhatsApp",
    "cliente" => "+34 600 123 456",
    "intencion" => "Reservas",
    "resultado" => "Resuelto por bot",
    "resultado_code" => "BOT",
    "csat" => "5/5",
    "mensaje" => "Quiero reservar una mesa para 4 a las 21:00…",
  ],
  [
    "id" => 98219,
    "fecha_hora" => "31/10/2025 11:03",
    "canal" => "Gmail",
    "cliente" => "marta@cliente.com",
    "intencion" => "Precios",
    "resultado" => "Derivado a agente",
    "resultado_code" => "AGENTE",
    "csat" => "4/5",
    "mensaje" => "¿Tenéis promoción para el plan Premium trimestral?",
  ],
  [
    "id" => 98177,
    "fecha_hora" => "31/10/2025 09:50",
    "canal" => "Web Chat",
    "cliente" => "anónimo",
    "intencion" => "Soporte",
    "resultado" => "Resuelto por bot",
    "resultado_code" => "BOT",
    "csat" => "—",
    "mensaje" => "El enlace de confirmación no me llega…",
  ],
  [
    "id" => 98002,
    "fecha_hora" => "30/10/2025 18:22",
    "canal" => "Telegram",
    "cliente" => "@ricardo",
    "intencion" => "Horarios",
    "resultado" => "Resuelto por bot",
    "resultado_code" => "BOT",
    "csat" => "5/5",
    "mensaje" => "¿Cuál es el horario del gimnasio el sábado?",
  ],
];

$paginacion = [
  "total" => 2384,
  "desde" => 1,
  "hasta" => 4,
  "pagina_actual" => 1,
  "paginas" => [1, 2, 3],
];

function pillClassResultado(string $code): string
{
  return match ($code) {
    "BOT" => "pill-ok",
    "AGENTE" => "pill-warn",
    "SIN_RESPUESTA" => "pill-bad",
    default => "pill-neutral",
  };
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reportes · Histórico | AutomAI Solutions</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg-main: #020617;
      --bg-elevated: rgba(15, 23, 42, 0.96);
      --bg-soft: rgba(15, 23, 42, 0.88);
      --accent: #38bdf8;
      --accent-strong: #0ea5e9;
      --accent-pink: #ec4899;
      --text-main: #f9fafb;
      --text-muted: rgba(203, 213, 245, .75);
      --border-subtle: rgba(148, 163, 184, 0.40);
      --shadow-strong: 0 18px 45px rgba(15, 23, 42, 0.9), 0 0 60px rgba(15, 23, 42, 0.95);
      --radius-lg: 1.5rem;
      --sidebar-w: 240px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      background:
        radial-gradient(circle at top left, #1d4ed8 0, transparent 45%),
        radial-gradient(circle at bottom right, #0f766e 0, transparent 45%),
        radial-gradient(circle at top right, #a855f7 0, transparent 40%),
        var(--bg-main);
      background-attachment: fixed;
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      color: var(--text-main);
      overflow-x: hidden;
    }

    body::before {
      content: "";
      position: fixed;
      inset: 0;
      backdrop-filter: blur(26px);
      pointer-events: none;
      z-index: -1;
    }

    /* Bootstrap overrides */
    .text-muted {
      color: var(--text-muted) !important;
    }

    .form-control,
    .form-select {
      border-radius: 1rem;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background: rgba(2, 6, 23, 0.45);
      color: rgba(249, 250, 251, .92);
      box-shadow: none !important;
    }

    .form-control::placeholder {
      color: rgba(203, 213, 245, .55);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: rgba(56, 189, 248, 0.65);
      box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.12) !important;
      background: rgba(2, 6, 23, 0.55);
      color: rgba(249, 250, 251, .95);
    }

    .form-check-input {
      background-color: rgba(2, 6, 23, 0.55);
      border: 1px solid rgba(148, 163, 184, 0.45);
    }

    .form-check-input:checked {
      background-color: rgba(56, 189, 248, 0.95);
      border-color: rgba(56, 189, 248, 0.95);
    }

    .form-check-label {
      color: rgba(249, 250, 251, .88);
    }

    .btn {
      border-radius: 999px;
      font-weight: 900;
      letter-spacing: .02em;
      border-width: 1px;
    }

    .btn:focus {
      box-shadow: none !important;
    }

    .btn-primary {
      background: linear-gradient(135deg, rgba(56, 189, 248, .95), rgba(14, 165, 233, .9));
      border: 1px solid rgba(56, 189, 248, .55);
      color: #04111f;
      box-shadow: 0 12px 26px rgba(56, 189, 248, 0.15);
    }

    .btn-primary:hover {
      filter: brightness(1.05);
      transform: translateY(-1px);
    }

    .btn-outline-secondary {
      color: rgba(249, 250, 251, .92) !important;
      border-color: rgba(148, 163, 184, .45) !important;
      background: rgba(2, 6, 23, 0.35) !important;
    }

    .btn-outline-secondary:hover {
      border-color: rgba(56, 189, 248, .55) !important;
      box-shadow: 0 0 0 1px rgba(56, 189, 248, .25);
      transform: translateY(-1px);
    }

    /* Sidebar */
    .sidebar {
      width: var(--sidebar-w);
      height: 100vh;
      position: fixed;
      inset-block: 0;
      left: 0;
      padding: 1.75rem 1.25rem;
      background: linear-gradient(145deg, rgba(15, 23, 42, 0.98), rgba(15, 23, 42, 0.90));
      border-right: 1px solid var(--border-subtle);
      box-shadow: 0 0 0 1px rgba(15, 23, 42, 1), 0 18px 45px rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(18px);
      z-index: 50;
    }

    .sidebar h4 {
      text-align: center;
      color: #e5f7ff;
      margin-bottom: 2rem;
      letter-spacing: .12em;
      font-size: .85rem;
      text-transform: uppercase;
      font-weight: 800;
    }

    .sidebar a {
      display: block;
      color: var(--text-muted);
      text-decoration: none;
      padding: 10px 16px;
      border-radius: .75rem;
      margin: 4px 6px;
      font-size: .92rem;
      transition: .18s;
      position: relative;
      font-weight: 800;
    }

    .sidebar a:hover {
      background: rgba(15, 23, 42, 0.95);
      color: #f9fafb;
      transform: translateX(1px);
    }

    .sidebar a.active {
      background: linear-gradient(135deg, rgba(56, 189, 248, 0.22), rgba(15, 23, 42, 0.96));
      color: #f9fafb;
      box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.4);
    }

    .sidebar a.text-danger {
      color: #fecaca !important;
      background: rgba(127, 29, 29, 0.15);
      border: 1px solid rgba(248, 113, 113, 0.35);
      margin-top: .75rem;
    }

    .sidebar a.text-danger:hover {
      background: rgba(153, 27, 27, 0.45);
      transform: none;
    }

    .sidebar hr {
      border-color: rgba(51, 65, 85, 0.8);
      margin: 1.1rem 0;
    }

    /* Topbar */
    .navbar-top {
      height: 72px;
      padding: 0 2.4rem;
      margin-left: var(--sidebar-w);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(90deg, rgba(6, 16, 40, .98), rgba(15, 23, 42, .96), rgba(6, 16, 40, .98));
      border-bottom: 1px solid rgba(148, 163, 184, .35);
      box-shadow: 0 12px 34px rgba(15, 23, 42, .95);
      position: sticky;
      top: 0;
      z-index: 40;
      backdrop-filter: blur(20px);
    }

    .navbar-title {
      display: flex;
      flex-direction: column;
      gap: .1rem;
    }

    .navbar-title span {
      font-size: .78rem;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: var(--text-muted);
    }

    .navbar-title h5 {
      margin: 0;
      font-size: 1.10rem;
      font-weight: 900;
      letter-spacing: .04em;
      text-transform: uppercase;
      color: var(--text-main);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: .75rem;
      font-size: .82rem;
      color: var(--text-muted);
    }

    .user-pill {
      display: flex;
      align-items: center;
      gap: .55rem;
      padding: .3rem .85rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.65);
      background: rgba(15, 23, 42, 0.8);
      color: rgba(249, 250, 251, .92);
    }

    /* Main */
    .main-content {
      margin-left: var(--sidebar-w);
      padding: 1.8rem 2.4rem 2.6rem;
    }

    .section-card {
      background: var(--bg-elevated);
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-subtle);
      box-shadow: var(--shadow-strong);
      backdrop-filter: blur(22px);
      position: relative;
      overflow: hidden;
    }

    .pill {
      display: inline-flex;
      align-items: center;
      gap: .45rem;
      padding: .28rem .85rem;
      border-radius: 999px;
      font-size: .7rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      border: 1px solid rgba(56, 189, 248, 0.65);
      color: #e0f2fe;
      background: rgba(15, 23, 42, 0.9);
      margin-bottom: .55rem;
      font-weight: 900;
    }

    /* Glass table */
    .glass-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      overflow: hidden;
      border-radius: 1.25rem;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background: rgba(2, 6, 23, 0.35);
      box-shadow: 0 18px 40px rgba(15, 23, 42, 0.75);
    }

    .glass-table thead th {
      background: linear-gradient(90deg, rgba(6, 16, 40, .98), rgba(15, 23, 42, .96));
      color: rgba(249, 250, 251, .92);
      border-bottom: 1px solid rgba(148, 163, 184, 0.30);
      font-size: .76rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      font-weight: 900;
      padding: .95rem .95rem;
      white-space: nowrap;
    }

    .glass-table tbody td {
      color: rgba(249, 250, 251, .9);
      border-bottom: 1px solid rgba(148, 163, 184, 0.20);
      padding: .95rem .95rem;
      vertical-align: middle;
      font-size: .92rem;
    }

    .glass-table tbody tr:hover td {
      background: rgba(56, 189, 248, 0.06);
    }

    .td-muted {
      color: rgba(203, 213, 245, .75) !important;
    }

    .msg-truncate {
      max-width: 320px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      color: rgba(203, 213, 245, .82);
    }

    /* Result pills */
    .pill-status {
      display: inline-flex;
      align-items: center;
      gap: .45rem;
      padding: .28rem .70rem;
      border-radius: 999px;
      font-size: .72rem;
      letter-spacing: .10em;
      text-transform: uppercase;
      font-weight: 900;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background: rgba(2, 6, 23, 0.35);
      color: rgba(249, 250, 251, .88);
      white-space: nowrap;
    }

    .pill-ok {
      border-color: rgba(34, 197, 94, .40);
      color: rgba(187, 247, 208, .95);
      background: rgba(22, 163, 74, .12);
    }

    .pill-warn {
      border-color: rgba(245, 158, 11, .40);
      color: rgba(254, 243, 199, .95);
      background: rgba(245, 158, 11, .10);
    }

    .pill-bad {
      border-color: rgba(239, 68, 68, .40);
      color: rgba(254, 202, 202, .95);
      background: rgba(239, 68, 68, .10);
    }

    .pill-neutral {
      border-color: rgba(148, 163, 184, .40);
      color: rgba(203, 213, 245, .90);
      background: rgba(148, 163, 184, .10);
    }

    /* Pagination glass */
    .pagination .page-link {
      border-radius: 999px !important;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background: rgba(2, 6, 23, 0.35);
      color: rgba(249, 250, 251, .90);
      margin: 0 .2rem;
      font-weight: 900;
    }

    .pagination .page-item.active .page-link {
      border-color: rgba(56, 189, 248, .55);
      box-shadow: 0 0 0 1px rgba(56, 189, 248, .25);
      background: rgba(56, 189, 248, .14);
      color: rgba(249, 250, 251, .95);
    }

    .pagination .page-item.disabled .page-link {
      opacity: .45;
    }

    footer {
      text-align: center;
      padding-top: 1.6rem;
      font-size: .78rem;
      color: var(--text-muted);
    }

    @media (max-width: 992px) {
      .navbar-top {
        margin-left: 0;
        padding: 0 1.2rem;
      }

      .main-content {
        margin-left: 0;
        padding: 1.2rem 1.2rem 2rem;
      }

      .sidebar {
        position: static;
        width: 100%;
        height: auto;
      }

      .msg-truncate {
        max-width: 220px;
      }
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <h4>AutomAI</h4>
    <a href="Dashboard.php">🏠 Dashboard</a>
    <a href="Chatbot-Configuracion.php">🤖 Configurar Chatbot</a>
    <a href="Integraciones-Canales.php">🔌 Integraciones</a>
    <a href="Reportes-Dashboard.php">📊 Reportes</a>
    <a href="Reportes-Filtros.php">🧪 Filtros y exportación</a>
    <a href="Reportes-Historico.php" class="active">🗂️ Histórico</a>
    <a href="Usuarios-Gestion.php">👥 Usuarios</a>
    <a href="Configuracion-General.php">⚙️ Configuración</a>
    <a href="Soporte-Ayuda.php">💬 Soporte</a>
    <hr>
    <a href="Logout.php" class="text-danger">🚪 Cerrar sesión</a>
  </aside>

  <!-- Topbar -->
  <header class="navbar-top">
    <div class="navbar-title">
      <span><?= htmlspecialchars($usuario["empresa"], ENT_QUOTES, "UTF-8"); ?></span>
      <h5>Reportes · Histórico</h5>
    </div>

    <div class="user-info">
      <div class="user-pill">
        <?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8"); ?>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="main-content">

    <!-- Filtros rápidos + columnas -->
    <div class="card section-card mb-3">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
          <div>
            <div class="pill">● Historial</div>
            <div class="text-muted small">Consulta el log de interacciones y resultados con filtros rápidos.</div>
          </div>
        </div>

        <form class="row g-3 align-items-end mt-1">
          <div class="col-md-3">
            <label class="form-label text-muted">Desde</label>
            <input type="date" class="form-control" value="<?= htmlspecialchars($filtro["desde"], ENT_QUOTES, "UTF-8"); ?>">
          </div>

          <div class="col-md-3">
            <label class="form-label text-muted">Hasta</label>
            <input type="date" class="form-control" value="<?= htmlspecialchars($filtro["hasta"], ENT_QUOTES, "UTF-8"); ?>">
          </div>

          <div class="col-md-3">
            <label class="form-label text-muted">Canal</label>
            <select class="form-select">
              <?php foreach ($canales as $c): ?>
                <option value="<?= htmlspecialchars($c["codigo"], ENT_QUOTES, "UTF-8"); ?>" <?= $c["codigo"] === $filtro["canal"] ? "selected" : ""; ?>>
                  <?= htmlspecialchars($c["nombre"], ENT_QUOTES, "UTF-8"); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label text-muted">Buscar</label>
            <input type="text" class="form-control" placeholder="cliente, mensaje, email..." value="<?= htmlspecialchars($filtro["buscar"], ENT_QUOTES, "UTF-8"); ?>">
          </div>

          <div class="col-12 d-flex flex-wrap gap-3 align-items-center mt-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="colFecha" <?= $filtro["cols"]["fecha"] ? "checked" : ""; ?>>
              <label class="form-check-label" for="colFecha">Fecha/Hora</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="colCanal" <?= $filtro["cols"]["canal"] ? "checked" : ""; ?>>
              <label class="form-check-label" for="colCanal">Canal</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="colCliente" <?= $filtro["cols"]["cliente"] ? "checked" : ""; ?>>
              <label class="form-check-label" for="colCliente">Cliente</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="colIntento" <?= $filtro["cols"]["intencion"] ? "checked" : ""; ?>>
              <label class="form-check-label" for="colIntento">Intención</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="colResultado" <?= $filtro["cols"]["resultado"] ? "checked" : ""; ?>>
              <label class="form-check-label" for="colResultado">Resultado</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="colCsat" <?= $filtro["cols"]["csat"] ? "checked" : ""; ?>>
              <label class="form-check-label" for="colCsat">CSAT</label>
            </div>

            <div class="ms-auto d-flex gap-2">
              <button class="btn btn-outline-secondary" type="button">Limpiar</button>
              <button class="btn btn-primary" type="button">Aplicar</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabla -->
    <div class="card section-card">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
          <div>
            <h5 class="m-0" style="font-weight:900; letter-spacing:.02em;">Registros</h5>
            <div class="text-muted small">Mostrando resultados del periodo seleccionado.</div>
          </div>
          <div class="text-muted small">Orden: <span style="color: rgba(249,250,251,.9); font-weight:900;">más recientes</span></div>
        </div>

        <div class="table-responsive">
          <table class="glass-table">
            <thead>
              <tr>
                <th>Fecha/Hora</th>
                <th>Canal</th>
                <th>Cliente</th>
                <th>Intención</th>
                <th>Resultado</th>
                <th>CSAT</th>
                <th>Mensaje</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($registros as $r): ?>
                <tr>
                  <td class="td-muted"><?= htmlspecialchars($r["fecha_hora"], ENT_QUOTES, "UTF-8"); ?></td>
                  <td><?= htmlspecialchars($r["canal"], ENT_QUOTES, "UTF-8"); ?></td>
                  <td><?= htmlspecialchars($r["cliente"], ENT_QUOTES, "UTF-8"); ?></td>
                  <td><?= htmlspecialchars($r["intencion"], ENT_QUOTES, "UTF-8"); ?></td>
                  <td>
                    <span class="pill-status <?= pillClassResultado($r["resultado_code"]); ?>">
                      <?= htmlspecialchars($r["resultado"], ENT_QUOTES, "UTF-8"); ?>
                    </span>
                  </td>
                  <td><?= htmlspecialchars($r["csat"], ENT_QUOTES, "UTF-8"); ?></td>
                  <td>
                    <div class="msg-truncate" title="<?= htmlspecialchars($r["mensaje"], ENT_QUOTES, "UTF-8"); ?>"><?= htmlspecialchars($r["mensaje"], ENT_QUOTES, "UTF-8"); ?></div>
                  </td>
                  <td class="text-end">
                    <button class="btn btn-outline-secondary btn-sm" type="button">Ver detalle</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
          <div class="text-muted small">
            Mostrando <?= (int)$paginacion["desde"]; ?>–<?= (int)$paginacion["hasta"]; ?> de <?= number_format((int)$paginacion["total"]); ?> registros
          </div>

          <ul class="pagination pagination-sm mb-0">
            <li class="page-item disabled"><a class="page-link" href="#">«</a></li>
            <?php foreach ($paginacion["paginas"] as $p): ?>
              <li class="page-item <?= $p === $paginacion["pagina_actual"] ? "active" : ""; ?>">
                <a class="page-link" href="#"><?= (int)$p; ?></a>
              </li>
            <?php endforeach; ?>
            <li class="page-item"><a class="page-link" href="#">»</a></li>
          </ul>
        </div>

      </div>
    </div>

    <footer>© 2026 AutomAI Solutions · Histórico</footer>
  </main>

</body>

</html>