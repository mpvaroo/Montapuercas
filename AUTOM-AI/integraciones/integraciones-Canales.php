<?php

declare(strict_types=1);

/**
 * Integraciones-Canales.php (SOLO VISUAL)
 * - No sesiones
 * - No BD
 * - Datos MOCK en arrays (equivalente a tablas futuras)
 */

$usuario = [
  "email" => "admin@empresa.com",
  "empresa" => "AutomAI Demo",
  "plan" => "Premium"
];

$empresaIdMock = 1;

/* =========================================================
   B) MOCK DATA (equivalente a BD futura)
   ========================================================= */

$linksTop = [
  ["href" => "Integraciones-CRMSheets.php", "label" => "CRM & Sheets"],
  ["href" => "Integraciones-Historial.php", "label" => "Historial"],
];

$canales = [
  [
    "codigo" => "WHATSAPP",
    "nombre" => "WhatsApp Business API",
    "icon" => "https://cdn-icons-png.flaticon.com/512/733/733585.png",
    "estado" => "CONECTADO", // CONECTADO | NO_CONECTADO | WARNING
    "badge" => ["class" => "badge-ok", "text" => "Conectado", "dot" => "dot-ok"],
    "descripcion" => "Responde a tus clientes directamente en WhatsApp. Requiere número verificado y proveedor (Cloud API).",
    "campos" => [
      ["key" => "access_token", "label" => "Access Token", "type" => "password", "placeholder" => "EAAB... (oculto)", "value" => ""],
      ["key" => "phone_number_id", "label" => "Phone Number ID", "type" => "text", "placeholder" => "1234567890", "value" => "1234567890"],
      ["key" => "webhook_url", "label" => "Webhook URL", "type" => "readonly", "value" => "https://api.automai.solutions/webhooks/whatsapp"],
    ],
    "nota" => "Configura esta URL en el panel de Meta (suscripciones: messages, message_template).",
    "acciones" => [
      ["type" => "danger-outline", "label" => "Desconectar", "disabled" => false],
      ["type" => "primary", "label" => "Probar canal", "disabled" => false],
    ],
  ],
  [
    "codigo" => "TELEGRAM",
    "nombre" => "Telegram Bot",
    "icon" => "https://cdn-icons-png.flaticon.com/512/2111/2111646.png",
    "estado" => "NO_CONECTADO",
    "badge" => ["class" => "badge-off", "text" => "No conectado", "dot" => "dot-off"],
    "descripcion" => "Crea tu bot con @BotFather y pega aquí el token para activarlo.",
    "campos" => [
      ["key" => "bot_token", "label" => "Bot Token", "type" => "text", "placeholder" => "123456:ABC-DEF...", "value" => ""],
      ["key" => "username", "label" => "Username", "type" => "text", "placeholder" => "@MiBot", "value" => ""],
      ["key" => "webhook_url", "label" => "Webhook URL", "type" => "readonly", "value" => "https://api.automai.solutions/webhooks/telegram"],
    ],
    "nota" => "Activa el webhook con setWebhook o usa long polling.",
    "acciones" => [
      ["type" => "primary-outline", "label" => "Conectar", "disabled" => false],
      ["type" => "secondary-outline", "label" => "Probar canal", "disabled" => true],
    ],
  ],
  [
    "codigo" => "GMAIL",
    "nombre" => "Gmail (Atención por email)",
    "icon" => "https://cdn-icons-png.flaticon.com/512/888/888853.png",
    "estado" => "WARNING",
    "badge" => ["class" => "badge-warn", "text" => "Conectado (revisar permisos)", "dot" => "dot-warn"],
    "descripcion" => "Autoriza a AutomAI para leer/redactar correos en tu bandeja de soporte.",
    "campos" => [
      ["key" => "cuenta_vinculada", "label" => "Cuenta vinculada", "type" => "readonly", "value" => "soporte@tuempresa.com"],
      ["key" => "oauth_scope", "label" => "Ámbito OAuth", "type" => "select", "value" => "LECTURA_ENVIO", "options" => [
        ["value" => "LECTURA_ENVIO", "label" => "Lectura + Envío"],
        ["value" => "SOLO_LECTURA", "label" => "Solo lectura"],
      ]],
      ["key" => "agrupar_hilos", "label" => "Agrupar hilos por ticket", "type" => "checkbox", "value" => true],
    ],
    "nota" => "Si el scope está limitado, algunas acciones (responder) pueden fallar.",
    "acciones" => [
      ["type" => "danger-outline", "label" => "Desconectar", "disabled" => false],
      ["type" => "primary", "label" => "Probar canal", "disabled" => false],
    ],
  ],
  [
    "codigo" => "WEB_CHAT",
    "nombre" => "Web Chat (embebible)",
    "icon" => "https://cdn-icons-png.flaticon.com/512/5977/5977590.png",
    "estado" => "CONECTADO",
    "badge" => ["class" => "badge-ok", "text" => "Conectado", "dot" => "dot-ok"],
    "descripcion" => "Inserta el chat en tu web con un snippet de JavaScript.",
    "snippet" => "<script src=\"https://cdn.automai.solutions/widget.js\"></script>\n<script>AutomAI.init({ companyId: \"EMP-001\", theme: \"dark\" });</script>",
    "campos" => [
      ["key" => "tema", "label" => "Tema", "type" => "select", "value" => "OSCURO", "options" => [
        ["value" => "CLARO", "label" => "Claro"],
        ["value" => "OSCURO", "label" => "Oscuro"],
      ]],
      ["key" => "posicion", "label" => "Posición", "type" => "select", "value" => "BOTTOM_RIGHT", "options" => [
        ["value" => "BOTTOM_RIGHT", "label" => "Bottom-right"],
        ["value" => "BOTTOM_LEFT", "label" => "Bottom-left"],
      ]],
    ],
    "nota" => "Puedes personalizar colores, comportamiento y eventos desde Configuración.",
    "acciones" => [
      ["type" => "secondary-outline", "label" => "Copiar snippet", "disabled" => false],
      ["type" => "primary", "label" => "Probar en vivo", "disabled" => false],
    ],
  ],
];

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Integraciones · Canales | AutomAI Solutions</title>

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
    .form-select,
    textarea.form-control {
      border-radius: 1rem;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background: rgba(2, 6, 23, 0.45);
      color: rgba(249, 250, 251, .92);
      box-shadow: none !important;
    }

    textarea.form-control {
      min-height: 110px;
    }

    .form-control::placeholder {
      color: rgba(203, 213, 245, .55);
    }

    .form-control:focus,
    .form-select:focus,
    textarea.form-control:focus {
      border-color: rgba(56, 189, 248, 0.65);
      box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.12) !important;
      background: rgba(2, 6, 23, 0.55);
      color: rgba(249, 250, 251, .95);
    }

    .input-group .btn {
      border-radius: 1rem;
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

    .btn-outline-danger {
      color: rgba(254, 202, 202, .95) !important;
      border-color: rgba(248, 113, 113, .45) !important;
      background: rgba(127, 29, 29, 0.16) !important;
    }

    .btn-outline-danger:hover {
      background: rgba(153, 27, 27, 0.35) !important;
      transform: translateY(-1px);
    }

    .btn-outline-primary {
      color: rgba(224, 242, 254, .95) !important;
      border-color: rgba(56, 189, 248, .45) !important;
      background: rgba(56, 189, 248, .10) !important;
    }

    .btn-outline-primary:hover {
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

    .user-avatar {
      width: 34px;
      height: 34px;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.6);
      background: radial-gradient(circle at 30% 0, var(--accent), #0f172a);
      padding: 2px;
      box-shadow: 0 0 12px rgba(56, 189, 248, 0.6);
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

    .page-hero {
      padding: 1.6rem 1.7rem 1.45rem;
      background:
        radial-gradient(circle at top left, rgba(56, 189, 248, 0.26), transparent 55%),
        radial-gradient(circle at bottom, rgba(236, 72, 153, 0.18), transparent 55%),
        rgba(15, 23, 42, 0.88);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 1.2rem;
      margin-bottom: 1.4rem;
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-subtle);
      box-shadow: var(--shadow-strong);
      backdrop-filter: blur(22px);
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
      max-width: 70ch;
      line-height: 1.45;
    }

    /* Cards */
    .int-card {
      height: 100%;
      padding: 1.25rem 1.25rem 1.15rem;
      border-radius: 1.35rem;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background:
        radial-gradient(circle at 20% 0, rgba(56, 189, 248, 0.18), transparent 60%),
        rgba(15, 23, 42, 0.90);
      box-shadow: 0 16px 36px rgba(15, 23, 42, 0.85);
      position: relative;
      overflow: hidden;
    }

    .int-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .8rem;
      margin-bottom: .65rem;
    }

    .int-title {
      display: flex;
      align-items: center;
      gap: .7rem;
      min-width: 0;
    }

    .int-icon {
      width: 46px;
      height: 46px;
      border-radius: 1.1rem;
      padding: 9px;
      background: rgba(2, 6, 23, 0.55);
      border: 1px solid rgba(148, 163, 184, 0.35);
      box-shadow: 0 12px 28px rgba(15, 23, 42, 0.65);
      flex: 0 0 auto;
    }

    .int-title h5 {
      margin: 0;
      font-weight: 900;
      letter-spacing: .02em;
      font-size: 1.02rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 38ch;
    }

    .small-help {
      font-size: .88rem;
      color: var(--text-muted);
      line-height: 1.45;
    }

    /* Status badge glass */
    .badge-glass {
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

    .status-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      display: inline-block;
      box-shadow: 0 0 12px rgba(56, 189, 248, .25);
    }

    .dot-ok {
      background: rgba(34, 197, 94, .95);
      box-shadow: 0 0 12px rgba(34, 197, 94, .35);
    }

    .dot-off {
      background: rgba(148, 163, 184, .95);
      box-shadow: 0 0 12px rgba(148, 163, 184, .25);
    }

    .dot-warn {
      background: rgba(245, 158, 11, .95);
      box-shadow: 0 0 12px rgba(245, 158, 11, .30);
    }

    .badge-ok {
      border-color: rgba(34, 197, 94, .40);
      color: rgba(187, 247, 208, .95);
      background: rgba(22, 163, 74, .12);
    }

    .badge-off {
      border-color: rgba(148, 163, 184, .40);
      color: rgba(203, 213, 245, .90);
      background: rgba(148, 163, 184, .10);
    }

    .badge-warn {
      border-color: rgba(245, 158, 11, .40);
      color: rgba(254, 243, 199, .95);
      background: rgba(245, 158, 11, .10);
    }

    code {
      color: rgba(224, 242, 254, .95);
      background: rgba(56, 189, 248, .08);
      border: 1px solid rgba(56, 189, 248, .20);
      padding: .12rem .35rem;
      border-radius: .5rem;
      font-weight: 800;
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
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <h4>AutomAI</h4>

    <a href="Dashboard.php">🏠 Dashboard</a>
    <a href="Chatbot-Configuracion.php">🤖 Configurar Chatbot</a>
    <a href="Integraciones-Canales.php" class="active">🔌 Integraciones</a>
    <a href="Reportes-Dashboard.php">📊 Reportes</a>
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
      <h5>Integraciones · Canales conectados</h5>
    </div>

    <div class="user-info">
      <div class="user-pill">
        <span><?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8"); ?></span>
        <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Avatar" class="user-avatar">
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="main-content">

    <section class="page-hero">
      <div>
        <div class="pill">● Canales</div>
        <h1>Canales conectados</h1>
        <p>Conecta y administra los canales por los que tu chatbot atenderá a clientes. Prueba el canal tras conectarlo para verificar su funcionamiento.</p>
      </div>

      <div class="d-flex gap-2">
        <?php foreach ($linksTop as $l): ?>
          <a href="<?= htmlspecialchars($l["href"], ENT_QUOTES, "UTF-8"); ?>" class="btn btn-outline-secondary btn-sm">
            <?= htmlspecialchars($l["label"], ENT_QUOTES, "UTF-8"); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </section>

    <div class="row g-4">
      <?php foreach ($canales as $c): ?>
        <div class="col-12 col-lg-6">
          <div class="int-card">

            <div class="int-head">
              <div class="int-title">
                <img class="int-icon" src="<?= htmlspecialchars($c["icon"], ENT_QUOTES, "UTF-8"); ?>" alt="">
                <h5><?= htmlspecialchars($c["nombre"], ENT_QUOTES, "UTF-8"); ?></h5>
              </div>

              <span class="badge-glass <?= htmlspecialchars($c["badge"]["class"], ENT_QUOTES, "UTF-8"); ?>">
                <span class="status-dot <?= htmlspecialchars($c["badge"]["dot"], ENT_QUOTES, "UTF-8"); ?>"></span>
                <?= htmlspecialchars($c["badge"]["text"], ENT_QUOTES, "UTF-8"); ?>
              </span>
            </div>

            <p class="small-help mb-3"><?= htmlspecialchars($c["descripcion"], ENT_QUOTES, "UTF-8"); ?></p>

            <?php if (!empty($c["campos"])): ?>
              <div class="row g-3">
                <?php foreach ($c["campos"] as $campo): ?>
                  <?php if ($campo["type"] === "readonly"): ?>
                    <div class="col-12">
                      <label class="form-label text-muted"><?= htmlspecialchars($campo["label"], ENT_QUOTES, "UTF-8"); ?></label>
                      <div class="input-group">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($campo["value"], ENT_QUOTES, "UTF-8"); ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button">Copiar</button>
                      </div>
                      <?php if (!empty($c["nota"])): ?>
                        <div class="small-help mt-1"><?= htmlspecialchars($c["nota"], ENT_QUOTES, "UTF-8"); ?></div>
                      <?php endif; ?>
                    </div>

                  <?php elseif ($campo["type"] === "select"): ?>
                    <div class="col-md-6">
                      <label class="form-label text-muted"><?= htmlspecialchars($campo["label"], ENT_QUOTES, "UTF-8"); ?></label>
                      <select class="form-select">
                        <?php foreach ($campo["options"] as $opt): ?>
                          <option value="<?= htmlspecialchars($opt["value"], ENT_QUOTES, "UTF-8"); ?>" <?= $opt["value"] === $campo["value"] ? "selected" : ""; ?>>
                            <?= htmlspecialchars($opt["label"], ENT_QUOTES, "UTF-8"); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                  <?php elseif ($campo["type"] === "checkbox"): ?>
                    <div class="col-12">
                      <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="<?= htmlspecialchars($c["codigo"] . "_" . $campo["key"], ENT_QUOTES, "UTF-8"); ?>" <?= $campo["value"] ? "checked" : ""; ?>>
                        <label class="form-check-label" for="<?= htmlspecialchars($c["codigo"] . "_" . $campo["key"], ENT_QUOTES, "UTF-8"); ?>">
                          <?= htmlspecialchars($campo["label"], ENT_QUOTES, "UTF-8"); ?>
                        </label>
                      </div>
                      <?php if (!empty($c["nota"])): ?>
                        <div class="small-help mt-2"><?= htmlspecialchars($c["nota"], ENT_QUOTES, "UTF-8"); ?></div>
                      <?php endif; ?>
                    </div>

                  <?php else: ?>
                    <div class="col-md-6">
                      <label class="form-label text-muted"><?= htmlspecialchars($campo["label"], ENT_QUOTES, "UTF-8"); ?></label>
                      <input
                        type="<?= htmlspecialchars($campo["type"], ENT_QUOTES, "UTF-8"); ?>"
                        class="form-control"
                        value="<?= htmlspecialchars($campo["value"], ENT_QUOTES, "UTF-8"); ?>"
                        placeholder="<?= htmlspecialchars($campo["placeholder"], ENT_QUOTES, "UTF-8"); ?>">
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($c["snippet"])): ?>
              <div class="mt-3">
                <label class="form-label text-muted">Snippet</label>
                <textarea class="form-control" rows="3" readonly><?= htmlspecialchars($c["snippet"], ENT_QUOTES, "UTF-8"); ?></textarea>
              </div>
            <?php endif; ?>

            <div class="d-flex justify-content-end gap-2 mt-3">
              <?php foreach ($c["acciones"] as $a): ?>
                <?php
                $classBtn = "btn btn-sm ";
                if ($a["type"] === "primary") $classBtn .= "btn-primary";
                if ($a["type"] === "secondary-outline") $classBtn .= "btn-outline-secondary";
                if ($a["type"] === "primary-outline") $classBtn .= "btn-outline-primary";
                if ($a["type"] === "danger-outline") $classBtn .= "btn-outline-danger";
                ?>
                <button type="button" class="<?= $classBtn; ?>" <?= $a["disabled"] ? "disabled" : ""; ?>>
                  <?= htmlspecialchars($a["label"], ENT_QUOTES, "UTF-8"); ?>
                </button>
              <?php endforeach; ?>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <footer>© 2026 AutomAI Solutions · Integraciones</footer>
  </main>

</body>

</html>