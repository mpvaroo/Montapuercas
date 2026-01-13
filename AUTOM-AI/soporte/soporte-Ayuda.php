<?php

declare(strict_types=1);

/**
 * Soporte-Ayuda.php (SOLO VISUAL)
 * - NO usa sesiones
 * - NO hace consultas
 * - Datos MOCK para conectar a BD más adelante
 */

$usuario = [
  "email"   => "admin@empresa.com",
  "empresa" => "AutomAI Demo",
  "plan"    => "Premium"
];

$estadoSistema = [
  ["label" => "API principal · Operativo", "type" => "ok"],
  ["label" => "Webhooks · Operativo", "type" => "ok"],
  ["label" => "Integración Gmail · Latencia moderada", "type" => "issue"],
];

$docCards = [
  ["title" => "Primeros pasos", "sub" => "Alta, canales y prueba inicial"],
  ["title" => "Configuración de Chatbot", "sub" => "Respuestas, IA y vista previa"],
  ["title" => "Integraciones", "sub" => "WhatsApp, Telegram, CRM, Sheets"],
  ["title" => "Reportes & Métricas", "sub" => "KPIs, exportación, histórico"],
];

$faq = [
  [
    "q" => "¿Cómo conecto WhatsApp Business API?",
    "a" => "Entra en Integraciones → Canales, añade tu Access Token y Phone Number ID, registra el Webhook en Meta y pulsa Probar canal."
  ],
  [
    "q" => "¿Cómo mapeo campos al CRM o a Google Sheets?",
    "a" => "Ve a Integraciones → CRM & Sheets y en Mapeo de campos asigna origen (bot) a destino (CRM/columna). Guarda y prueba."
  ],
  [
    "q" => "¿Puedo entrenar la IA con mis políticas y FAQs?",
    "a" => "Sí. En Configuración del Chatbot → Entrenamiento IA sube documentos y ejemplos. Luego prueba en Vista previa."
  ],
  [
    "q" => "¿Dónde consulto los logs y el estado de integraciones?",
    "a" => "Visita Integraciones → Historial para ver cada evento con resultado, duración y detalle del log."
  ],
];

$tickets = [
  ["id" => 3421, "asunto" => "Error 401 al conectar CRM", "estado" => "En curso",   "estado_type" => "warn", "prioridad" => "Alta",   "actualizado" => "31/10/2025 12:14"],
  ["id" => 3390, "asunto" => "Retraso en notificaciones de Gmail", "estado" => "Resuelto", "estado_type" => "ok",   "prioridad" => "Normal", "actualizado" => "30/10/2025 17:02"],
  ["id" => 3358, "asunto" => "Snippet Web Chat no carga", "estado" => "Bloqueado", "estado_type" => "bad",  "prioridad" => "Crítica", "actualizado" => "29/10/2025 09:41"],
];

function badgeEstado(string $type): array
{
  if ($type === "ok")   return ["bg" => "rgba(34,197,94,.16)",  "bd" => "rgba(34,197,94,.45)",  "tx" => "#bbf7d0"];
  if ($type === "warn") return ["bg" => "rgba(245,158,11,.16)", "bd" => "rgba(245,158,11,.45)", "tx" => "#fed7aa"];
  return                 ["bg" => "rgba(239,68,68,.16)",        "bd" => "rgba(239,68,68,.45)",  "tx" => "#fecaca"];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Soporte y Ayuda | AutomAI Solutions</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      --radius-lg: 1.5rem;
    }

    * {
      box-sizing: border-box
    }

    body {
      margin: 0;
      min-height: 100vh;
      color: var(--text-main);
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background:
        radial-gradient(circle at top left, #1d4ed8 0, transparent 45%),
        radial-gradient(circle at bottom right, #0f766e 0, transparent 45%),
        radial-gradient(circle at top right, #a855f7 0, transparent 40%),
        var(--bg-main);
      background-attachment: fixed;
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
       SIDEBAR (IGUAL QUE TU "CONFIGURACIÓN AVANZADA": 240px)
       ========================================================= */
    .sidebar {
      width: 240px;
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

    /* TOP BAR */
    .navbar-top {
      height: 72px;
      padding: 0 2.4rem;
      margin-left: 240px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(90deg, rgba(6, 16, 40, .98), rgba(15, 23, 42, .96), rgba(6, 16, 40, .98));
      border-bottom: 1px solid rgba(30, 64, 175, .6);
      box-shadow: 0 12px 34px rgba(15, 23, 42, .95);
      position: sticky;
      top: 0;
      z-index: 30;
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
      box-shadow: 0 0 12px rgba(56, 189, 248, .6);
      padding: 2px;
    }

    /* MAIN */
    .main-content {
      margin-left: 240px;
      padding: 1.8rem 2.4rem 2.6rem;
    }

    .section-card {
      background: var(--bg-elevated);
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-subtle);
      box-shadow: 0 18px 45px rgba(15, 23, 42, .9), 0 0 60px rgba(15, 23, 42, .95);
      backdrop-filter: blur(22px);
      overflow: hidden;
    }

    .text-muted-soft {
      color: rgba(203, 213, 245, .75);
    }

    /* SEARCH */
    .search-wrap {
      padding: 1.35rem 1.4rem;
    }

    .search-wrap h6 {
      margin: 0 0 .35rem;
      font-size: .9rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: rgba(249, 250, 251, .92);
    }

    .glass-input,
    .glass-select,
    .glass-textarea {
      background: rgba(10, 14, 30, .55) !important;
      border: 1px solid rgba(255, 255, 255, .14) !important;
      color: var(--text-main) !important;
      border-radius: 999px !important;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, .06);
    }

    .glass-textarea {
      border-radius: 1rem !important;
    }

    .glass-input::placeholder,
    .glass-textarea::placeholder {
      color: rgba(236, 242, 255, .45);
    }

    .glass-btn {
      border: none;
      border-radius: 999px;
      padding: .6rem 1.2rem;
      font-weight: 800;
      color: #06101A;
      background: radial-gradient(circle at 10% 0, var(--accent), #2563eb);
      box-shadow: 0 10px 25px rgba(59, 130, 246, .55), 0 0 22px rgba(56, 189, 248, .75);
      transition: transform .15s ease, box-shadow .2s ease;
    }

    .glass-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 16px 35px rgba(37, 99, 235, .8), 0 0 28px rgba(56, 189, 248, 1);
    }

    /* STATUS LIST */
    .status-pill {
      padding: .65rem .8rem;
      border-radius: 12px;
      background: rgba(10, 14, 30, .35);
      border: 1px solid rgba(255, 255, 255, .10);
      color: rgba(249, 250, 251, .92);
    }

    .status-ok {
      background: rgba(34, 197, 94, .12);
      border-color: rgba(34, 197, 94, .35);
      color: #bbf7d0;
    }

    .status-issue {
      background: rgba(245, 158, 11, .12);
      border-color: rgba(245, 158, 11, .35);
      color: #fed7aa;
    }

    /* DOC CARDS */
    .doc-card {
      height: 100%;
      padding: 1rem 1.1rem;
      border-radius: 1rem;
      background: rgba(10, 14, 30, .35);
      border: 1px solid rgba(255, 255, 255, .10);
      text-decoration: none;
      transition: transform .15s ease, border-color .2s ease, box-shadow .2s ease;
      display: block;
    }

    .doc-card:hover {
      transform: translateY(-2px);
      border-color: rgba(56, 189, 248, .35);
      box-shadow: 0 16px 35px rgba(15, 23, 42, .75), 0 0 18px rgba(56, 189, 248, .25);
    }

    .doc-card .t {
      font-weight: 800;
      color: var(--text-main);
    }

    .doc-card .s {
      font-size: .86rem;
      color: rgba(203, 213, 245, .75);
      margin-top: .15rem;
    }

    /* ACCORDION (dark) */
    .accordion-item {
      background: rgba(10, 14, 30, .28);
      border: 1px solid rgba(255, 255, 255, .10);
      border-radius: 1rem !important;
      overflow: hidden;
      margin-bottom: .8rem;
    }

    .accordion-button {
      background: transparent !important;
      color: var(--text-main) !important;
      font-weight: 800;
      border: none;
      box-shadow: none !important;
      padding: 1rem 1.1rem;
    }

    .accordion-button::after {
      filter: invert(1) opacity(.85);
    }

    .accordion-body {
      color: rgba(236, 242, 255, .78);
      padding: .9rem 1.1rem 1rem;
    }

    .accordion-collapse {
      background: rgba(10, 14, 30, .18);
    }

    /* TABLE */
    .table-wrap {
      padding: 1.1rem 1.2rem 1.2rem;
    }

    .table-glass {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      overflow: hidden;
      border-radius: 1.1rem;
      background: rgba(10, 14, 30, .28);
      border: 1px solid rgba(255, 255, 255, .10);
    }

    .table-glass thead th {
      font-size: .78rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: rgba(249, 250, 251, .92) !important;
      background: rgba(15, 23, 42, .85);
      border-bottom: 1px solid rgba(255, 255, 255, .10);
      padding: .95rem 1rem;
      white-space: nowrap;
    }

    .table-glass tbody td {
      color: rgba(249, 250, 251, .88) !important;
      padding: .95rem 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, .08);
      vertical-align: middle;
    }

    .table-glass tbody tr:hover td {
      background: rgba(56, 189, 248, .06);
    }

    .badge-soft {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: .25rem .6rem;
      border-radius: 999px;
      font-size: .78rem;
      font-weight: 900;
      letter-spacing: .02em;
      border: 1px solid rgba(255, 255, 255, .14);
      background: rgba(10, 14, 30, .35);
      color: rgba(249, 250, 251, .92);
      white-space: nowrap;
    }

    .btn-ghost {
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .55);
      background: rgba(15, 23, 42, .55);
      color: rgba(249, 250, 251, .9);
      padding: .35rem .8rem;
      font-weight: 800;
      transition: transform .15s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .btn-ghost:hover {
      transform: translateY(-1px);
      border-color: rgba(56, 189, 248, .5);
      box-shadow: 0 12px 28px rgba(15, 23, 42, .75), 0 0 18px rgba(56, 189, 248, .25);
    }

    /* PAGINATION */
    .pagination .page-link {
      background: rgba(10, 14, 30, .35);
      border: 1px solid rgba(255, 255, 255, .10);
      color: rgba(249, 250, 251, .88);
    }

    .pagination .page-item.active .page-link {
      background: linear-gradient(145deg, var(--accent), #2563eb);
      border-color: rgba(56, 189, 248, .45);
      color: #06101A;
      font-weight: 900;
    }

    .pagination .page-item.disabled .page-link {
      opacity: .45;
    }

    footer {
      text-align: center;
      padding-top: 1.6rem;
      font-size: .78rem;
      color: rgba(203, 213, 245, .75);
    }

    @media (max-width: 1100px) {
      .navbar-top {
        padding: 0 1.2rem;
      }

      .main-content {
        padding: 1.2rem 1.2rem 2rem;
      }
    }

    @media (max-width: 992px) {
      .navbar-top {
        margin-left: 0;
      }

      .main-content {
        margin-left: 0;
        padding-inline: 1.25rem;
      }

      .sidebar {
        position: static;
        width: 100%;
        height: auto;
        border-right: none;
      }
    }
  </style>
</head>

<body>

  <!-- Sidebar (IGUAL a Configuración avanzada) -->
  <aside class="sidebar">
    <h4>AutomAI</h4>

    <a href="Dashboard.php">🏠 Dashboard</a>
    <a href="Chatbot-Configuracion.php">🤖 Configurar Chatbot</a>
    <a href="Integraciones-Canales.php">🔌 Integraciones</a>
    <a href="Reportes-Dashboard.php">📊 Reportes</a>
    <a href="Usuarios-Gestion.php">👥 Usuarios</a>
    <a href="Configuracion-General.php">⚙️ Configuracion</a>
    <a href="Soporte-Ayuda.php" class="active">💬 Soporte</a>

    <hr>
    <a href="Logout.php" class="text-danger">🚪 Cerrar sesión</a>
  </aside>

  <!-- Navbar superior -->
  <header class="navbar-top">
    <div class="navbar-title">
      <span><?= htmlspecialchars($usuario["empresa"], ENT_QUOTES, "UTF-8"); ?></span>
      <h5>Soporte y ayuda</h5>
    </div>

    <div class="user-pill">
      <span><?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8"); ?></span>
      <img class="user-avatar" alt="Avatar" src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png">
    </div>
  </header>

  <!-- Contenido principal -->
  <main class="main-content">

    <!-- Buscador + Estado -->
    <div class="row g-4">
      <div class="col-12 col-lg-8">
        <section class="section-card search-wrap">
          <h6>¿En qué podemos ayudarte?</h6>
          <div class="d-flex gap-2">
            <input class="form-control glass-input" placeholder="Buscar en documentación, FAQ o tickets…">
            <button class="glass-btn">Buscar</button>
          </div>
          <div class="mt-2 text-muted-soft" style="font-size:.86rem;">
            Ejemplos: “conectar WhatsApp”, “mapear campos CRM”, “exportar reportes”.
          </div>
        </section>
      </div>

      <div class="col-12 col-lg-4">
        <section class="section-card search-wrap">
          <h6>Estado del sistema</h6>

          <div class="d-grid gap-2 mt-2">
            <?php foreach ($estadoSistema as $s): ?>
              <div class="status-pill <?= $s["type"] === "ok" ? "status-ok" : "status-issue"; ?>">
                <?= htmlspecialchars($s["label"], ENT_QUOTES, "UTF-8"); ?>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="mt-2 text-muted-soft" style="font-size:.82rem;">
            Última actualización: hace 5 min
          </div>
        </section>
      </div>
    </div>

    <!-- Documentación -->
    <section class="section-card mt-4 p-4">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="text-muted-soft" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.12em;">Centro de documentación</div>
          <h6 class="mt-1 mb-0">Recursos rápidos</h6>
        </div>
        <span class="badge-soft">Mock data</span>
      </div>

      <div class="row g-3 mt-2">
        <?php foreach ($docCards as $d): ?>
          <div class="col-12 col-md-6 col-xl-3">
            <a class="doc-card" href="#">
              <div class="t"><?= htmlspecialchars($d["title"], ENT_QUOTES, "UTF-8"); ?></div>
              <div class="s"><?= htmlspecialchars($d["sub"], ENT_QUOTES, "UTF-8"); ?></div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- FAQ + Ticket + Chat -->
    <div class="row g-4 mt-1">
      <!-- FAQ -->
      <div class="col-12 col-xl-6">
        <section class="section-card p-4">
          <div class="text-muted-soft" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.12em;">Preguntas frecuentes</div>
          <h6 class="mt-1">Respuestas rápidas</h6>

          <div class="accordion mt-3" id="faq">
            <?php foreach ($faq as $i => $item): ?>
              <?php $open = ($i === 0); ?>
              <div class="accordion-item">
                <h2 class="accordion-header" id="q<?= $i; ?>">
                  <button class="accordion-button <?= $open ? "" : "collapsed"; ?>" type="button"
                    data-bs-toggle="collapse" data-bs-target="#a<?= $i; ?>"
                    aria-expanded="<?= $open ? "true" : "false"; ?>" aria-controls="a<?= $i; ?>">
                    <?= htmlspecialchars($item["q"], ENT_QUOTES, "UTF-8"); ?>
                  </button>
                </h2>
                <div id="a<?= $i; ?>" class="accordion-collapse collapse <?= $open ? "show" : ""; ?>"
                  aria-labelledby="q<?= $i; ?>" data-bs-parent="#faq">
                  <div class="accordion-body">
                    <?= htmlspecialchars($item["a"], ENT_QUOTES, "UTF-8"); ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="mt-3 text-muted-soft" style="font-size:.85rem;">
            ¿No ves tu duda aquí? Abre un ticket o usa el chat.
          </div>
        </section>
      </div>

      <!-- Ticket -->
      <div class="col-12 col-xl-3">
        <section class="section-card p-4">
          <div class="text-muted-soft" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.12em;">Abrir ticket</div>
          <h6 class="mt-1">Contacta soporte</h6>

          <div class="mt-3 d-grid gap-2">
            <label class="text-muted-soft" style="font-size:.82rem;">Asunto</label>
            <input class="form-control glass-input" placeholder="Describe el problema">

            <label class="text-muted-soft mt-2" style="font-size:.82rem;">Prioridad</label>
            <select class="form-select glass-select">
              <option>Normal</option>
              <option>Alta</option>
              <option>Crítica</option>
            </select>

            <label class="text-muted-soft mt-2" style="font-size:.82rem;">Canal afectado</label>
            <select class="form-select glass-select">
              <option>WhatsApp</option>
              <option>Telegram</option>
              <option>Gmail</option>
              <option>Web Chat</option>
              <option>CRM/Sheets</option>
            </select>

            <label class="text-muted-soft mt-2" style="font-size:.82rem;">Descripción</label>
            <textarea class="form-control glass-textarea" rows="4" placeholder="Pasos, capturas, IDs…"></textarea>

            <button class="glass-btn mt-2">Enviar ticket (demo)</button>

            <div class="text-muted-soft" style="font-size:.82rem;">
              Tiempo medio de respuesta: 4 h laborables.
            </div>
          </div>
        </section>
      </div>

      <!-- Chat -->
      <div class="col-12 col-xl-3">
        <section class="section-card p-4">
          <div class="text-muted-soft" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.12em;">Chat en vivo</div>
          <h6 class="mt-1">Widget (placeholder)</h6>

          <div class="mt-3"
            style="height:220px; border-radius:1rem; border:1px dashed rgba(148,163,184,.45);
                      background: rgba(10,14,30,.22);
                      display:flex; align-items:center; justify-content:center; color: rgba(236,242,255,.55);">
            Placeholder del widget de soporte
          </div>

          <div class="d-grid gap-2 mt-3">
            <button class="btn-ghost">Iniciar chat</button>
            <button class="btn-ghost">Ver horarios</button>
          </div>

          <div class="text-muted-soft mt-2" style="font-size:.82rem;">
            Horario: L–V 9:00–18:00 CET
          </div>
        </section>
      </div>
    </div>

    <!-- Historial tickets -->
    <section class="section-card mt-4 table-wrap">
      <div class="d-flex justify-content-between align-items-center px-2">
        <div>
          <div class="text-muted-soft" style="font-size:.78rem; text-transform:uppercase; letter-spacing:.12em;">Historial de tickets</div>
          <h6 class="mt-1 mb-0">Listado</h6>
        </div>
        <div class="d-flex gap-2">
          <button class="btn-ghost">Exportar CSV</button>
          <button class="btn-ghost">Exportar PDF</button>
        </div>
      </div>

      <div class="table-responsive mt-3">
        <table class="table-glass">
          <thead>
            <tr>
              <th>ID</th>
              <th>Asunto</th>
              <th>Estado</th>
              <th>Prioridad</th>
              <th>Actualizado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tickets as $t): ?>
              <?php $b = badgeEstado($t["estado_type"]); ?>
              <tr>
                <td>#<?= (int)$t["id"]; ?></td>
                <td><?= htmlspecialchars($t["asunto"], ENT_QUOTES, "UTF-8"); ?></td>
                <td>
                  <span class="badge-soft" style="background:<?= $b["bg"]; ?>; border-color:<?= $b["bd"]; ?>; color:<?= $b["tx"]; ?>">
                    <?= htmlspecialchars($t["estado"], ENT_QUOTES, "UTF-8"); ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($t["prioridad"], ENT_QUOTES, "UTF-8"); ?></td>
                <td><?= htmlspecialchars($t["actualizado"], ENT_QUOTES, "UTF-8"); ?></td>
                <td class="text-end">
                  <button class="btn-ghost">Ver</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-3 px-2">
        <div class="text-muted-soft" style="font-size:.82rem;">Mostrando 1–3 de 28 tickets</div>
        <ul class="pagination pagination-sm mb-0">
          <li class="page-item disabled"><a class="page-link" href="#">«</a></li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">»</a></li>
        </ul>
      </div>
    </section>

    <footer>
      © 2026 AutomAI Solutions · Soporte y Ayuda · Plan: <?= htmlspecialchars($usuario["plan"], ENT_QUOTES, "UTF-8"); ?>
    </footer>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>