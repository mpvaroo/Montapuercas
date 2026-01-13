<?php

declare(strict_types=1);

/**
 * Integraciones-CRMSheets.php (SOLO VISUAL)
 * - NO usa sesiones
 * - NO consulta BD
 * - Datos MOCK para luego conectar con integracion_crm + integracion_sheets + mapeos
 */

$usuario = [
  "email"   => "admin@empresa.com",
  "rol"     => "admin",
  "empresa" => "AutomAI Demo",
  "plan"    => "Premium"
];

$crmMock = [
  "estado" => "NO_CONECTADO", // CONECTADO | WARNING | NO_CONECTADO
  "proveedor" => "HubSpot",
  "region" => "US",
  "token" => "hs_api_key_************************",
  "webhook" => "https://api.automai.solutions/webhooks/crm"
];

$sheetsMock = [
  "estado" => "WARNING", // CONECTADO | WARNING | NO_CONECTADO
  "spreadsheet_id" => "1AbCDefGhIJKlmNOPqRstuVWXYZ1234567890",
  "rango" => "Leads!A1:D5000",
  "service_account" => "automai-service@project.iam.gserviceaccount.com"
];

$mapeosMock = [
  ["origen" => "Nombre",  "crm" => "contacts.firstname",   "sheet_col" => "A", "transform" => "Ninguna"],
  ["origen" => "Email",   "crm" => "contacts.email",       "sheet_col" => "B", "transform" => "Validar email"],
  ["origen" => "Mensaje", "crm" => "tickets.description",  "sheet_col" => "C", "transform" => "Trim espacios"],
];

$syncMock = [
  "modo" => "Manual (a demanda)",
  "frecuencia" => "Cada hora",
  "ultima" => "Hace 2 h · 12:14",
  "procesados" => "148 (145 ok · 3 con aviso)",
  "estado" => "Correcto"
];

function badgeEstado(string $estado): array
{
  return match (strtoupper($estado)) {
    "CONECTADO"     => ["CONECTADO", "pill-pill pill-green"],
    "WARNING"       => ["WARNING", "pill-pill pill-amber"],
    "NO_CONECTADO"  => ["NO CONECTADO", "pill-pill pill-gray"],
    default         => [strtoupper($estado), "pill-pill pill-gray"],
  };
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Integraciones · CRM & Sheets | AutomAI Solutions</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg-main: #020617;
      --bg-elevated: rgba(15, 23, 42, .96);
      --bg-soft: rgba(15, 23, 42, .88);
      --accent: #38bdf8;
      --accent-strong: #0ea5e9;
      --text-main: #f9fafb;
      --text-muted: #cbd5f5;
      --border-subtle: rgba(148, 163, 184, .40);
      --shadow-strong: 0 18px 45px rgba(15, 23, 42, 0.9), 0 0 60px rgba(15, 23, 42, 0.95);
      --radius-lg: 1.5rem;
      --sidebar-w: 240px;
    }

    * {
      box-sizing: border-box
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
       SIDEBAR (IGUAL QUE TUS PÁGINAS OSCURAS) - 240px
       ========================================================= */
    .sidebar {
      width: var(--sidebar-w);
      height: 100vh;
      position: fixed;
      inset-block: 0;
      left: 0;
      padding: 1.75rem 1.25rem;
      background: linear-gradient(145deg, rgba(15, 23, 42, 0.98), rgba(15, 23, 42, 0.9));
      border-right: 1px solid var(--border-subtle);
      box-shadow:
        0 0 0 1px rgba(15, 23, 42, 1),
        0 18px 45px rgba(15, 23, 42, 0.95);
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
    }

    .sidebar a {
      display: block;
      color: var(--text-muted);
      text-decoration: none;
      padding: 10px 16px;
      border-radius: .75rem;
      margin: 4px 6px;
      font-size: .9rem;
      transition: .18s;
      position: relative;
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
       NAVBAR TOP (alineada al sidebar 240px)
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
      color: rgba(203, 213, 245, .75);
    }

    .navbar-title h5 {
      margin: 0;
      font-size: 1.15rem;
      font-weight: 800;
      letter-spacing: .04em;
      text-transform: uppercase;
      color: var(--text-main);
    }

    .top-actions {
      display: flex;
      align-items: center;
      gap: .9rem;
    }

    .user-pill {
      display: flex;
      align-items: center;
      gap: .55rem;
      padding: .3rem .85rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .65);
      background: rgba(15, 23, 42, .8);
      color: rgba(203, 213, 245, .9);
      font-size: .82rem;
    }

    .user-avatar {
      width: 34px;
      height: 34px;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .6);
      background: radial-gradient(circle at 30% 0, var(--accent), #0f172a);
      padding: 2px;
      box-shadow: 0 0 12px rgba(56, 189, 248, .6);
    }

    /* botón principal */
    .btn-primary-glow {
      border-radius: 999px;
      border: none;
      padding: .55rem 1.05rem;
      font-size: .88rem;
      font-weight: 800;
      color: #f9fafb;
      background: radial-gradient(circle at 10% 0, var(--accent), #2563eb);
      box-shadow: 0 10px 25px rgba(59, 130, 246, .65), 0 0 24px rgba(56, 189, 248, .85);
      transition: transform .18s ease, box-shadow .22s ease, background .18s ease;
      white-space: nowrap;
    }

    .btn-primary-glow:hover {
      transform: translateY(-1px);
      background: radial-gradient(circle at 10% 0, var(--accent-strong), #1d4ed8);
      box-shadow: 0 16px 35px rgba(37, 99, 235, .9), 0 0 30px rgba(56, 189, 248, 1);
    }

    .btn-ghost {
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .65);
      background: rgba(15, 23, 42, .65);
      color: rgba(203, 213, 245, .9);
      padding: .55rem 1rem;
      font-size: .88rem;
      font-weight: 800;
      transition: all .18s ease;
    }

    .btn-ghost:hover {
      border-color: rgba(56, 189, 248, .85);
      color: #e0f2fe;
      background: rgba(15, 23, 42, .85);
      box-shadow: 0 12px 28px rgba(15, 23, 42, .85), 0 0 22px rgba(56, 189, 248, .35);
    }

    /* botones compactos */
    .btn-sm-soft {
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .55);
      background: rgba(15, 23, 42, .70);
      color: rgba(249, 250, 251, .9);
      padding: .38rem .75rem;
      font-size: .78rem;
      font-weight: 800;
      transition: all .18s ease;
      white-space: nowrap;
    }

    .btn-sm-soft:hover {
      border-color: rgba(56, 189, 248, .75);
      box-shadow: 0 10px 25px rgba(15, 23, 42, .85), 0 0 18px rgba(56, 189, 248, .35);
    }

    .btn-sm-danger {
      border-radius: 999px;
      border: 1px solid rgba(248, 113, 113, .65);
      background: rgba(127, 29, 29, .25);
      color: #fecaca;
      padding: .38rem .75rem;
      font-size: .78rem;
      font-weight: 800;
      transition: all .18s ease;
      white-space: nowrap;
    }

    .btn-sm-danger:hover {
      background: rgba(190, 24, 24, .75);
      box-shadow: 0 12px 30px rgba(127, 29, 29, .9), 0 0 18px rgba(248, 113, 113, .55);
    }

    /* =========================================================
       MAIN
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

    .card-pad {
      padding: 1.25rem 1.4rem;
    }

    .card-header-row {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: .85rem;
    }

    .card-header-row h6 {
      margin: 0;
      font-size: .9rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: rgba(249, 250, 251, .95);
    }

    .card-sub {
      margin: .2rem 0 0;
      font-size: .82rem;
      color: rgba(203, 213, 245, .75);
      max-width: 80ch;
    }

    .badge-soft {
      padding: .18rem .6rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .6);
      font-size: .7rem;
      color: rgba(203, 213, 245, .85);
      background: rgba(15, 23, 42, .65);
      text-transform: uppercase;
      letter-spacing: .10em;
      white-space: nowrap;
    }

    /* Inputs dark */
    .form-label {
      font-size: .72rem;
      color: rgba(203, 213, 245, .75);
      text-transform: uppercase;
      letter-spacing: .14em;
      margin-bottom: .35rem;
    }

    .form-control,
    .form-select {
      background: rgba(15, 23, 42, .75);
      border: 1px solid rgba(148, 163, 184, .45);
      color: var(--text-main);
      border-radius: 1rem;
      padding: .6rem .85rem;
    }

    .form-control::placeholder {
      color: rgba(148, 163, 184, .55)
    }

    .form-control:focus,
    .form-select:focus {
      border-color: rgba(56, 189, 248, .85);
      box-shadow: 0 0 0 2px rgba(56, 189, 248, .18);
      background: rgba(15, 23, 42, .85);
      color: var(--text-main);
    }

    /* Pills (estado) */
    .pill-pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .35rem;
      padding: .26rem .75rem;
      border-radius: 999px;
      font-size: .72rem;
      letter-spacing: .10em;
      text-transform: uppercase;
      border: 1px solid rgba(148, 163, 184, .6);
      background: rgba(15, 23, 42, .65);
      white-space: nowrap;
      font-weight: 900;
    }

    .pill-green {
      border-color: rgba(34, 197, 94, .55);
      color: #bbf7d0;
      background: rgba(34, 197, 94, .10);
    }

    .pill-amber {
      border-color: rgba(245, 158, 11, .55);
      color: #fed7aa;
      background: rgba(245, 158, 11, .10);
    }

    .pill-gray {
      border-color: rgba(148, 163, 184, .55);
      color: #cbd5e1;
      background: rgba(148, 163, 184, .08);
    }

    /* Table dark */
    .table-wrap {
      border-radius: 1.25rem;
      overflow: hidden;
      border: 1px solid rgba(148, 163, 184, .35);
      background: rgba(10, 14, 30, .28);
    }

    table.map-table {
      margin: 0;
      width: 100%;
      color: var(--text-main);
      border-collapse: separate;
      border-spacing: 0;
    }

    .map-table thead th {
      background: rgba(15, 23, 42, .85) !important;
      color: rgba(249, 250, 251, .92) !important;
      border-bottom: 1px solid rgba(255, 255, 255, .10) !important;
      font-size: .78rem;
      letter-spacing: .10em;
      text-transform: uppercase;
      padding: .95rem 1rem;
      white-space: nowrap;
    }

    .map-table tbody td {
      background: rgba(10, 14, 30, .18);
      border-bottom: 1px solid rgba(255, 255, 255, .08);
      padding: .95rem 1rem;
      vertical-align: middle;
      font-size: .9rem;
      color: rgba(249, 250, 251, .88) !important;
    }

    .map-table tbody tr:hover td {
      background: rgba(56, 189, 248, .06);
    }

    /* KPI boxes */
    .kpi {
      border-radius: 1.25rem;
      border: 1px solid rgba(148, 163, 184, .35);
      background: rgba(10, 14, 30, .22);
      padding: 1rem 1rem;
      height: 100%;
    }

    .kpi .kpi-label {
      font-size: .72rem;
      color: rgba(203, 213, 245, .75);
      letter-spacing: .14em;
      text-transform: uppercase;
      margin-bottom: .35rem;
    }

    .kpi .kpi-value {
      font-weight: 900;
      letter-spacing: .02em;
      color: rgba(249, 250, 251, .95);
    }

    .kpi .kpi-sub {
      margin-top: .25rem;
      font-size: .82rem;
      color: rgba(203, 213, 245, .75);
    }

    footer {
      text-align: center;
      padding-top: 1.6rem;
      font-size: .78rem;
      color: rgba(203, 213, 245, .75);
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
        border-right: none;
      }
    }

    /* Modal theme (dark) */
    .modal-content {
      background: rgba(15, 23, 42, .96);
      border: 1px solid rgba(148, 163, 184, .35);
      border-radius: 1.25rem;
      color: var(--text-main);
      box-shadow: 0 18px 45px rgba(15, 23, 42, .9);
    }

    .modal-header,
    .modal-footer {
      border-color: rgba(148, 163, 184, .18);
    }

    .btn-close {
      filter: invert(1);
      opacity: .7;
    }

    .modal-title {
      font-size: .9rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: rgba(249, 250, 251, .95);
      font-weight: 900;
    }

    .form-text {
      color: rgba(203, 213, 245, .75);
    }

    code {
      color: #f472b6;
    }

    /* small helper text */
    .hint {
      font-size: .82rem;
      color: rgba(203, 213, 245, .75);
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
    <a href="Integraciones-CRMSheets.php" class="active">🧩 CRM & Sheets</a>
    <a href="Integraciones-Historial.php">🗂️ Historial</a>
    <a href="Reportes-Dashboard.php">📊 Reportes</a>
    <a href="Usuarios-Gestion.php">👥 Usuarios</a>
    <a href="Configuracion-General.php">⚙️ Configuración</a>
    <a href="Soporte-Ayuda.php">💬 Soporte</a>

    <hr>
    <a href="Logout.php" class="text-danger">🚪 Cerrar sesión</a>
  </aside>

  <!-- Navbar -->
  <header class="navbar-top">
    <div class="navbar-title">
      <span><?= htmlspecialchars($usuario["empresa"], ENT_QUOTES, "UTF-8"); ?></span>
      <h5>Integraciones · CRM & Sheets</h5>
    </div>

    <div class="top-actions">
      <button class="btn-ghost" data-bs-toggle="modal" data-bs-target="#modalSync">⏱ Programar sync</button>

      <div class="user-pill">
        <span><?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8"); ?></span>
        <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Avatar usuario" class="user-avatar">
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="main-content">

    <div class="row g-4">
      <!-- CRM -->
      <div class="col-12 col-xl-6">
        <section class="section-card card-pad">
          <div class="card-header-row">
            <div>
              <h6>Conexión CRM</h6>
              <p class="card-sub">Conecta tu CRM para guardar leads, tickets o contactos creados por el chatbot.</p>
            </div>

            <?php [$txtCRM, $clsCRM] = badgeEstado($crmMock["estado"]); ?>
            <span class="<?= $clsCRM; ?>"><?= $txtCRM; ?></span>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Proveedor</label>
              <select class="form-select">
                <option <?= $crmMock["proveedor"] === "HubSpot" ? "selected" : "" ?>>HubSpot</option>
                <option <?= $crmMock["proveedor"] === "Zoho CRM" ? "selected" : "" ?>>Zoho CRM</option>
                <option <?= $crmMock["proveedor"] === "Pipedrive" ? "selected" : "" ?>>Pipedrive</option>
                <option <?= $crmMock["proveedor"] === "Otro (API)" ? "selected" : "" ?>>Otro (API)</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Región / Data center</label>
              <select class="form-select">
                <option <?= $crmMock["region"] === "EU" ? "selected" : "" ?>>EU</option>
                <option <?= $crmMock["region"] === "US" ? "selected" : "" ?>>US</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">API Key / OAuth Token</label>
              <div class="input-group">
                <input id="crm_token" type="password" class="form-control" value="<?= htmlspecialchars($crmMock["token"], ENT_QUOTES, "UTF-8"); ?>">
                <button class="btn-sm-soft" type="button" id="btnToggleCrm">👁 Mostrar</button>
              </div>
              <div class="hint mt-2">Para OAuth, usa el flujo guiado. Si es API Key, pégala aquí.</div>
            </div>

            <div class="col-12">
              <label class="form-label">Webhook URL (eventos)</label>
              <div class="input-group">
                <input id="crm_webhook" type="text" class="form-control" value="<?= htmlspecialchars($crmMock["webhook"], ENT_QUOTES, "UTF-8"); ?>" readonly>
                <button class="btn-sm-soft" type="button" onclick="copiar('crm_webhook', this)">Copiar</button>
              </div>
              <div class="hint mt-2">Registra esta URL en tu CRM para recibir eventos (webhooks).</div>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-3">
            <button class="btn-primary-glow" type="button">Conectar</button>
            <button class="btn-sm-soft" type="button" data-bs-toggle="modal" data-bs-target="#modalTestCrm">Probar</button>
            <button class="btn-sm-danger" type="button">Desconectar</button>
          </div>
        </section>
      </div>

      <!-- Sheets -->
      <div class="col-12 col-xl-6">
        <section class="section-card card-pad">
          <div class="card-header-row">
            <div>
              <h6>Google Sheets</h6>
              <p class="card-sub">Sincroniza registros del chatbot con una hoja de cálculo para reportes y backups.</p>
            </div>

            <?php [$txtS, $clsS] = badgeEstado($sheetsMock["estado"]); ?>
            <span class="<?= $clsS; ?>"><?= $txtS; ?></span>
          </div>

          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Spreadsheet ID</label>
              <input class="form-control" type="text" value="<?= htmlspecialchars($sheetsMock["spreadsheet_id"], ENT_QUOTES, "UTF-8"); ?>">
            </div>

            <div class="col-md-4">
              <label class="form-label">Rango</label>
              <input class="form-control" type="text" value="<?= htmlspecialchars($sheetsMock["rango"], ENT_QUOTES, "UTF-8"); ?>">
            </div>

            <div class="col-12">
              <label class="form-label">Service Account (email)</label>
              <input class="form-control" type="email" value="<?= htmlspecialchars($sheetsMock["service_account"], ENT_QUOTES, "UTF-8"); ?>">
              <div class="hint mt-2">Comparte la hoja con este email para dar permisos de escritura.</div>
            </div>
          </div>

          <div class="d-flex justify-content-end gap-2 mt-3">
            <button class="btn-sm-soft" type="button" data-bs-toggle="modal" data-bs-target="#modalValidateSheets">Validar acceso</button>
            <button class="btn-primary-glow" type="button">Probar escritura</button>
            <button class="btn-sm-danger" type="button">Desconectar</button>
          </div>
        </section>
      </div>

      <!-- Mapeo -->
      <div class="col-12">
        <section class="section-card card-pad">
          <div class="card-header-row">
            <div>
              <h6>Mapeo de campos</h6>
              <p class="card-sub">Define cómo se guardan los datos capturados por el bot en tu CRM o en Google Sheets.</p>
            </div>
            <div class="d-flex gap-2">
              <span class="badge-soft"><?= count($mapeosMock) ?> mapeos</span>
              <button class="btn-primary-glow" type="button" data-bs-toggle="modal" data-bs-target="#modalMapeo">➕ Añadir mapeo</button>
            </div>
          </div>

          <div class="table-wrap">
            <div class="table-responsive">
              <table class="map-table align-middle">
                <thead>
                  <tr>
                    <th>Origen (Chatbot)</th>
                    <th>Destino (CRM)</th>
                    <th>Destino (Sheets)</th>
                    <th>Transformación</th>
                    <th class="text-end">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($mapeosMock as $m): ?>
                    <tr>
                      <td style="font-weight:900;color:rgba(249,250,251,.95)"><?= htmlspecialchars($m["origen"], ENT_QUOTES, "UTF-8"); ?></td>
                      <td>
                        <select class="form-select form-select-sm">
                          <option <?= $m["crm"] === "contacts.firstname" ? "selected" : "" ?>>contacts.firstname</option>
                          <option <?= $m["crm"] === "contacts.lastname" ? "selected" : "" ?>>contacts.lastname</option>
                          <option <?= $m["crm"] === "contacts.email" ? "selected" : "" ?>>contacts.email</option>
                          <option <?= $m["crm"] === "tickets.requester_email" ? "selected" : "" ?>>tickets.requester_email</option>
                          <option <?= $m["crm"] === "tickets.description" ? "selected" : "" ?>>tickets.description</option>
                          <option <?= $m["crm"] === "deals.notes" ? "selected" : "" ?>>deals.notes</option>
                        </select>
                      </td>
                      <td style="max-width:140px;">
                        <input class="form-control form-control-sm" type="text" value="<?= htmlspecialchars($m["sheet_col"], ENT_QUOTES, "UTF-8"); ?>">
                      </td>
                      <td>
                        <select class="form-select form-select-sm">
                          <option <?= $m["transform"] === "Ninguna" ? "selected" : "" ?>>Ninguna</option>
                          <option <?= $m["transform"] === "Mayúsculas" ? "selected" : "" ?>>Mayúsculas</option>
                          <option <?= $m["transform"] === "Trim espacios" ? "selected" : "" ?>>Trim espacios</option>
                          <option <?= $m["transform"] === "Validar email" ? "selected" : "" ?>>Validar email</option>
                        </select>
                      </td>
                      <td class="text-end">
                        <div class="d-inline-flex flex-wrap gap-2 justify-content-end">
                          <button class="btn-sm-soft" type="button" data-bs-toggle="modal" data-bs-target="#modalMapeo">Editar</button>
                          <button class="btn-sm-danger" type="button">Eliminar</button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>

      <!-- Sync -->
      <div class="col-12">
        <section class="section-card card-pad">
          <div class="card-header-row">
            <div>
              <h6>Sincronización</h6>
              <p class="card-sub">Configura el modo y frecuencia. Puedes lanzar una prueba cuando quieras.</p>
            </div>
            <span class="badge-soft">Demo visual</span>
          </div>

          <form class="row g-3 align-items-end">
            <div class="col-md-4">
              <label class="form-label">Modo</label>
              <select class="form-select">
                <option selected><?= htmlspecialchars($syncMock["modo"], ENT_QUOTES, "UTF-8"); ?></option>
                <option>Automática programada</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Frecuencia</label>
              <select class="form-select">
                <option>Cada 15 minutos</option>
                <option selected><?= htmlspecialchars($syncMock["frecuencia"], ENT_QUOTES, "UTF-8"); ?></option>
                <option>Diaria (00:00)</option>
              </select>
            </div>

            <div class="col-md-4 d-flex justify-content-end gap-2">
              <button type="button" class="btn-sm-soft" data-bs-toggle="modal" data-bs-target="#modalTestSync">Probar sincronización</button>
              <button type="button" class="btn-primary-glow">Guardar programación</button>
            </div>
          </form>

          <div class="row g-3 mt-2">
            <div class="col-md-4">
              <div class="kpi">
                <div class="kpi-label">Última sincronización</div>
                <div class="kpi-value"><?= htmlspecialchars($syncMock["ultima"], ENT_QUOTES, "UTF-8"); ?></div>
                <div class="kpi-sub">Estado del último job</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="kpi">
                <div class="kpi-label">Registros procesados</div>
                <div class="kpi-value"><?= htmlspecialchars($syncMock["procesados"], ENT_QUOTES, "UTF-8"); ?></div>
                <div class="kpi-sub">Ok vs avisos</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="kpi">
                <div class="kpi-label">Estado</div>
                <div class="kpi-value" style="color:#bbf7d0;"><?= htmlspecialchars($syncMock["estado"], ENT_QUOTES, "UTF-8"); ?></div>
                <div class="kpi-sub">Sin errores críticos</div>
              </div>
            </div>
          </div>

          <div class="text-end mt-3">
            <a href="Integraciones-Historial.php" class="btn-ghost" style="text-decoration:none;">Ver historial de sincronización</a>
          </div>
        </section>
      </div>

    </div>

    <footer>
      © 2026 AutomAI Solutions · Integraciones CRM & Sheets · Plan: <?= htmlspecialchars($usuario["plan"], ENT_QUOTES, "UTF-8"); ?>
    </footer>

  </main>

  <!-- ========================= MODALES (DARK) ========================= -->

  <!-- Modal: Programar sync -->
  <div class="modal fade" id="modalSync" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Programar sincronización</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3">
            <div class="col-12">
              <label class="form-label">Destino</label>
              <select class="form-select">
                <option selected>Google Sheets</option>
                <option>CRM</option>
                <option>Ambos</option>
              </select>
              <div class="form-text">Define dónde se guardarán los registros del chatbot.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Frecuencia</label>
              <select class="form-select">
                <option>Cada 15 minutos</option>
                <option selected>Cada hora</option>
                <option>Diaria (00:00)</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Modo</label>
              <select class="form-select">
                <option selected>Manual (a demanda)</option>
                <option>Automática programada</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Notas</label>
              <textarea class="form-control" rows="3" placeholder="Observaciones (demo)..."></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn-ghost" data-bs-dismiss="modal" type="button">Cancelar</button>
          <button class="btn-primary-glow" type="button">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Probar CRM -->
  <div class="modal fade" id="modalTestCrm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Probar conexión CRM</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p class="hint mb-2">Demo visual. Aquí luego verás latencia, resultado HTTP y mensaje del test.</p>
          <div class="table-wrap" style="border-radius:1rem;">
            <div style="padding:1rem;">
              <div class="d-flex justify-content-between">
                <span class="badge-soft">Resultado</span>
                <span class="pill-pill pill-amber">WARNING</span>
              </div>
              <div class="mt-3">
                <div class="hint">HTTP</div>
                <div style="font-weight:900;">403 · Permisos insuficientes</div>
              </div>
              <div class="mt-3">
                <div class="hint">Detalle</div>
                <div style="color:rgba(249,250,251,.85)">OAuth scope limitado o token caducado (demo).</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn-ghost" data-bs-dismiss="modal" type="button">Cerrar</button>
          <button class="btn-primary-glow" type="button">Reintentar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Validar Sheets -->
  <div class="modal fade" id="modalValidateSheets" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Validar acceso a Sheets</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p class="hint mb-2">Demo visual. Luego se validará si el Service Account tiene permisos en la hoja.</p>
          <div class="d-flex justify-content-between align-items-center">
            <span class="badge-soft">Estado</span>
            <span class="pill-pill pill-amber">WARNING</span>
          </div>
          <div class="mt-3 hint">Sugerencia: comparte la hoja con el email del Service Account y concede edición.</div>
        </div>
        <div class="modal-footer">
          <button class="btn-ghost" data-bs-dismiss="modal" type="button">Cerrar</button>
          <button class="btn-primary-glow" type="button">Validar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Test Sync -->
  <div class="modal fade" id="modalTestSync" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Probar sincronización</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p class="hint mb-2">Demo visual. Luego aquí verás el log del job (ok/issue/fail).</p>
          <div class="table-wrap" style="border-radius:1rem;">
            <div style="padding:1rem;">
              <div class="d-flex justify-content-between">
                <span class="badge-soft">Procesados</span>
                <span class="pill-pill pill-green">OK</span>
              </div>
              <div class="mt-3" style="font-weight:900;">148 registros · 145 OK · 3 aviso</div>
              <div class="mt-2 hint">Tiempo: 920ms · HTTP: 200 · Sin errores críticos (demo).</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn-ghost" data-bs-dismiss="modal" type="button">Cerrar</button>
          <button class="btn-primary-glow" type="button">Ejecutar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Añadir/Editar mapeo -->
  <div class="modal fade" id="modalMapeo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Añadir / Editar mapeo</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Origen (Chatbot)</label>
              <select class="form-select">
                <option selected>Nombre</option>
                <option>Email</option>
                <option>Mensaje</option>
                <option>Teléfono</option>
                <option>Empresa</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Destino (CRM)</label>
              <select class="form-select">
                <option selected>contacts.firstname</option>
                <option>contacts.lastname</option>
                <option>contacts.email</option>
                <option>tickets.description</option>
                <option>deals.notes</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Columna (Sheets)</label>
              <input class="form-control" type="text" placeholder="A / B / C ...">
            </div>
            <div class="col-12">
              <label class="form-label">Transformación</label>
              <select class="form-select">
                <option selected>Ninguna</option>
                <option>Mayúsculas</option>
                <option>Trim espacios</option>
                <option>Validar email</option>
              </select>
              <div class="form-text">Luego puedes añadir reglas más avanzadas (JSON, regex, etc.).</div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn-ghost" data-bs-dismiss="modal" type="button">Cancelar</button>
          <button class="btn-primary-glow" type="button">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function copiar(idInput, btn) {
      const el = document.getElementById(idInput);
      if (!el) return;

      const texto = el.value || el.getAttribute("value") || "";
      navigator.clipboard.writeText(texto).then(() => {
        const old = btn.textContent;
        btn.textContent = "✅ Copiado";
        btn.disabled = true;
        setTimeout(() => {
          btn.textContent = old;
          btn.disabled = false;
        }, 1100);
      }).catch(() => alert("No se pudo copiar. Copia manualmente."));
    }

    // Toggle token CRM
    const inputToken = document.getElementById("crm_token");
    const btnToggle = document.getElementById("btnToggleCrm");
    if (inputToken && btnToggle) {
      btnToggle.addEventListener("click", () => {
        const isPass = inputToken.type === "password";
        inputToken.type = isPass ? "text" : "password";
        btnToggle.textContent = isPass ? "🙈 Ocultar" : "👁 Mostrar";
      });
    }
  </script>
</body>

</html>