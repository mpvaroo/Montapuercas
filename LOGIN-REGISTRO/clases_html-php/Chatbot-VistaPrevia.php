<?php

declare(strict_types=1);

/**
 * Chatbot-VistaPrevia.php (DEMO VISUAL + FUNCIONAL)
 * - NO login real
 * - NO roles reales
 * - Chat + subida DOCX + Ollama local
 */

set_time_limit(90);

// Si quieres “cero sesiones”, quita esto y el uso de $_SESSION.
// Pero para demo de chat persistente, la sesión es lo más simple y no “es login”.
session_start();

$mensaje_usuario = null;
$respuesta_bot   = null;
$contexto_doc    = $_SESSION['contexto_doc'] ?? null;
$historial       = $_SESSION['historial'] ?? []; // conversación completa

// ----------------------------
// MOCK USER (solo visual, como tu Dashboard)
// ----------------------------
$usuario = [
  "email"   => "admin@empresa.com",
  "empresa" => "AutomAI Demo",
  "plan"    => "Premium"
];

// ----------------------------
// Helper: marcar link activo según nombre del archivo actual
// ----------------------------
function isActive(string $fileName): bool
{
  $actual = basename($_SERVER['PHP_SELF'] ?? '');
  return strcasecmp($actual, $fileName) === 0;
}

// ----------------------------
// FUNCIÓN: extraer texto de DOCX
// ----------------------------
function extraerTextoDocx(string $ruta): ?string
{
  $zip = new ZipArchive();
  if ($zip->open($ruta) === true) {
    $xml = $zip->getFromName('word/document.xml');
    $zip->close();

    if ($xml) {
      $xml = str_replace('</w:p>', "\n", $xml);
      $xml = strip_tags($xml);
      $texto = trim($xml);
      return $texto !== "" ? $texto : null;
    }
  }
  return null;
}

// ----------------------------
// PROCESAR DOCX SUBIDO
// ----------------------------
if (!empty($_FILES['documento']) && ($_FILES['documento']['error'] ?? null) === UPLOAD_ERR_OK) {

  $rutaTmp  = $_FILES['documento']['tmp_name'];
  $nombre   = $_FILES['documento']['name'] ?? 'documento.docx';
  $ext      = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

  if ($ext === 'docx') {
    $textoDoc = extraerTextoDocx($rutaTmp);
    if ($textoDoc) {
      $_SESSION['contexto_doc'] = $textoDoc;
      $contexto_doc = $textoDoc;
    }
  }
}

// ----------------------------
// PROCESAR MENSAJE DEL CHAT
// ----------------------------
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !empty($_POST['mensaje'])) {
  $mensaje_usuario = (string)$_POST['mensaje'];

  // Historial a texto plano para el prompt
  $historialTexto = "";
  if (!empty($historial)) {
    foreach ($historial as $msg) {
      if (($msg['role'] ?? '') === 'user') {
        $historialTexto .= "Usuario: " . ($msg['mensaje'] ?? '') . "\n";
      } elseif (($msg['role'] ?? '') === 'bot') {
        $historialTexto .= "Asistente: " . ($msg['mensaje'] ?? '') . "\n";
      }
    }
  }

  // Prompt según si hay documento
  if (!empty($contexto_doc)) {
    $prompt = "Eres un asistente que responde SOLO basándote en el siguiente documento.\n\n" .
      "DOCUMENTO:\n" . $contexto_doc .
      "\n\nCONVERSACIÓN HASTA AHORA:\n" . $historialTexto .
      "Usuario: " . $mensaje_usuario . "\n" .
      "Asistente:";
  } else {
    $prompt = "Eres un asistente útil. Responde de forma clara y directa.\n\n" .
      "CONVERSACIÓN HASTA AHORA:\n" . $historialTexto .
      "Usuario: " . $mensaje_usuario . "\n" .
      "Asistente:";
  }

  // Payload Ollama
  $payload = [
    "model"  => "llama3",
    "prompt" => $prompt,
    "stream" => false
  ];

  // Llamada a Ollama local
  $ch = curl_init("http://localhost:11434/api/generate");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

  $raw = curl_exec($ch);
  curl_close($ch);

  if ($raw !== false) {
    $data = json_decode($raw, true);
    $respuesta_bot = isset($data['response']) ? (string)$data['response'] : "Lo siento, no he podido procesar la respuesta de la IA.";
  } else {
    $respuesta_bot = "Error al conectar con el modelo local (Ollama).";
  }

  // Guardar en historial
  if ($mensaje_usuario !== "") {
    $historial[] = ['role' => 'user', 'mensaje' => $mensaje_usuario];
  }
  if (!empty($respuesta_bot)) {
    $historial[] = ['role' => 'bot', 'mensaje' => $respuesta_bot];
  }

  $_SESSION['historial'] = $historial;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vista previa | AutomAI Solutions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root {
      --bg-main: #020617;
      --bg-elevated: rgba(15, 23, 42, .96);
      --bg-soft: rgba(15, 23, 42, .88);
      --accent: #38bdf8;
      --accent-strong: #0ea5e9;
      --text-main: #f9fafb;
      --text-muted: #9ca3af;
      --border-subtle: rgba(148, 163, 184, .4);
      --radius-lg: 1.5rem;
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
      font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
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

    /* SIDEBAR (igual que tu Dashboard) */
    .sidebar {
      width: 80px;
      height: 100vh;
      position: fixed;
      inset-block: 0;
      left: 0;
      padding: 1.4rem .9rem;
      background: linear-gradient(145deg, rgba(15, 23, 42, .98), rgba(15, 23, 42, .92));
      border-right: 1px solid rgba(15, 23, 42, 1);
      box-shadow: 0 0 0 1px rgba(15, 23, 42, 1), 0 18px 45px rgba(15, 23, 42, .95);
      backdrop-filter: blur(18px);
      z-index: 40;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1.5rem;
    }

    .sidebar-logo {
      width: 44px;
      height: 44px;
      border-radius: 999px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: radial-gradient(circle at 20% 0, #38bdf8, #0f172a);
      box-shadow: 0 0 0 1px rgba(148, 163, 184, .5), 0 0 22px rgba(56, 189, 248, .8);
      font-size: 1.2rem;
    }

    .sidebar-nav {
      display: flex;
      flex-direction: column;
      gap: .6rem;
      margin-top: .5rem;
    }

    .sidebar a {
      width: 46px;
      height: 46px;
      border-radius: 999px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      text-decoration: none;
      font-size: 1.2rem;
      position: relative;
      background: rgba(15, 23, 42, .6);
      border: 1px solid rgba(30, 64, 175, .45);
      transition: background .22s ease, transform .18s ease, box-shadow .22s ease, color .22s ease;
    }

    .sidebar a span.tooltip {
      position: absolute;
      left: 54px;
      padding: .25rem .6rem;
      border-radius: 999px;
      background: rgba(15, 23, 42, .98);
      border: 1px solid rgba(148, 163, 184, .7);
      color: #e5f0ff;
      font-size: .7rem;
      letter-spacing: .06em;
      text-transform: uppercase;
      white-space: nowrap;
      opacity: 0;
      transform: translateX(-4px);
      pointer-events: none;
      transition: opacity .16s ease, transform .16s ease;
    }

    .sidebar a:hover span.tooltip {
      opacity: 1;
      transform: translateX(0);
    }

    .sidebar a:hover {
      background: radial-gradient(circle at 20% 0, rgba(56, 189, 248, .35), rgba(15, 23, 42, .95));
      color: #f9fafb;
      transform: translateX(2px) translateY(-1px);
      box-shadow: 0 10px 28px rgba(15, 23, 42, .9), 0 0 22px rgba(56, 189, 248, .65);
    }

    .sidebar a.active {
      background: linear-gradient(145deg, #38bdf8, #2563eb);
      color: #f9fafb;
      box-shadow: 0 14px 32px rgba(37, 99, 235, .9), 0 0 28px rgba(56, 189, 248, 1);
    }

    .sidebar-footer {
      margin-top: auto;
    }

    .sidebar a.logout {
      border-color: rgba(248, 113, 113, .45);
      background: rgba(127, 29, 29, .25);
      color: #fecaca;
    }

    .sidebar a.logout:hover {
      background: rgba(190, 24, 24, .75);
      box-shadow: 0 12px 30px rgba(127, 29, 29, .9), 0 0 20px rgba(248, 113, 113, .85);
    }

    /* NAVBAR (igual que tu Dashboard) */
    .navbar-top {
      height: 72px;
      padding: 0 2.4rem;
      margin-left: 80px;
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
      color: var(--text-muted);
    }

    .navbar-title h5 {
      margin: 0;
      font-size: 1.15rem;
      font-weight: 600;
      letter-spacing: .04em;
      text-transform: uppercase;
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
      gap: .5rem;
      padding: .3rem .85rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .65);
      background: rgba(15, 23, 42, .8);
    }

    .user-avatar {
      width: 34px;
      height: 34px;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .6);
      background: radial-gradient(circle at 30% 0, #38bdf8, #0f172a);
      padding: 2px;
      box-shadow: 0 0 12px rgba(56, 189, 248, .6);
    }

    /* MAIN */
    .main-content {
      margin-left: 80px;
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

    /* Layout 2 columnas (izq opciones, der chat) */
    .preview-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1.6fr);
      gap: 1.5rem;
      align-items: stretch;
    }

    @media (max-width: 1100px) {
      .preview-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Opciones */
    .card-pad {
      padding: 1.35rem 1.5rem;
    }

    .card-header-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 1rem;
    }

    .card-header-row h6 {
      margin: 0;
      font-size: .9rem;
      letter-spacing: .08em;
      text-transform: uppercase;
    }

    .card-header-row p {
      margin: .35rem 0 0;
      color: var(--text-muted);
      font-size: .85rem;
      max-width: 52ch;
    }

    .badge-soft {
      padding: .16rem .5rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .6);
      font-size: .7rem;
      color: var(--text-muted);
      white-space: nowrap;
    }

    .form-label {
      font-size: .75rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--text-muted);
    }

    .form-control,
    .form-select {
      border-radius: 1rem;
      border: 1px solid rgba(30, 64, 175, .55);
      background: rgba(15, 23, 42, .75);
      color: var(--text-main);
    }

    .form-control:focus,
    .form-select:focus {
      box-shadow: 0 0 0 .2rem rgba(56, 189, 248, .15);
      border-color: rgba(56, 189, 248, .8);
      background: rgba(15, 23, 42, .85);
      color: var(--text-main);
    }

    .btn-primary-glow {
      border-radius: 999px;
      border: none;
      padding: .55rem 1.2rem;
      font-size: .9rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .4rem;
      color: #f9fafb;
      background: radial-gradient(circle at 10% 0, #38bdf8, #2563eb);
      box-shadow: 0 10px 25px rgba(59, 130, 246, .7), 0 0 26px rgba(56, 189, 248, .95);
      transition: transform .18s ease, box-shadow .22s ease, background .18s ease;
      text-decoration: none;
      width: 100%;
    }

    .btn-primary-glow:hover {
      transform: translateY(-1px);
      background: radial-gradient(circle at 10% 0, #0ea5e9, #1d4ed8);
      box-shadow: 0 16px 35px rgba(37, 99, 235, .9), 0 0 32px rgba(56, 189, 248, 1);
    }

    .quick-replies {
      display: flex;
      flex-wrap: wrap;
      gap: .5rem;
    }

    .qr-btn {
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .65);
      background: rgba(15, 23, 42, .7);
      color: var(--text-muted);
      padding: .35rem .75rem;
      font-size: .78rem;
      transition: .18s;
    }

    .qr-btn:hover {
      border-color: rgba(56, 189, 248, .9);
      color: #e5f0ff;
      background: rgba(15, 23, 42, .9);
      transform: translateY(-1px);
    }

    /* CHAT */
    .chat-shell {
      display: flex;
      flex-direction: column;
      height: 72vh;
    }

    @media (max-width: 1100px) {
      .chat-shell {
        height: 70vh;
      }
    }

    .chat-header {
      padding: 1rem 1.2rem;
      border-bottom: 1px solid rgba(30, 64, 175, .6);
      background:
        radial-gradient(circle at top left, rgba(56, 189, 248, .22), transparent 55%),
        rgba(15, 23, 42, .85);
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
    }

    .chat-title {
      display: flex;
      align-items: center;
      gap: .75rem;
    }

    .bot-avatar {
      width: 38px;
      height: 38px;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .6);
      background: radial-gradient(circle at 30% 0, #38bdf8, #0f172a);
      box-shadow: 0 0 14px rgba(56, 189, 248, .7);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
    }

    .chat-title strong {
      letter-spacing: .04em;
      text-transform: uppercase;
    }

    .chat-title small {
      display: block;
      color: var(--text-muted);
      font-size: .78rem;
    }

    .pill-muted {
      padding: .18rem .7rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .6);
      color: var(--text-muted);
      font-size: .72rem;
      background: rgba(15, 23, 42, .75);
      white-space: nowrap;
    }

    .chat-body {
      flex: 1;
      padding: 1rem 1.2rem;
      overflow-y: auto;
      background: rgba(2, 6, 23, .25);
    }

    .chat-row {
      display: flex;
      gap: .6rem;
      align-items: flex-end;
      margin-bottom: .8rem;
    }

    .chat-row.user {
      justify-content: flex-end;
    }

    .bubble {
      max-width: 76%;
      padding: .75rem .95rem;
      border-radius: 1.1rem;
      font-size: .9rem;
      line-height: 1.4;
      border: 1px solid rgba(148, 163, 184, .45);
      box-shadow: 0 10px 26px rgba(15, 23, 42, .75);
      background: rgba(15, 23, 42, .88);
    }

    .bubble.user {
      border: none;
      background: radial-gradient(circle at 10% 0, #38bdf8, #2563eb);
      color: #eaf6ff;
      box-shadow: 0 14px 34px rgba(37, 99, 235, .85);
    }

    .meta {
      font-size: .72rem;
      color: var(--text-muted);
      margin-top: .25rem;
    }

    .meta.end {
      text-align: right;
    }

    .chat-footer {
      padding: .95rem 1.2rem 1.1rem;
      border-top: 1px solid rgba(30, 64, 175, .6);
      background: rgba(15, 23, 42, .9);
    }

    .input-row {
      display: flex;
      gap: .6rem;
      align-items: stretch;
      flex-wrap: wrap;
    }

    .input-row .msg-input {
      flex: 1 1 260px;
    }

    .actions {
      display: flex;
      gap: .55rem;
      align-items: center;
      flex-wrap: nowrap;
    }

    .file-btn {
      position: relative;
      overflow: hidden;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .65);
      background: rgba(15, 23, 42, .75);
      color: var(--text-muted);
      padding: .55rem .95rem;
      font-size: .82rem;
      cursor: pointer;
      transition: .18s;
      display: inline-flex;
      align-items: center;
      gap: .4rem;
    }

    .file-btn:hover {
      border-color: rgba(56, 189, 248, .9);
      color: #e5f0ff;
      background: rgba(15, 23, 42, .9);
      transform: translateY(-1px);
    }

    .file-btn input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
    }

    .send-btn {
      border-radius: 999px;
      border: none;
      padding: .55rem 1rem;
      font-weight: 600;
      color: #f9fafb;
      background: radial-gradient(circle at 10% 0, #38bdf8, #2563eb);
      box-shadow: 0 10px 25px rgba(59, 130, 246, .65), 0 0 22px rgba(56, 189, 248, .85);
      transition: .18s;
    }

    .send-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 16px 35px rgba(37, 99, 235, .85), 0 0 28px rgba(56, 189, 248, 1);
    }

    .file-chosen {
      margin-top: .55rem;
      font-size: .78rem;
      color: var(--text-muted);
      min-height: 1rem;
    }

    .helper {
      margin-top: .45rem;
      font-size: .78rem;
      color: var(--text-muted);
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo">A</div>

    <nav class="sidebar-nav">
      <a href="Dashboard.php" class="<?= isActive('Dashboard.php') ? 'active' : '' ?>">🏠 <span class="tooltip">Dashboard</span></a>
      <a href="Chatbot-Configuracion.php" class="<?= isActive('Chatbot-Configuracion.php') ? 'active' : '' ?>">🤖 <span class="tooltip">Chatbots</span></a>
      <a href="Integraciones-Canales.php" class="<?= isActive('Integraciones-Canales.php') ? 'active' : '' ?>">🔌 <span class="tooltip">Integraciones</span></a>
      <a href="Reportes-Dashboard.php" class="<?= isActive('Reportes-Dashboard.php') ? 'active' : '' ?>">📊 <span class="tooltip">Reportes</span></a>
      <a href="Usuarios-Gestion.php" class="<?= isActive('Usuarios-Gestion.php') ? 'active' : '' ?>">👥 <span class="tooltip">Usuarios</span></a>
      <a href="Configuracion-General.php" class="<?= isActive('Configuracion-General.php') ? 'active' : '' ?>">⚙️ <span class="tooltip">Ajustes</span></a>
      <a href="Soporte-Ayuda.php" class="<?= isActive('Soporte-Ayuda.php') ? 'active' : '' ?>">💬 <span class="tooltip">Soporte</span></a>
    </nav>

    <div class="sidebar-footer">
      <a href="Logout.php" class="logout">🚪 <span class="tooltip">Cerrar sesión</span></a>
    </div>
  </aside>

  <!-- Navbar superior -->
  <header class="navbar-top">
    <div class="navbar-title">
      <span><?= htmlspecialchars($usuario["empresa"], ENT_QUOTES, "UTF-8"); ?></span>
      <h5>Vista previa del chatbot</h5>
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

    <div class="preview-grid">

      <!-- Opciones -->
      <section class="section-card card-pad">
        <div class="card-header-row">
          <div>
            <h6>Opciones de simulación</h6>
            <p>Ajusta cómo se comporta tu bot antes de publicarlo (solo visual por ahora).</p>
          </div>
          <span class="badge-soft">Preview</span>
        </div>

        <div class="mt-3">
          <label class="form-label">Canal</label>
          <select class="form-select">
            <option selected>WhatsApp</option>
            <option>Telegram</option>
            <option>Web chat</option>
            <option>Email (Gmail)</option>
          </select>
        </div>

        <div class="mt-3">
          <label class="form-label">Personalidad del bot</label>
          <select class="form-select">
            <option selected>Profesional</option>
            <option>Cálido y cercano</option>
            <option>Formal</option>
            <option>Directo</option>
          </select>
        </div>

        <div class="mt-3">
          <label class="form-label">Contexto de negocio</label>
          <textarea class="form-control" rows="4" placeholder="Ej: Peluquería AutomAI · Horario, precios, ubicación, teléfono..."></textarea>
          <div class="helper">Este texto luego se guardará en BD (preview_sesion.contexto_negocio).</div>
        </div>

        <div class="mt-3">
          <a class="btn-primary-glow" href="#" onclick="return false;">⚡ Aplicar cambios (demo)</a>
        </div>

        <hr style="border-color: rgba(51,65,85,.8); margin:1.2rem 0;">

        <div class="d-flex align-items-center justify-content-between mb-2">
          <h6 style="margin:0; font-size:.85rem; letter-spacing:.08em; text-transform:uppercase;">Respuestas rápidas</h6>
          <span class="badge-soft">UX testing</span>
        </div>

        <div class="quick-replies">
          <button class="qr-btn" type="button">Horario de atención</button>
          <button class="qr-btn" type="button">Reservar cita</button>
          <button class="qr-btn" type="button">Precios</button>
          <button class="qr-btn" type="button">Soporte técnico</button>
          <button class="qr-btn" type="button">Hablar con agente</button>
        </div>

        <div class="helper mt-3">
          Si subes un <strong>DOCX</strong>, el bot responderá solo en base al documento (demo local con Ollama).
        </div>
      </section>

      <!-- Chat -->
      <section class="section-card chat-shell">
        <div class="chat-header">
          <div class="chat-title">
            <div class="bot-avatar">🤖</div>
            <div>
              <strong>AutomAI Bot</strong>
              <small>Estado: Activo · Modelo local <span style="color: var(--accent); font-weight:600;">llama3</span></small>
            </div>
          </div>
          <span class="pill-muted">Modo simulación</span>
        </div>

        <div class="chat-body">
          <?php if (empty($historial)): ?>
            <div class="chat-row">
              <div class="bot-avatar" style="width:34px;height:34px; font-size:1rem;">🤖</div>
              <div>
                <div class="bubble">
                  ¡Hola! Soy <strong>AutomAI</strong>. Escríbeme o sube un <strong>DOCX</strong> para que responda solo usando ese documento.
                </div>
                <div class="meta">Bot · ahora</div>
              </div>
            </div>
          <?php else: ?>
            <?php foreach ($historial as $msg): ?>
              <?php if (($msg['role'] ?? '') === 'user'): ?>
                <div class="chat-row user">
                  <div>
                    <div class="bubble user"><?= htmlspecialchars((string)$msg['mensaje'], ENT_QUOTES, "UTF-8"); ?></div>
                    <div class="meta end">Tú</div>
                  </div>
                </div>
              <?php elseif (($msg['role'] ?? '') === 'bot'): ?>
                <div class="chat-row">
                  <div class="bot-avatar" style="width:34px;height:34px; font-size:1rem;">🤖</div>
                  <div>
                    <div class="bubble"><?= nl2br(htmlspecialchars((string)$msg['mensaje'], ENT_QUOTES, "UTF-8")); ?></div>
                    <div class="meta">Bot</div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="chat-footer">
          <form method="post" enctype="multipart/form-data">
            <div class="input-row">
              <input type="text" name="mensaje" class="form-control msg-input" placeholder="Escribe tu mensaje..." required>

              <div class="actions">
                <label class="file-btn mb-0">
                  📄 DOCX
                  <input type="file" name="documento" id="documentoInput" accept=".docx">
                </label>

                <button type="submit" name="enviar" class="send-btn">Enviar</button>
              </div>
            </div>
          </form>

          <div id="fileName" class="file-chosen"></div>
          <div class="helper">Sube un DOCX y pregunta: “¿Cuál es el horario?” / “¿Cuánto cuesta X?”</div>
        </div>
      </section>

    </div>
  </main>

  <script>
    const inputDoc = document.getElementById('documentoInput');
    const fileNameSpan = document.getElementById('fileName');

    if (inputDoc && fileNameSpan) {
      inputDoc.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
          fileNameSpan.textContent = "Archivo seleccionado: " + this.files[0].name;
        } else {
          fileNameSpan.textContent = "";
        }
      });
    }
  </script>

</body>

</html>