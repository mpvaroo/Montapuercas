<?php

declare(strict_types=1);

/**
 * Chatbot-Configuracion.php (SOLO VISUAL)
 * - No sesiones
 * - No BD
 * - Mismo estilo premium que Dashboard.php
 * - Sidebar "bien implementado" (240px) + navbar alineada (igual que el Dashboard/Usuarios final)
 */

$usuario = [
  "email" => "admin@empresa.com",
  "empresa" => "AutomAI Demo",
  "plan" => "Premium"
];

$modulos = [
  [
    "href" => "Chatbot-Respuestas.php",
    "icon" => "https://cdn-icons-png.flaticon.com/512/4712/4712100.png",
    "title" => "Respuestas automáticas",
    "desc" => "Gestiona las preguntas frecuentes y define cómo responde tu chatbot.",
    "tag"  => "FAQs"
  ],
  [
    "href" => "Chatbot-ConfiguracionAvanzada.php",
    "icon" => "https://cdn-icons-png.flaticon.com/512/1828/1828940.png",
    "title" => "Configuración avanzada",
    "desc" => "Ajusta idioma, horario, tono y reglas de interacción.",
    "tag"  => "Reglas"
  ],
  [
    "href" => "Chatbot-Entrenamiento.php",
    "icon" => "https://cdn-icons-png.flaticon.com/512/1048/1048953.png",
    "title" => "Entrenamiento IA",
    "desc" => "Sube datasets o FAQs para entrenar el modelo de IA.",
    "tag"  => "Datasets"
  ],
  [
    "href" => "Chatbot-VistaPrevia.php",
    "icon" => "https://cdn-icons-png.flaticon.com/512/2910/2910768.png",
    "title" => "Vista previa",
    "desc" => "Simula una conversación con tu chatbot y prueba las respuestas.",
    "tag"  => "Testing"
  ],
];

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuración del Chatbot | AutomAI Solutions</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
       SIDEBAR (IGUAL A CONFIG/USUARIOS) - 240px
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
      color: var(--text-muted);
    }

    .navbar-title h5 {
      margin: 0;
      font-size: 1.15rem;
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

    /* MAIN */
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

    /* HERO */
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
      max-width: 62ch;
      line-height: 1.45;
    }

    /* Module cards */
    .module-grid {
      margin-top: 1.15rem;
    }

    .module-card {
      height: 100%;
      padding: 1.25rem 1.25rem 1.15rem;
      border-radius: 1.35rem;
      border: 1px solid rgba(148, 163, 184, 0.35);
      background:
        radial-gradient(circle at 20% 0, rgba(56, 189, 248, 0.18), transparent 60%),
        rgba(15, 23, 42, 0.90);
      box-shadow: 0 16px 36px rgba(15, 23, 42, 0.85);
      transition: transform .18s ease, box-shadow .22s ease, border-color .22s ease, background .22s ease;
      position: relative;
      overflow: hidden;
      text-decoration: none;
      color: var(--text-main);
      display: block;
    }

    .module-card::after {
      content: "";
      position: absolute;
      inset: -120% -60%;
      background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.10), transparent);
      transform: rotate(18deg);
      opacity: 0;
      transition: opacity .2s ease;
      pointer-events: none;
    }

    .module-card:hover {
      transform: translateY(-4px);
      border-color: rgba(56, 189, 248, 0.55);
      background:
        radial-gradient(circle at 20% 0, rgba(56, 189, 248, 0.26), transparent 60%),
        rgba(15, 23, 42, 0.92);
      box-shadow: 0 22px 50px rgba(15, 23, 42, 0.92), 0 0 24px rgba(56, 189, 248, 0.22);
    }

    .module-card:hover::after {
      opacity: 1;
    }

    .module-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .8rem;
      margin-bottom: .65rem;
    }

    .module-icon {
      width: 46px;
      height: 46px;
      border-radius: 1.1rem;
      padding: 9px;
      background: rgba(2, 6, 23, 0.55);
      border: 1px solid rgba(148, 163, 184, 0.35);
      box-shadow: 0 12px 28px rgba(15, 23, 42, 0.65);
    }

    .module-tag {
      padding: .16rem .55rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.45);
      color: var(--text-muted);
      font-size: .7rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      background: rgba(2, 6, 23, 0.35);
      white-space: nowrap;
      font-weight: 900;
    }

    .module-card h5 {
      margin: 0 0 .35rem;
      font-weight: 900;
      letter-spacing: .02em;
      font-size: 1.02rem;
    }

    .module-card p {
      margin: 0;
      color: var(--text-muted);
      font-size: .88rem;
      line-height: 1.4;
    }

    footer {
      text-align: center;
      padding-top: 1.6rem;
      font-size: .78rem;
      color: var(--text-muted);
    }

    /* Fade-in */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .fade-in {
      opacity: 0;
      animation: fadeInUp .6s ease-out forwards;
    }

    .delay-1 {
      animation-delay: .08s;
    }

    .delay-2 {
      animation-delay: .16s;
    }

    .delay-3 {
      animation-delay: .24s;
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

  <!-- Sidebar (240px, igual al resto) -->
  <aside class="sidebar">
    <h4>AutomAI</h4>

    <a href="Dashboard.php">🏠 Dashboard</a>
    <a href="Chatbot-Configuracion.php" class="active">🤖 Configurar Chatbot</a>
    <a href="Integraciones-Canales.php">🔌 Integraciones</a>
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
      <h5>Configuración del Chatbot</h5>
    </div>

    <div class="user-info">
      <div class="user-pill">
        <span><?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8"); ?></span>
        <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Avatar usuario" class="user-avatar">
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="main-content">

    <section class="section-card page-hero fade-in">
      <div>
        <div class="pill">● Centro de configuración</div>
        <h1>Centro de Configuración del Chatbot</h1>
        <p>Selecciona el módulo que deseas gestionar para personalizar el comportamiento de tu asistente virtual.</p>
      </div>

      <div style="text-align:right; color: var(--text-muted); font-size:.82rem;">
        <div style="letter-spacing:.12em; text-transform:uppercase;">Plan</div>
        <div style="font-weight:900; color: var(--text-main);">
          <?= htmlspecialchars($usuario["plan"], ENT_QUOTES, "UTF-8"); ?>
        </div>
      </div>
    </section>

    <div class="row g-4 module-grid fade-in delay-1">
      <?php foreach ($modulos as $i => $m): ?>
        <div class="col-md-6 col-lg-3 fade-in <?= "delay-" . min(3, $i + 1); ?>">
          <a href="<?= htmlspecialchars($m["href"], ENT_QUOTES, "UTF-8"); ?>" class="module-card">
            <div class="module-head">
              <img src="<?= htmlspecialchars($m["icon"], ENT_QUOTES, "UTF-8"); ?>" alt="" class="module-icon">
              <span class="module-tag"><?= htmlspecialchars($m["tag"], ENT_QUOTES, "UTF-8"); ?></span>
            </div>
            <h5><?= htmlspecialchars($m["title"], ENT_QUOTES, "UTF-8"); ?></h5>
            <p><?= htmlspecialchars($m["desc"], ENT_QUOTES, "UTF-8"); ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <footer>
      © 2026 AutomAI Solutions · Configuración del Chatbot
    </footer>

  </main>

</body>

</html>