<?php

declare(strict_types=1);

/**
 * Reportes-Filtros.php (SOLO VISUAL)
 * - No sesiones
 * - No BD
 * - Datos MOCK en arrays (equivalentes a tablas futuras)
 * - UI premium dark neon glass consistente con el dashboard
 */

$usuario = [
  "email" => "admin@empresa.com",
  "empresa" => "AutomAI Demo",
  "plan" => "Premium"
];

/* =========================================================
   B) MOCK DATA (equivalente a BD futura)
   ========================================================= */

$presets = [
  ["id" => 0, "nombre" => "—"],
  ["id" => 1, "nombre" => "Mensual · Todos los canales"],
  ["id" => 2, "nombre" => "WhatsApp · Últimos 7 días"],
  ["id" => 3, "nombre" => "Leads e-commerce · 30 días"],
];

$canales = [
  ["codigo" => "", "nombre" => "Todos"],
  ["codigo" => "WHATSAPP", "nombre" => "WhatsApp"],
  ["codigo" => "TELEGRAM", "nombre" => "Telegram"],
  ["codigo" => "WEB_CHAT", "nombre" => "Web Chat"],
  ["codigo" => "GMAIL", "nombre" => "Gmail"],
];

$sectores = [
  ["codigo" => "", "nombre" => "Todos"],
  ["codigo" => "RESTAURANTE", "nombre" => "Restaurante"],
  ["codigo" => "GIMNASIO", "nombre" => "Gimnasio"],
  ["codigo" => "ECOMMERCE", "nombre" => "E-commerce"],
  ["codigo" => "ACADEMIA", "nombre" => "Academia"],
];

$intenciones = [
  ["codigo" => "", "nombre" => "Todas"],
  ["codigo" => "RESERVAS", "nombre" => "Reservas/Citas"],
  ["codigo" => "PRECIOS", "nombre" => "Precios"],
  ["codigo" => "SOPORTE", "nombre" => "Soporte"],
  ["codigo" => "HORARIOS", "nombre" => "Horarios"],
];

$resultados = [
  ["codigo" => "", "nombre" => "Todos"],
  ["codigo" => "BOT", "nombre" => "Resuelto por bot"],
  ["codigo" => "AGENTE", "nombre" => "Derivado a agente"],
  ["codigo" => "SIN_RESPUESTA", "nombre" => "Sin respuesta"],
];

$csatMinOpciones = [
  ["codigo" => "", "nombre" => "—"],
  ["codigo" => "3", "nombre" => "3+"],
  ["codigo" => "4", "nombre" => "4+"],
  ["codigo" => "4.5", "nombre" => "4.5+"],
];

$filtro = [
  "preset_id" => 1,
  "desde" => "2025-10-01",
  "hasta" => "2025-10-31",
  "canal" => "",
  "sector" => "",
  "intencion" => "",
  "resultado" => "",
  "csat_min" => "4",
  "t_resp_max" => ""
];

/** KPIs resumidos (tabla futura: reporte_kpi_serie o reporte_resumen_rango) */
$kpiResumen = [
  "interacciones" => 2314,
  "csat_medio" => 4.5,
  "resuelto_bot_pct" => 72,
  "t_resp_medio_s" => 5.8,
];

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reportes · Filtros y exportación | AutomAI Solutions</title>

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

    * { box-sizing: border-box; }

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
    .text-muted { color: var(--text-muted) !important; }

    .btn {
      border-radius: 999px;
      font-weight: 900;
      letter-spacing: .02em;
      border-width: 1px;
    }
    .btn:focus { box-shadow: none !important; }

    .btn-primary {
      background: linear-gradient(135deg, rgba(56,189,248,.95), rgba(14,165,233,.9));
      border: 1px solid rgba(56,189,248,.55);
      color: #04111f;
      box-shadow: 0 12px 26px rgba(56, 189, 248, 0.15);
    }
    .btn-primary:hover { filter: brightness(1.05); transform: translateY(-1px); }

    .btn-outline-secondary {
      color: rgba(249,250,251,.92) !important;
      border-color: rgba(148,163,184,.45) !important;
      background: rgba(2, 6, 23, 0.35) !important;
    }
    .btn-outline-secondary:hover {
      border-color: rgba(56,189,248,.55) !important;
      box-shadow: 0 0 0 1px rgba(56,189,248,.25);
      transform: translateY(-1px);
    }

    .form-control, .form-select {
      border-radius: 1rem;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background: rgba(2, 6, 23, 0.45);
      color: rgba(249, 250, 251, .92);
      box-shadow: none !important;
    }
    .form-control::placeholder { color: rgba(203, 213, 245, .55); }
    .form-control:focus, .form-select:focus {
      border-color: rgba(56, 189, 248, 0.65);
      box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.12) !important;
      background: rgba(2, 6, 23, 0.55);
      color: rgba(249, 250, 251, .95);
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

    /* KPI small cards */
    .kpi {
      border-radius: 1.35rem;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background:
        radial-gradient(circle at 20% 0, rgba(56, 189, 248, 0.16), transparent 60%),
        rgba(15, 23, 42, 0.90);
      box-shadow: 0 16px 36px rgba(15, 23, 42, 0.85);
      padding: 1.1rem 1.15rem;
      height: 100%;
    }

    .kpi .k-label {
      font-size: .82rem;
      color: var(--text-muted);
      font-weight: 900;
      letter-spacing: .04em;
      text-transform: uppercase;
      margin-bottom: .25rem;
    }

    .kpi .k-value {
      font-size: 1.55rem;
      font-weight: 900;
      letter-spacing: .02em;
      color: rgba(249,250,251,.95);
      line-height: 1.1;
    }

    footer {
      text-align: center;
      padding-top: 1.6rem;
      font-size: .78rem;
      color: var(--text-muted);
    }

    @media (max-width: 992px) {
      .navbar-top { margin-left: 0; padding: 0 1.2rem; }
      .main-content { margin-left: 0; padding: 1.2rem 1.2rem 2rem; }
      .sidebar { position: static; width: 100%; height: auto; }
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
    <a href="Reportes-Filtros.php" class="active">🧪 Filtros y exportación</a>
    <a href="Reportes-Historico.php">🗂️ Histórico</a>
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
      <h5>Reportes · Filtros avanzados y exportación</h5>
    </div>

    <div class="user-info">
      <div class="user-pill">
        <?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8"); ?>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="main-content">

    <!-- Presets -->
    <div class="card section-card mb-4">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <div class="pill" style="border-color: rgba(148,163,184,.55); color: rgba(203,213,245,.9);">● Presets</div>
            <div class="text-muted small">Guarda combinaciones de filtros para reutilizarlas.</div>
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" type="button">Guardar como preset</button>
            <button class="btn btn-outline-secondary btn-sm" type="button">Eliminar preset</button>
          </div>
        </div>

        <div class="row g-3 mt-2">
          <div class="col-md-6 col-lg-4">
            <label class="form-label text-muted">Seleccionar preset</label>
            <select class="form-select">
              <?php foreach ($presets as $p): ?>
                <option value="<?= (int)$p["id"]; ?>" <?= (int)$p["id"] === (int)$filtro["preset_id"] ? "selected" : ""; ?>>
                  <?= htmlspecialchars($p["nombre"], ENT_QUOTES, "UTF-8"); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtros avanzados -->
    <div class="card section-card mb-4">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
          <div>
            <div class="pill">● Filtros</div>
            <div class="text-muted small">Aplica segmentación por canal, nicho, intención y resultado.</div>
          </div>
        </div>

        <form class="row g-3">
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
            <label class="form-label text-muted">Sector/Nicho</label>
            <select class="form-select">
              <?php foreach ($sectores as $s): ?>
                <option value="<?= htmlspecialchars($s["codigo"], ENT_QUOTES, "UTF-8"); ?>" <?= $s["codigo"] === $filtro["sector"] ? "selected" : ""; ?>>
                  <?= htmlspecialchars($s["nombre"], ENT_QUOTES, "UTF-8"); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label text-muted">Intención</label>
            <select class="form-select">
              <?php foreach ($intenciones as $i): ?>
                <option value="<?= htmlspecialchars($i["codigo"], ENT_QUOTES, "UTF-8"); ?>" <?= $i["codigo"] === $filtro["intencion"] ? "selected" : ""; ?>>
                  <?= htmlspecialchars($i["nombre"], ENT_QUOTES, "UTF-8"); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label text-muted">Resultado</label>
            <select class="form-select">
              <?php foreach ($resultados as $r): ?>
                <option value="<?= htmlspecialchars($r["codigo"], ENT_QUOTES, "UTF-8"); ?>" <?= $r["codigo"] === $filtro["resultado"] ? "selected" : ""; ?>>
                  <?= htmlspecialchars($r["nombre"], ENT_QUOTES, "UTF-8"); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label text-muted">Satisfacción mínima</label>
            <select class="form-select">
              <?php foreach ($csatMinOpciones as $o): ?>
                <option value="<?= htmlspecialchars($o["codigo"], ENT_QUOTES, "UTF-8"); ?>" <?= $o["codigo"] === $filtro["csat_min"] ? "selected" : ""; ?>>
                  <?= htmlspecialchars($o["nombre"], ENT_QUOTES, "UTF-8"); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label text-muted">Tiempo resp. (máx. seg)</label>
            <input type="number" class="form-control" placeholder="ej. 10" value="<?= htmlspecialchars($filtro["t_resp_max"], ENT_QUOTES, "UTF-8"); ?>">
          </div>

          <div class="col-12 d-flex justify-content-end gap-2 mt-2">
            <button type="button" class="btn btn-outline-secondary">Limpiar</button>
            <button type="button" class="btn btn-primary">Aplicar filtros</button>
          </div>
        </form>
      </div>
    </div>

    <!-- KPIs resumidos según filtros -->
    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <div class="kpi">
          <div class="k-label">Interacciones</div>
          <div class="k-value"><?= number_format((int)$kpiResumen["interacciones"]); ?></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="kpi">
          <div class="k-label">CSAT medio</div>
          <div class="k-value"><?= number_format((float)$kpiResumen["csat_medio"], 1); ?></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="kpi">
          <div class="k-label">% Resuelto por bot</div>
          <div class="k-value"><?= (int)$kpiResumen["resuelto_bot_pct"]; ?>%</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="kpi">
          <div class="k-label">Tiempo medio</div>
          <div class="k-value"><?= number_format((float)$kpiResumen["t_resp_medio_s"], 1); ?> s</div>
        </div>
      </div>
    </div>

    <!-- Acciones de exportación -->
    <div class="card section-card">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <div class="pill" style="border-color: rgba(148,163,184,.55); color: rgba(203,213,245,.9);">● Exportación</div>
            <h5 class="m-0" style="font-weight: 900; letter-spacing:.02em;">Exportar resultados</h5>
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" type="button">Exportar CSV</button>
            <button class="btn btn-outline-secondary" type="button">Exportar PDF</button>
          </div>
        </div>
        <p class="text-muted mt-2 mb-0">La exportación respeta los filtros activos y el orden de columnas por defecto.</p>
      </div>
    </div>

    <footer>© 2026 AutomAI Solutions · Filtros avanzados</footer>
  </main>

</body>
</html>
