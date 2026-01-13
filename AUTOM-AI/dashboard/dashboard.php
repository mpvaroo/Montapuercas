<?php

declare(strict_types=1);

/**
 * Dashboard.php (SOLO VISUAL)
 * - NO usa sesiones
 * - NO hace consultas
 * - Los datos son MOCK para que luego los conectes a BD
 */

// ----------------------------
// MOCK DATA (luego vendrá de BD)
// ----------------------------
$usuario = [
  "email" => "admin@empresa.com",
  "rol" => "admin",
  "empresa" => "AutomAI Demo",
  "plan" => "Premium"
];

$kpis = [
  ["label" => "Consultas este mes", "value" => "76k", "tag" => "+14% vs mes anterior"],
  ["label" => "Usuarios únicos", "value" => "1.5M", "tag" => "Omnicanal"],
  ["label" => "Valor generado", "value" => "€3.6k", "tag" => "Tickets automatizados"],
  ["label" => "CSAT promedio", "value" => "4.7★", "tag" => "Basado en encuestas"],
];

$canales = [
  ["label" => "WhatsApp", "value" => "+32%", "trend" => "Canal más activo", "trend_color" => "#bbf7d0"],
  ["label" => "Web Chat", "value" => "18.4k", "trend" => "Sesiones esta semana", "trend_color" => "#bbf7d0"],
  ["label" => "Instagram DM", "value" => "8.9k", "trend" => "Campaña en curso", "trend_color" => "#fed7aa"],
  ["label" => "Tasa de automatización", "value" => "87%", "trend" => "Resueltas sin agente", "trend_color" => "#bbf7d0"],
];

$topChatbots = [
  ["titulo" => "Soporte Ecommerce · ES", "sub" => "Canales: Web, WhatsApp", "kpi1" => "24.5k consultas", "kpi2" => "↑ 18% rendimiento", "kpi2_color" => "#bbf7d0"],
  ["titulo" => "Onboarding SaaS · LATAM", "sub" => "Canales: Web, Instagram", "kpi1" => "11.2k consultas", "kpi2" => "T. resp.: 1.9s", "kpi2_color" => "#bfdbfe"],
  ["titulo" => "Soporte técnico nivel 1", "sub" => "Canales: Web, Telegram", "kpi1" => "8.7k consultas", "kpi2" => "Derivación: 9%", "kpi2_color" => "#fed7aa"],
];

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | AutomAI Solutions</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg-main: #020617;
      --bg-elevated: rgba(15, 23, 42, 0.96);
      --bg-soft: rgba(15, 23, 42, 0.88);
      --accent: #38bdf8;
      --accent-strong: #0ea5e9;
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
      font-weight: 700;
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
      font-weight: 800;
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

    /* =========================================================
       TU DASHBOARD (mismo contenido, sin tocar lógica)
       ========================================================= */

    .dashboard-grid {
      display: grid;
      grid-template-columns: minmax(0, 2.1fr) minmax(0, 1.4fr);
      gap: 1.75rem;
      align-items: stretch;
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
    .hero-card {
      padding: 1.8rem 1.8rem 1.6rem;
      background:
        radial-gradient(circle at top left, rgba(56, 189, 248, 0.3), transparent 55%),
        radial-gradient(circle at bottom, rgba(236, 72, 153, 0.22), transparent 55%),
        var(--bg-soft);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 320px;
    }

    .hero-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 1.2rem;
    }

    .hero-title-block {
      max-width: 60%;
      z-index: 2;
    }

    .badge-kpi {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      padding: .28rem .8rem;
      border-radius: 999px;
      font-size: .7rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      border: 1px solid rgba(56, 189, 248, 0.65);
      color: #e0f2fe;
      background: rgba(15, 23, 42, 0.9);
      font-weight: 800;
    }

    .hero-title-block h1 {
      margin: .9rem 0 .35rem;
      font-size: 1.7rem;
      font-weight: 800;
      letter-spacing: .04em;
      text-transform: uppercase;
      color: var(--text-main);
    }

    .hero-title-block p {
      margin: 0;
      font-size: .9rem;
      color: var(--text-muted);
      max-width: 90%;
    }

    .hero-cta-row {
      margin-top: 1.4rem;
      display: flex;
      align-items: center;
      gap: .9rem;
    }

    .btn-primary-glow {
      border-radius: 999px;
      border: none;
      padding: .55rem 1.4rem;
      font-size: .9rem;
      font-weight: 800;
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      color: #f9fafb;
      background: radial-gradient(circle at 10% 0, #38bdf8, #2563eb);
      box-shadow: 0 10px 25px rgba(59, 130, 246, 0.7), 0 0 26px rgba(56, 189, 248, 0.95);
      transition: transform .18s ease, box-shadow .22s ease, background .18s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      text-decoration: none;
    }

    .btn-primary-glow::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.25), transparent);
      transform: translateX(-120%);
      opacity: 0;
      pointer-events: none;
    }

    .btn-primary-glow:hover {
      transform: translateY(-1px);
      background: radial-gradient(circle at 10% 0, #0ea5e9, #1d4ed8);
      box-shadow: 0 16px 35px rgba(37, 99, 235, 0.9), 0 0 32px rgba(56, 189, 248, 1);
    }

    .btn-primary-glow:hover::after {
      opacity: 1;
      transform: translateX(160%);
      transition: transform .9s ease-out, opacity .3s ease-out;
    }

    .hero-cta-row small {
      font-size: .78rem;
      color: var(--text-muted);
    }

    .hero-stats-row {
      margin-top: 1.6rem;
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1rem;
      z-index: 2;
    }

    .hero-stat {
      padding: .6rem .75rem;
      border-radius: 1rem;
      background: rgba(15, 23, 42, 0.85);
      border: 1px solid rgba(148, 163, 184, 0.6);
      font-size: .75rem;
    }

    .hero-stat-label {
      color: var(--text-muted);
      margin-bottom: .15rem;
    }

    .hero-stat-value {
      font-size: 1.05rem;
      font-weight: 800;
    }

    .hero-stat-tag {
      font-size: .7rem;
      color: #bbf7d0;
      font-weight: 700;
    }

    .hero-image-wrapper {
      position: relative;
      height: 250px;
      width: 220px;
      border-radius: 2.2rem;
      overflow: hidden;
      flex-shrink: 0;
      box-shadow: 0 20px 45px rgba(15, 23, 42, 0.95), 0 0 40px rgba(56, 189, 248, .65);
    }

    .hero-image-wrapper::before {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at top, rgba(0, 0, 0, 0), rgba(15, 23, 42, 0.6));
      pointer-events: none;
    }

    .hero-image-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transform: scale(1.05);
      transition: transform .6s ease;
    }

    .hero-card:hover .hero-image-wrapper img {
      transform: scale(1.1);
    }

    .hero-glow-orbit {
      position: absolute;
      inset: auto -40%;
      height: 60px;
      bottom: -16px;
      background: radial-gradient(circle at 50% 0, rgba(56, 189, 248, 0.7), transparent 65%);
      opacity: .75;
      filter: blur(10px);
    }

    /* RIGHT */
    .card-analytics {
      padding: 1.4rem 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1.1rem;
    }

    .card-analytics-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: .84rem;
    }

    .pill-muted {
      padding: .18rem .7rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.6);
      color: var(--text-muted);
      font-size: .7rem;
      font-weight: 800;
      background: rgba(15, 23, 42, .65);
    }

    .chart-placeholder {
      height: 150px;
      border-radius: 1rem;
      background:
        radial-gradient(circle at top, rgba(56, 189, 248, 0.24), transparent 65%),
        linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.9));
      position: relative;
      overflow: hidden;
      border: 1px solid rgba(148, 163, 184, .25);
    }

    .chart-line {
      position: absolute;
      inset: 0;
      background: repeating-linear-gradient(to right, transparent 0, transparent 16px, rgba(30, 64, 175, 0.45) 16px, rgba(30, 64, 175, 0.45) 17px);
      opacity: .4;
    }

    .chart-curve {
      position: absolute;
      inset: 0;
      background: conic-gradient(from 180deg, rgba(56, 189, 248, .0), rgba(56, 189, 248, .9), rgba(14, 165, 233, .0), rgba(236, 72, 153, .9), rgba(56, 189, 248, .0));
      mix-blend-mode: screen;
      filter: blur(18px);
      animation: chartPulse 5s ease-in-out infinite alternate;
    }

    .chart-dots {
      position: absolute;
      inset: 0;
      padding: .9rem 1.2rem;
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
    }

    .chart-dot {
      width: 10px;
      height: 10px;
      border-radius: 999px;
      background: #38bdf8;
      box-shadow: 0 0 14px rgba(56, 189, 248, 0.9);
      transform-origin: bottom;
      animation: floatDot 4s ease-in-out infinite alternate;
    }

    .chart-dot:nth-child(2) {
      height: 50%;
      animation-delay: .2s;
    }

    .chart-dot:nth-child(3) {
      height: 70%;
      animation-delay: .35s;
      background: #f97316;
      box-shadow: 0 0 14px rgba(249, 115, 22, 0.9);
    }

    .chart-dot:nth-child(4) {
      height: 40%;
      animation-delay: .5s;
      background: #22c55e;
      box-shadow: 0 0 14px rgba(34, 197, 94, 0.9);
    }

    .mini-metrics {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: .8rem;
      font-size: .8rem;
    }

    .mini-metric {
      padding: .6rem .75rem;
      border-radius: 1rem;
      background: rgba(15, 23, 42, 0.9);
      border: 1px solid rgba(148, 163, 184, .35);
    }

    .mini-metric span.label {
      display: block;
      color: var(--text-muted);
      margin-bottom: .1rem;
    }

    .mini-metric span.value {
      font-weight: 800;
      font-size: .95rem;
      color: rgba(249, 250, 251, .92);
    }

    .mini-metric span.trend {
      display: block;
      margin-top: .15rem;
      font-weight: 700;
      font-size: .76rem;
    }

    /* LOWER */
    .bottom-row {
      margin-top: 1.8rem;
      display: grid;
      grid-template-columns: minmax(0, 2fr) minmax(0, 1.4fr);
      gap: 1.5rem;
    }

    .list-card {
      padding: 1.3rem 1.5rem 1.2rem;
    }

    .list-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: .9rem;
    }

    .list-header h6 {
      margin: 0;
      font-size: .9rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: rgba(249, 250, 251, .92);
      font-weight: 900;
    }

    .badge-soft {
      padding: .16rem .5rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.45);
      font-size: .7rem;
      color: var(--text-muted);
      background: rgba(15, 23, 42, .65);
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
    }

    .list-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: .7rem .2rem;
      border-bottom: 1px dashed rgba(51, 65, 85, 0.8);
      font-size: .82rem;
    }

    .list-item:last-child {
      border-bottom: none;
    }

    .list-item-main {
      display: flex;
      flex-direction: column;
      gap: .05rem;
    }

    .list-item-title {
      font-weight: 800;
    }

    .list-item-sub {
      color: var(--text-muted);
      font-size: .75rem;
    }

    .list-item-kpi {
      text-align: right;
      font-size: .78rem;
      font-weight: 800;
    }

    .highlight-card {
      padding: 1.3rem 1.4rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .product-chip {
      display: flex;
      align-items: center;
      gap: .8rem;
    }

    .product-thumb {
      width: 52px;
      height: 52px;
      border-radius: 1.2rem;
      background: radial-gradient(circle at top, rgba(56, 189, 248, 0.45), rgba(15, 23, 42, 1));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.6rem;
      box-shadow: 0 12px 28px rgba(15, 23, 42, .85);
    }

    footer {
      text-align: center;
      padding-top: 1.6rem;
      font-size: .78rem;
      color: var(--text-muted);
    }

    /* ANIM */
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

    @keyframes chartPulse {
      from {
        transform: translateY(10px) scale(1);
        opacity: .9;
      }

      to {
        transform: translateY(-4px) scale(1.05);
        opacity: 1;
      }
    }

    @keyframes floatDot {
      from {
        transform: scaleY(.8) translateY(4px);
      }

      to {
        transform: scaleY(1) translateY(-4px);
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

    .delay-4 {
      animation-delay: .3s;
    }

    /* Responsive */
    @media (max-width: 1100px) {
      .dashboard-grid {
        grid-template-columns: 1fr;
      }

      .bottom-row {
        grid-template-columns: 1fr;
      }

      .hero-title-block {
        max-width: 100%;
      }

      .hero-image-wrapper {
        display: none;
      }

      .hero-stats-row {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
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

  <!-- Sidebar (igual Config/Usuarios) -->
  <aside class="sidebar">
    <h4>AutomAI</h4>

    <a href="Dashboard.php" class="active">🏠 Dashboard</a>
    <a href="Chatbot-Configuracion.php">🤖 Configurar Chatbot</a>
    <a href="Integraciones-Canales.php">🔌 Integraciones</a>
    <a href="Reportes-Dashboard.php">📊 Reportes</a>
    <a href="Usuarios-Gestion.php">👥 Usuarios</a>
    <a href="Configuracion-General.php">⚙️ Configuración</a>
    <a href="Soporte-Ayuda.php">💬 Soporte</a>

    <hr>
    <a href="Logout.php" class="text-danger">🚪 Cerrar sesión</a>
  </aside>

  <!-- Navbar superior -->
  <header class="navbar-top">
    <div class="navbar-title">
      <span><?= htmlspecialchars($usuario["empresa"], ENT_QUOTES, "UTF-8"); ?></span>
      <h5>Panel de Control</h5>
    </div>

    <div class="user-info">
      <div class="user-pill">
        <span><?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8"); ?></span>
        <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Avatar usuario" class="user-avatar">
      </div>
    </div>
  </header>

  <!-- Contenido principal -->
  <main class="main-content">

    <div class="dashboard-grid">

      <!-- HERO PRINCIPAL -->
      <section class="section-card hero-card fade-in">
        <div class="hero-header">
          <div class="hero-title-block">
            <span class="badge-kpi">● Live Analytics · Chatbots</span>

            <h1>Optimiza tus métricas de atención</h1>
            <p>
              Visualiza en tiempo real el rendimiento de tus chatbots, la satisfacción
              de tus clientes y la automatización por canal.
            </p>

            <div class="hero-cta-row">
              <a class="btn-primary-glow" href="Chatbot-Configuracion.php">⚡ Mejorar flujo ahora</a>
              <small>+23% de reducción media en tiempo de respuesta</small>
            </div>
          </div>

          <div class="hero-image-wrapper">
            <img src="https://images.pexels.com/photos/1181686/pexels-photo-1181686.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Analista de datos">
            <div class="hero-glow-orbit"></div>
          </div>
        </div>

        <div class="hero-stats-row">
          <?php foreach ($kpis as $k): ?>
            <div class="hero-stat">
              <div class="hero-stat-label"><?= htmlspecialchars($k["label"], ENT_QUOTES, "UTF-8"); ?></div>
              <div class="hero-stat-value"><?= htmlspecialchars($k["value"], ENT_QUOTES, "UTF-8"); ?></div>
              <div class="hero-stat-tag"><?= htmlspecialchars($k["tag"], ENT_QUOTES, "UTF-8"); ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- ANALYTICS LADO DERECHO -->
      <section class="section-card card-analytics fade-in delay-1">
        <div class="card-analytics-header">
          <div>
            <div style="font-size:.78rem; text-transform:uppercase; letter-spacing:.12em; color:var(--text-muted);">
              Actividad global
            </div>
            <strong>Usuarios activos por canal</strong>
          </div>
          <span class="pill-muted">Últimos 7 días</span>
        </div>

        <div class="chart-placeholder">
          <div class="chart-line"></div>
          <div class="chart-curve"></div>
          <div class="chart-dots">
            <div class="chart-dot"></div>
            <div class="chart-dot"></div>
            <div class="chart-dot"></div>
            <div class="chart-dot"></div>
          </div>
        </div>

        <div class="mini-metrics">
          <?php foreach ($canales as $c): ?>
            <div class="mini-metric">
              <span class="label"><?= htmlspecialchars($c["label"], ENT_QUOTES, "UTF-8"); ?></span>
              <span class="value"><?= htmlspecialchars($c["value"], ENT_QUOTES, "UTF-8"); ?></span>
              <span class="trend" style="color: <?= htmlspecialchars($c["trend_color"], ENT_QUOTES, "UTF-8"); ?>">
                <?= htmlspecialchars($c["trend"], ENT_QUOTES, "UTF-8"); ?>
              </span>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

    </div>

    <!-- FILA INFERIOR -->
    <div class="bottom-row">

      <section class="section-card list-card fade-in delay-2">
        <div class="list-header">
          <h6>Top chatbots este periodo</h6>
          <span class="badge-soft">Ordenado por volumen</span>
        </div>

        <?php foreach ($topChatbots as $bot): ?>
          <div class="list-item">
            <div class="list-item-main">
              <span class="list-item-title"><?= htmlspecialchars($bot["titulo"], ENT_QUOTES, "UTF-8"); ?></span>
              <span class="list-item-sub"><?= htmlspecialchars($bot["sub"], ENT_QUOTES, "UTF-8"); ?></span>
            </div>

            <div class="list-item-kpi">
              <div><?= htmlspecialchars($bot["kpi1"], ENT_QUOTES, "UTF-8"); ?></div>
              <div style="color: <?= htmlspecialchars($bot["kpi2_color"], ENT_QUOTES, "UTF-8"); ?>">
                <?= htmlspecialchars($bot["kpi2"], ENT_QUOTES, "UTF-8"); ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </section>

      <section class="section-card highlight-card fade-in delay-3">
        <div class="list-header">
          <h6>Instant insights</h6>
          <span class="badge-soft">Resumen rápido</span>
        </div>

        <div class="product-chip">
          <div class="product-thumb">💬</div>
          <div>
            <div style="font-weight:800;">Satisfacción del cliente</div>
            <div style="font-size:.8rem; color:var(--text-muted);">
              94% de valoraciones positivas en las últimas 500 interacciones.
            </div>
          </div>
        </div>

        <div class="mini-metrics">
          <div class="mini-metric">
            <span class="label">Tiempo medio respuesta</span>
            <span class="value">2.1s</span>
            <span class="trend" style="color:#bbf7d0;">Optimizado</span>
          </div>
          <div class="mini-metric">
            <span class="label">Ahorro estimado</span>
            <span class="value">€ 586</span>
            <span class="trend" style="color:#fee2e2;">Mensual en soporte</span>
          </div>
        </div>
      </section>

    </div>

    <footer>
      © 2026 AutomAI Solutions · Dashboard de Analítica de Canales
      · Plan: <?= htmlspecialchars($usuario["plan"], ENT_QUOTES, "UTF-8"); ?>
    </footer>

  </main>

</body>

</html>