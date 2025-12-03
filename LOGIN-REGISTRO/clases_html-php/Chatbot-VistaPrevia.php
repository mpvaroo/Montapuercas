<?php
set_time_limit(90);
session_start();

// Mensaje del usuario, respuesta del bot, contexto del documento e historial de chat
$mensaje_usuario = null;
$respuesta_bot   = null;
$contexto_doc    = $_SESSION['contexto_doc'] ?? null;
$historial       = $_SESSION['historial'] ?? []; // ← aquí guardamos toda la conversación

// ----------- FUNCIÓN PARA LEER DOCX -----------
function extraerTextoDocx($ruta)
{
    $zip = new ZipArchive();
    if ($zip->open($ruta) === true) {
        // El contenido principal de Word está en word/document.xml
        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml) {
            // Sustituimos fin de párrafo por saltos de línea
            $xml = str_replace('</w:p>', "\n", $xml);
            // Quitamos etiquetas XML
            $xml = strip_tags($xml);
            return trim($xml);
        }
    }
    return null;
}

// ----------- PROCESAR DOCUMENTO SUBIDO -----------
if (!empty($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {

    $rutaTmp  = $_FILES['documento']['tmp_name'];
    $nombre   = $_FILES['documento']['name'];
    $ext      = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

    // Solo aceptamos DOCX en este ejemplo
    if ($ext === 'docx') {
        $textoDoc = extraerTextoDocx($rutaTmp);
        if ($textoDoc) {
            // Guardamos el texto del doc en sesión para usarlo en varias preguntas
            $_SESSION['contexto_doc'] = $textoDoc;
            $contexto_doc             = $textoDoc;
        }
    }
}

// ----------- PROCESAR MENSAJE DEL CHAT -----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje'])) {
    $mensaje_usuario = $_POST['mensaje'];

    // Construimos un texto con el historial de la conversación
    $historialTexto = "";
    if (!empty($historial)) {
        foreach ($historial as $msg) {
            if ($msg['role'] === 'user') {
                $historialTexto .= "Usuario: " . $msg['mensaje'] . "\n";
            } elseif ($msg['role'] === 'bot') {
                $historialTexto .= "Asistente: " . $msg['mensaje'] . "\n";
            }
        }
    }

    // Prompt según si hay documento o no
    if (!empty($contexto_doc)) {
        $prompt = "Eres un asistente que responde SOLO basándote en el siguiente documento.\n\n" .
            "DOCUMENTO:\n" .
            $contexto_doc .
            "\n\nCONVERSACIÓN HASTA AHORA:\n" .
            $historialTexto .
            "Usuario: " . $mensaje_usuario . "\n" .
            "Asistente:";
    } else {
        $prompt = "Eres un asistente útil. Responde de forma clara y directa.\n\n" .
            "CONVERSACIÓN HASTA AHORA:\n" .
            $historialTexto .
            "Usuario: " . $mensaje_usuario . "\n" .
            "Asistente:";
    }

    // Construimos el payload para OLLAMA
    $payload = [
        "model"  => "llama3",   // o "llama3:8b" o el modelo que tengas
        "prompt" => $prompt,
        "stream" => false
    ];

    // Llamada a la API local de OLLAMA
    $ch = curl_init("http://localhost:11434/api/generate");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    $raw = curl_exec($ch);
    curl_close($ch);

    if ($raw !== false) {
        $data = json_decode($raw, true);
        if (isset($data['response'])) {
            $respuesta_bot = $data['response'];
        } else {
            $respuesta_bot = "Lo siento, no he podido procesar la respuesta de la IA.";
        }
    } else {
        $respuesta_bot = "Error al conectar con el modelo local (Ollama).";
    }

    // Guardamos en historial la nueva interacción
    if ($mensaje_usuario) {
        $historial[] = [
            'role'    => 'user',
            'mensaje' => $mensaje_usuario
        ];
    }

    if ($respuesta_bot) {
        $historial[] = [
            'role'    => 'bot',
            'mensaje' => $respuesta_bot
        ];
    }

    // Actualizamos la sesión con el historial completo
    $_SESSION['historial'] = $historial;
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vista previa | AutomAI Solutions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Fuente moderna -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg-main: #020617;
      --bg-elevated: rgba(15, 23, 42, 0.85);
      --accent: #38bdf8;
      --accent-strong: #0ea5e9;
      --text-main: #e5e7eb;
      --text-muted: #9ca3af;
      --border-subtle: rgba(148, 163, 184, 0.25);
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
      color: var(--text-main);
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      overflow-x: hidden;
    }

    body::before {
      content: "";
      position: fixed;
      inset: 0;
      backdrop-filter: blur(24px);
      pointer-events: none;
      z-index: -1;
    }

    .app-shell {
      margin-left: 240px;
    }

    @media (max-width: 992px) {
      .app-shell {
        margin-left: 0;
      }
    }

    /* SIDEBAR – mismos enlaces que tu versión original */
    .sidebar {
      width: 240px;
      height: 100vh;
      position: fixed;
      inset-block: 0;
      left: 0;
      padding: 1.75rem 1.25rem;
      background: linear-gradient(145deg, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.85));
      border-right: 1px solid var(--border-subtle);
      box-shadow:
        0 0 0 1px rgba(15, 23, 42, 0.9),
        0 18px 45px rgba(15, 23, 42, 0.9);
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
      color: #e5f7ff;
      transform: translateX(1px);
    }

    .sidebar a.active {
      background: linear-gradient(135deg, rgba(56, 189, 248, 0.22), rgba(15, 23, 42, 0.96));
      color: #e5f7ff;
      box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.4);
    }

    .sidebar a.text-danger {
      color: #fecaca;
      background: rgba(127, 29, 29, 0.15);
      border: 1px solid rgba(248, 113, 113, 0.35);
      margin-top: .75rem;
    }

    .sidebar a.text-danger:hover {
      background: rgba(153, 27, 27, 0.45);
    }

    .sidebar hr {
      border-color: rgba(51, 65, 85, 0.8);
      margin: 1.1rem 0;
    }

    /* NAVBAR SUPERIOR */
    .navbar-top {
      height: 64px;
      padding: 0 1.75rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(148, 163, 184, 0.25);
      background: linear-gradient(90deg,
          rgba(15, 23, 42, 0.93),
          rgba(15, 23, 42, 0.85),
          rgba(15, 23, 42, 0.93));
      backdrop-filter: blur(18px);
      position: sticky;
      top: 0;
      z-index: 40;
      margin-left: 240px;
    }

    @media (max-width: 992px) {
      .navbar-top {
        margin-left: 0;
      }
    }

    .navbar-title {
      display: flex;
      flex-direction: column;
      gap: .15rem;
    }

    .navbar-title h5 {
      margin: 0;
      font-size: 1.05rem;
      font-weight: 600;
      letter-spacing: .03em;
      text-transform: uppercase;
      color: #e5e7eb;
    }

    .navbar-title span {
      font-size: .78rem;
      color: var(--text-muted);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: .75rem;
      font-size: .85rem;
      color: var(--text-muted);
    }

    .user-pill {
      padding: .2rem .75rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.4);
      background: rgba(15, 23, 42, 0.75);
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.5);
      padding: 2px;
      background: radial-gradient(circle at 30% 0, #38bdf8, #0f172a);
      box-shadow: 0 0 12px rgba(56, 189, 248, .55);
    }

    /* MAIN */
    .main-content {
      padding: 1.75rem 1.75rem 2.5rem;
      margin-left: 240px;
    }

    @media (max-width: 992px) {
      .main-content {
        margin-left: 0;
      }
    }

    .section-card {
      background: var(--bg-elevated);
      border-radius: 1.2rem;
      border: 1px solid var(--border-subtle);
      box-shadow:
        0 18px 45px rgba(15, 23, 42, 0.85),
        0 0 60px rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(18px);
      color: var(--text-main);
    }

    .section-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: .75rem;
    }

    .badge-pill {
      border-radius: 999px;
      padding: .25rem .7rem;
      font-size: .7rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      border: 1px solid rgba(148, 163, 184, 0.4);
      color: var(--text-muted);
    }

    .pill-primary {
      background: rgba(56, 189, 248, 0.18);
      border-color: rgba(56, 189, 248, 0.5);
      color: #e0f2fe;
    }

    .form-label {
      font-size: .8rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: .3rem;
    }

    .form-select,
    .form-control {
      font-size: .9rem;
      border-radius: .8rem;
      border: 1px solid rgba(148, 163, 184, 0.5);
      background: rgba(15, 23, 42, 0.8);
      color: var(--text-main);
    }

    .form-select:focus,
    .form-control:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.35);
      background: rgba(15, 23, 42, 0.9);
      color: var(--text-main);
    }

    .form-text {
      font-size: .78rem;
      color: var(--text-muted);
    }

    /* Botones */
    .btn-primary {
      background: linear-gradient(135deg, var(--accent-strong), #4f46e5);
      border: none;
      border-radius: .9rem;
      font-size: .9rem;
      font-weight: 600;
      padding: .55rem 1rem;
      box-shadow:
        0 10px 25px rgba(59, 130, 246, 0.4),
        0 0 18px rgba(56, 189, 248, 0.5);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #0ea5e9, #4338ca);
      box-shadow:
        0 14px 32px rgba(37, 99, 235, 0.55),
        0 0 22px rgba(56, 189, 248, 0.75);
    }

    .btn-outline-secondary {
      border-radius: 999px;
      border-color: rgba(148, 163, 184, 0.8);
      color: var(--text-muted);
      background: rgba(15, 23, 42, 0.7);
      font-size: .78rem;
    }

    .btn-outline-secondary:hover {
      border-color: var(--accent);
      color: #e0f2fe;
      background: rgba(15, 23, 42, 0.9);
    }

    /* CHAT */
    .chat-panel {
      display: flex;
      flex-direction: column;
      height: 72vh;
      position: relative;
      overflow: hidden;
      background: radial-gradient(circle at top, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.98));
      border-radius: 1.2rem;
      border: 1px solid rgba(37, 99, 235, 0.55);
      box-shadow:
        0 0 0 1px rgba(15, 23, 42, 1),
        0 20px 55px rgba(15, 23, 42, 0.9),
        0 0 55px rgba(56, 189, 248, 0.4);
      backdrop-filter: blur(18px);
    }

    .chat-header {
      border-bottom: 1px solid rgba(30, 64, 175, 0.65);
      padding: .9rem 1.1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .chat-header-left {
      display: flex;
      align-items: center;
      gap: .7rem;
    }

    .avatar {
      width: 36px;
      height: 36px;
      border-radius: 999px;
      object-fit: cover;
      border: 1px solid rgba(148, 163, 184, 0.8);
      padding: 2px;
      background: radial-gradient(circle at 30% 0, #38bdf8, #0f172a);
      box-shadow: 0 0 14px rgba(56, 189, 248, 0.75);
    }

    .chat-header-text small {
      font-size: .78rem;
      color: var(--text-muted);
    }

    .badge-sim {
      font-size: .7rem;
      border-radius: 999px;
      padding: .2rem .65rem;
      background: rgba(15, 23, 42, 0.9);
      border: 1px solid rgba(148, 163, 184, 0.5);
      color: var(--text-muted);
    }

    .badge-sim span {
      color: #bbf7d0;
    }

    .status-dot {
      width: .55rem;
      height: .55rem;
      border-radius: 999px;
      background: #22c55e;
      box-shadow: 0 0 10px rgba(34, 197, 94, 0.9);
      display: inline-block;
      margin-right: .4rem;
    }

    .chat-body {
      flex: 1;
      padding: 1rem 1.1rem 1.1rem;
      overflow-y: auto;
    }

    .chat-footer {
      border-top: 1px solid rgba(30, 64, 175, 0.65);
      padding: .75rem 1.05rem 1rem;
      background: linear-gradient(180deg, rgba(15, 23, 42, 0.96), rgba(15, 23, 42, 1));
    }

    .chat-row {
      display: flex;
      gap: .55rem;
      align-items: flex-end;
      margin-bottom: .75rem;
    }

    .chat-row.bot {
      justify-content: flex-start;
    }

    .chat-row.user {
      justify-content: flex-end;
    }

    .chat-row.user .avatar {
      display: none;
    }

    .msg {
      max-width: 75%;
      padding: .7rem .95rem;
      border-radius: 1rem;
      font-size: .9rem;
      line-height: 1.4;
      position: relative;
    }

    .msg-bot {
      background: rgba(15, 23, 42, 0.95);
      border: 1px solid rgba(148, 163, 184, 0.45);
      border-top-left-radius: .45rem;
      box-shadow: 0 8px 18px rgba(15, 23, 42, 0.8);
    }

    .msg-user {
      background: radial-gradient(circle at top left, var(--accent-strong), #4f46e5);
      color: #e0f2fe;
      border-top-right-radius: .45rem;
      box-shadow: 0 10px 24px rgba(37, 99, 235, 0.9);
    }

    .msg-meta {
      font-size: .72rem;
      color: var(--text-muted);
      margin-top: .25rem;
    }

    .msg-meta.text-end {
      text-align: right;
    }

    /* BARRA MENSAJE + ARCHIVO */
    .chat-input-row {
      display: flex;
      gap: .6rem;
      align-items: stretch;
      flex-wrap: wrap;
    }

    .chat-input-text {
      flex: 1 1 220px;
    }

    .chat-actions {
      display: flex;
      gap: .45rem;
      align-items: center;
      flex-wrap: nowrap;
    }

    .btn-file-label {
      position: relative;
      overflow: hidden;
      white-space: nowrap;
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      padding-inline: .9rem;
      font-size: .8rem;
    }

    .btn-file-label input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
    }

    .file-chosen {
      font-size: .75rem;
      color: var(--text-muted);
      margin-top: .35rem;
      min-height: 1rem;
    }

    @media (max-width: 992px) {
      .chat-input-row {
        flex-direction: column;
      }

      .chat-actions {
        justify-content: flex-end;
      }

      .chat-panel {
        height: 65vh;
      }
    }

    .quick-replies {
      margin-top: .6rem;
    }
  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h4>AutomAI</h4>
    <a href="dashboard.html">🏠 Dashboard</a>
    <a href="chatbot-configuracion.html" class="active">🤖 Configurar Chatbot</a>
    <a href="Integraciones-Canales.html">🔌 Integraciones</a>
    <a href="Reportes-Dashboard.html">📊 Reportes</a>
    <a href="Usuarios-Gestion.html">👥 Usuarios</a>
    <a href="Configuracion-General.html">⚙️ Ajustes</a>
    <a href="Soporte-Ayuda.html">💬 Soporte</a>
    <hr>
    <a href="Logout.html" class="text-danger">🚪 Cerrar sesión</a>
  </aside>

  <!-- NAVBAR -->
  <nav class="navbar-top">
    <div class="navbar-title">
      <h5>Vista previa del chatbot</h5>
      <span>Simula cómo respondería tu asistente en tiempo real</span>
    </div>
    <div class="user-info">
      <div class="user-pill">
        Usuario: <strong>admin@empresa.com</strong>
      </div>
      <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Usuario" class="user-avatar">
    </div>
  </nav>

  <!-- MAIN -->
  <main class="main-content">
    <div class="row g-4">
      <!-- OPCIONES -->
      <div class="col-lg-4">
        <div class="card section-card h-100">
          <div class="card-body">
            <div class="section-card-header">
              <div>
                <h5 class="card-title mb-0">Opciones de simulación</h5>
                <small class="text">Ajusta cómo se comporta tu bot antes de publicarlo.</small>
              </div>
              <span class="badge-pill pill-primary">Preview</span>
            </div>

            <div class="mb-3 mt-3">
              <label class="form-label">Canal</label>
              <select class="form-select">
                <option selected>WhatsApp</option>
                <option>Telegram</option>
                <option>Web chat</option>
                <option>Email (Gmail)</option>
              </select>
              <div class="form-text">Simula el comportamiento en distintos canales.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Personalidad del bot</label>
              <select class="form-select">
                <option selected>Profesional</option>
                <option>Cálido y cercano</option>
                <option>Formal</option>
                <option>Directo</option>
              </select>
              <div class="form-text">Afecta al tono de las respuestas, no al contenido.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Contexto de negocio</label>
              <textarea class="form-control" rows="3"></textarea>
              <div class="form-text">Este contexto se aplicaría al modelo cuando lo conectes con producción.</div>
            </div>

            <button class="btn btn-primary w-100 mb-3">
              Aplicar cambios de simulación
            </button>

            <hr class="border-secondary">

            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="text" style="font-size: .8rem;">Respuestas rápidas</span>
              <span class="badge-pill">UX testing</span>
            </div>

            <div class="quick-replies d-flex flex-wrap gap-2">
              <button class="btn btn-outline-secondary btn-sm">Horario de atención</button>
              <button class="btn btn-outline-secondary btn-sm">Reservar cita</button>
              <button class="btn btn-outline-secondary btn-sm">Precios</button>
              <button class="btn btn-outline-secondary btn-sm">Soporte técnico</button>
              <button class="btn btn-outline-secondary btn-sm">Hablar con agente</button>
            </div>
          </div>
        </div>
      </div>

      <!-- CHAT -->
      <div class="col-lg-8">
        <div class="chat-panel">
          <!-- Header chat -->
          <div class="chat-header">
            <div class="chat-header-left">
              <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" class="avatar" alt="Bot">
              <div class="chat-header-text">
                <strong>AutomAI Bot</strong><br>
                <small>
                  <span class="status-dot"></span>
                  Estado: Activo · Modelo local <span style="color:#38bdf8;">llama3</span>
                </small>
              </div>
            </div>
            <div>
              <span class="badge-sim">
                Modo <span>Simulación</span>
              </span>
            </div>
          </div>

          <!-- Body chat -->
          <div class="chat-body">
            <?php if (empty($historial)): ?>
              <!-- Si no hay historial, mostramos un saludo inicial -->
              <div class="chat-row bot">
                <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" class="avatar" alt="Bot">
                <div>
                  <div class="msg msg-bot">
                    ¡Hola! Soy <strong>AutomAI</strong> 🤖. Estoy conectado a tu modelo local y listo para probar tus flujos. Hazme una pregunta o súbeme un DOCX para responder solo en base a ese documento.
                  </div>
                  <div class="msg-meta">Bot · ahora</div>
                </div>
              </div>
            <?php else: ?>
              <!-- Pintamos todo el historial almacenado en sesión -->
              <?php foreach ($historial as $msg): ?>
                <?php if ($msg['role'] === 'user'): ?>
                  <div class="chat-row user">
                    <div>
                      <div class="msg msg-user">
                        <?= htmlspecialchars($msg['mensaje']) ?>
                      </div>
                      <div class="msg-meta text-end">Tú</div>
                    </div>
                  </div>
                <?php elseif ($msg['role'] === 'bot'): ?>
                  <div class="chat-row bot">
                    <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" class="avatar" alt="Bot">
                    <div>
                      <div class="msg msg-bot">
                        <?= nl2br(htmlspecialchars($msg['mensaje'])) ?>
                      </div>
                      <div class="msg-meta">Bot</div>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <!-- Footer chat -->
          <div class="chat-footer">
            <form method="post" enctype="multipart/form-data">
              <div class="chat-input-row">
                <!-- Texto -->
                <input
                  type="text"
                  name="mensaje"
                  class="form-control chat-input-text"
                  required
                >

                <!-- Acciones: archivo + enviar -->
                <div class="chat-actions">
                  <label class="btn btn-outline-secondary btn-file-label mb-0">
                    <span>📄 DOCX</span>
                    <input
                      type="file"
                      name="documento"
                      id="documentoInput"
                      accept=".docx"
                    >
                  </label>

                  <button type="submit" name="enviar" class="btn btn-primary">
                    Enviar
                  </button>
                </div>
              </div>
            </form>

            <div id="fileName" class="file-chosen">
              <!-- Aquí se mostrará el nombre del archivo elegido -->
            </div>

            <div class="form-text mt-1">
              Si subes un <strong>DOCX</strong>, el bot responderá solo en base al contenido de ese documento y mantendrá el contexto de la conversación.
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Mostrar nombre del archivo elegido
    const inputDoc = document.getElementById('documentoInput');
    const fileNameSpan = document.getElementById('fileName');

    if (inputDoc && fileNameSpan) {
      inputDoc.addEventListener('change', function () {
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
