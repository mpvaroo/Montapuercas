<?php

declare(strict_types=1);

/**
 * Reportes-Dashboard.php (SOLO VISUAL)
 * - No sesiones
 * - No BD
 * - Datos MOCK (arrays) equivalentes a tablas futuras
 * - UI premium dark neon glass: sidebar 240 + topbar alineada + cards + tablas/pills/botones
 */

$usuario = [
  "email" => "admin@empresa.com",
  "empresa" => "AutomAI Demo",
  "plan" => "Premium"
];

/* =========================================================
   B) MOCK DATA (equivalente a BD futura)
   ========================================================= */

/** Canales (tabla futura: integracion_canal) */
$canales = [
  ["codigo" => "ALL", "nombre" => "Todos"],
  ["codigo" => "WHATSAPP", "nombre" => "WhatsApp"],
  ["codigo" => "TELEGRAM", "nombre" => "Telegram"],
  ["codigo" => "WEB_CHAT", "nombre" => "Web Chat"],
  ["codigo" => "GMAIL", "nombre" => "Gmail"],
];

/** Filtros rápidos (tabla futura: reporte_filtro_guardado opcional) */
$filtro = [
  "desde" => "2025-10-01",
  "hasta" => "2025-10-31",
  "canal" => "ALL"
];

/** KPIs (tabla futura: reporte_kpi_serie / reporte_resumen) */
$kpis = [
  [
    "clave" => "interacciones",
    "label" => "Interacciones totales",
    "value" => "12,845",
    "delta" => "▲ +8.4% vs. periodo anterior",
    "trend" => "up"
  ],
  [
    "clave" => "t_respuesta",
    "label" => "Tiempo medio de respuesta",
    "value" => "6.2 s",
    "delta" => "▲ -12% (más rápido)",
    "trend" => "up"
  ],
  [
    "clave" => "csat",
    "label" => "Satisfacción (CSAT)",
    "value" => "4.6 / 5",
    "delta" => "▲ +0.3 pts",
    "trend" => "up"
  ],
  [
    "clave" => "horas",
    "label" => "Horas ahorradas",
    "value" => "182 h",
    "delta" => "▲ +15 h",
    "trend" => "up"
  ],
];

/** Top intenciones (tabla futura: reporte_intencion_agg) */
$topIntenciones = [
  ["nombre" => "Reservas / Citas", "conteo" => 3210],
  ["nombre" => "Precios / Tarifas", "conteo" => 2740],
  ["nombre" => "Horarios", "conteo" => 1980],
  ["nombre" => "Soporte / Incidencias", "conteo" => 1604],
];

/** Modo gráfico (tabla futura: reporte_preferencia_usuario opcional) */
$modoGrafico = "DIA"; // DIA | SEMANA | MES

function badgeTrendClass(string $trend): string
{
  return $trend === "up" ? "delta up" : "delta down";
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reportes · Dashboard | AutomAI Solutions</title>
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

    /* =========================================================
       Bootstrap overrides (evitar blanco/gris)
       ========================================================= */
    .text-muted {
      color: var(--text-muted) !important;
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

    /* =========================================================
       SIDEBAR (240px) — EXACTO a tu estándar
       ========================================================= */
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

    /* =========================================================
       TOPBAR alineada al sidebar (margen correcto)
       ========================================================= */
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

    .user-avatar {
      width: 34px;
      height: 34px;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.6);
      background: radial-gradient(circle at 30% 0, var(--accent), #0f172a);
      padding: 2px;
      box-shadow: 0 0 12px rgba(56, 189, 248, 0.6);
      object-fit: cover;
    }

    /* =========================================================
       MAIN + CARDS (glass)
       ========================================================= */
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

    .page-hero {
      padding: 1.6rem 1.7rem 1.45rem;
      background:
        radial-gradient(circle at top left, rgba(56, 189, 248, 0.22), transparent 55%),
        radial-gradient(circle at bottom, rgba(236, 72, 153, 0.14), transparent 55%),
        rgba(15, 23, 42, 0.86);
      margin-bottom: 1.25rem;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1.2rem;
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

    .page-hero h1 {
      margin: .2rem 0 .35rem;
      font-size: 1.35rem;
      font-weight: 900;
      letter-spacing: .04em;
      text-transform: uppercase;
    }

    .page-hero p {
      margin: 0;
      color: var(--text-muted);
      font-size: .92rem;
      max-width: 72ch;
      line-height: 1.45;
    }

    /* KPIs (glass) */
    .kpi {
      border-radius: 1.35rem;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background:
        radial-gradient(circle at 20% 0, rgba(56, 189, 248, 0.16), transparent 60%),
        rgba(15, 23, 42, 0.90);
      box-shadow: 0 16px 36px rgba(15, 23, 42, 0.85);
      padding: 1.25rem;
      height: 100%;
      position: relative;
      overflow: hidden;
    }

    .kpi::after {
      content: "";
      position: absolute;
      inset: -120% -60%;
      background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.10), transparent);
      transform: rotate(18deg);
      opacity: 0;
      transition: opacity .2s ease;
      pointer-events: none;
    }

    .kpi:hover::after {
      opacity: 1;
    }

    .kpi .label {
      font-size: .86rem;
      color: var(--text-muted);
      letter-spacing: .02em;
      font-weight: 800;
      text-transform: uppercase;
    }

    .kpi .value {
      font-size: 1.75rem;
      font-weight: 900;
      letter-spacing: .02em;
      margin-top: .15rem;
      color: rgba(249, 250, 251, .95);
    }

    .delta {
      font-size: .86rem;
      font-weight: 800;
      margin-top: .3rem;
      letter-spacing: .01em;
    }

    .delta.up {
      color: rgba(134, 239, 172, .95);
    }

    .delta.down {
      color: rgba(252, 165, 165, .95);
    }

    /* Chart placeholders (dark) */
    .chart-placeholder {
      background:
        repeating-linear-gradient(135deg,
          rgba(148, 163, 184, .14),
          rgba(148, 163, 184, .14) 12px,
          rgba(148, 163, 184, .08) 12px,
          rgba(148, 163, 184, .08) 24px);
      border-radius: 1.25rem;
      border: 1px dashed rgba(148, 163, 184, 0.45);
      height: 320px;
      position: relative;
      overflow: hidden;
    }

    .chart-placeholder::before {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 20% 0, rgba(56, 189, 248, .14), transparent 60%);
      pointer-events: none;
    }

    .mini-chart {
      height: 220px;
    }

    /* List group dark overrides */
    .list-group-item {
      background: rgba(2, 6, 23, 0.35) !important;
      border-color: rgba(148, 163, 184, 0.18) !important;
      color: rgba(249, 250, 251, .92) !important;
      padding: .9rem 1rem;
    }

    .badge-soft {
      border-radius: 999px;
      padding: .35rem .7rem;
      border: 1px solid rgba(148, 163, 184, .35);
      background: rgba(15, 23, 42, .75);
      color: rgba(249, 250, 251, .9);
      font-weight: 900;
      letter-spacing: .02em;
    }

    /* Segmented buttons (day/week/month) */
    .segmented {
      border: 1px solid rgba(148, 163, 184, .35);
      background: rgba(2, 6, 23, .35);
      padding: .25rem;
      border-radius: 999px;
      display: inline-flex;
      gap: .25rem;
    }

    .segmented .seg-btn {
      border: 0;
      padding: .35rem .8rem;
      border-radius: 999px;
      background: transparent;
      color: rgba(203, 213, 245, .8);
      font-weight: 900;
      letter-spacing: .04em;
      text-transform: uppercase;
      font-size: .72rem;
    }

    .segmented .seg-btn.active {
      background: linear-gradient(135deg, rgba(56, 189, 248, .25), rgba(15, 23, 42, .85));
      color: rgba(249, 250, 251, .95);
      box-shadow: 0 0 0 1px rgba(56, 189, 248, .22) inset;
    }

    footer {
      text-align: center;
      padding-top: 1.6rem;
      font-size: .78rem;
      color: var(--text-muted);
    }

    /* Responsive */
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
    <a href="Reportes-Dashboard.php" class="active">📊 Reportes</a>
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
      <h5>Reportes y Métricas · Dashboard</h5>
    </div>

    <div class="user-info">
      <div class="user-pill">
        <span><?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8"); ?></span>
        <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Usuario" class="user-avatar">
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="main-content">

    <!-- Hero -->
    <section class="section-card page-hero">
      <div>
        <div class="pill">● Reportes</div>
        <h1>Dashboard de métricas</h1>
        <p>Vista general de rendimiento, volumen de interacciones y distribución por canal. Usa filtros rápidos o ve a filtros avanzados para exportación.</p>
      </div>

      <div style="text-align:right; color: var(--text-muted); font-size:.82rem;">
        <div style="letter-spacing:.12em; text-transform:uppercase;">Plan</div>
        <div style="font-weight:900; color: var(--text-main);">
          <?= htmlspecialchars($usuario["plan"], ENT_QUOTES, "UTF-8"); ?>
        </div>
      </div>
    </section>

    <!-- Filtros rápidos (MISMO CONTENIDO, MISMA ESTRUCTURA FUNCIONAL) -->
    <div class="card section-card mb-4">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
          <div>
            <div class="pill" style="border-color: rgba(148,163,184,.55); color: rgba(203,213,245,.9);">
              ● Filtros rápidos
            </div>
            <div class="text-muted small">Ajusta rango y canal para ver el resumen del periodo.</div>
          </div>
          <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="Reportes-Filtros.php">Filtros avanzados</a>
            <button type="button" class="btn btn-primary">Aplicar</button>
          </div>
        </div>

        <form class="row g-3 align-items-end">
          <div class="col-md-3">
            <label class="form-label text-muted">Rango de fechas</label>
            <input type="date" class="form-control" value="<?= htmlspecialchars($filtro["desde"], ENT_QUOTES, "UTF-8"); ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label text-muted d-none d-md-block">&nbsp;</label>
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
          <div class="col-md-3 d-flex justify-content-end gap-2">
            <a class="btn btn-outline-secondary" href="Reportes-Historico.php">Histórico</a>
            <button type="button" class="btn btn-primary">Aplicar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- KPIs -->
    <div class="row g-3 mb-4">
      <?php foreach ($kpis as $k): ?>
        <div class="col-12 col-md-6 col-xl-3">
          <div class="kpi">
            <div class="label"><?= htmlspecialchars($k["label"], ENT_QUOTES, "UTF-8"); ?></div>
            <div class="value"><?= htmlspecialchars($k["value"], ENT_QUOTES, "UTF-8"); ?></div>
            <div class="<?= badgeTrendClass($k["trend"]); ?>">
              <?= htmlspecialchars($k["delta"], ENT_QUOTES, "UTF-8"); ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Gráficos principales (placeholders) -->
    <div class="row g-4">
      <div class="col-12 col-xl-8">
        <div class="card section-card">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
              <h5 class="m-0" style="font-weight: 900; letter-spacing:.02em;">Volumen de interacciones (diario)</h5>

              <div class="segmented" role="group" aria-label="Rango gráfico">
                <button class="seg-btn <?= $modoGrafico === "DIA" ? "active" : ""; ?>" type="button">Día</button>
                <button class="seg-btn <?= $modoGrafico === "SEMANA" ? "active" : ""; ?>" type="button">Semana</button>
                <button class="seg-btn <?= $modoGrafico === "MES" ? "active" : ""; ?>" type="button">Mes</button>
              </div>
            </div>

            <div class="chart-placeholder"></div>
            <div class="text-muted small mt-2">Placeholder de gráfico (líneas/barras). Integraremos la librería en desarrollo.</div>
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-4">
        <div class="card section-card mb-4">
          <div class="card-body p-4">
            <h5 class="mb-2" style="font-weight: 900;">Distribución por canal</h5>
            <div class="chart-placeholder mini-chart"></div>
            <div class="text-muted small mt-2">Placeholder (donut/barras apiladas).</div>
          </div>
        </div>

        <div class="card section-card">
          <div class="card-body p-4">
            <h5 class="mb-3" style="font-weight: 900;">Top intenciones</h5>
            <ul class="list-group list-group-flush">
              <?php foreach ($topIntenciones as $t): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span><?= htmlspecialchars($t["nombre"], ENT_QUOTES, "UTF-8"); ?></span>
                  <span class="badge-soft"><?= number_format((int)$t["conteo"]); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
      <div class="text-muted">¿Necesitas segmentar o exportar?</div>
      <div class="d-flex gap-2">
        <a href="Reportes-Filtros.php" class="btn btn-outline-secondary">Filtros y exportación</a>
        <a href="Reportes-Historico.php" class="btn btn-outline-secondary">Histórico</a>
      </div>
    </div>

    <footer>© 2026 AutomAI Solutions · Reportes y Métricas</footer>
  </main>
</body>

</html>