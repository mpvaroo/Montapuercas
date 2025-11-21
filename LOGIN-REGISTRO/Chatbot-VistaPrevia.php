<?php
set_time_limit(90);
session_start();

// Mensaje del usuario, respuesta del bot y contexto del documento
$mensaje_usuario = null;
$respuesta_bot   = null;
$contexto_doc    = $_SESSION['contexto_doc'] ?? null;

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
            $contexto_doc = $textoDoc;
        }
    }
}

// ----------- PROCESAR MENSAJE DEL CHAT -----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['mensaje'])) {
    $mensaje_usuario = $_POST['mensaje'];

    // Si hay documento cargado, lo metemos en el prompt como contexto
    if (!empty($contexto_doc)) {
        $prompt = "Eres un asistente que responde SOLO basándote en el siguiente documento.\n\n".
                  "DOCUMENTO:\n".
                  $contexto_doc.
                  "\n\nPREGUNTA DEL USUARIO:\n".
                  $mensaje_usuario.
                  "\n\nResponde citando únicamente información coherente con el documento.";
    } else {
        // Si no hay documento, responde solo al mensaje
        $prompt = $mensaje_usuario;
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
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vista previa | AutomAI Solutions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
      overflow-x: hidden
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      height: 100vh;
      background: #fff;
      position: fixed;
      left: 0;
      top: 0;
      box-shadow: 2px 0 10px rgba(0, 0, 0, .1);
      padding-top: 1.5rem
    }

    .sidebar h4 {
      text-align: center;
      color: #0077b6;
      margin-bottom: 2rem
    }

    .sidebar a {
      display: block;
      color: #333;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 6px;
      margin: 4px 10px;
      transition: .2s
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: #0077b6;
      color: #fff
    }

    /* Navbar */
    .navbar-top {
      margin-left: 240px;
      height: 60px;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.5rem;
      position: sticky;
      top: 0;
      z-index: 100
    }

    /* Main */
    .main-content {
      margin-left: 240px;
      padding: 2rem
    }

    .section-card {
      background: #fff;
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, .05)
    }

    .btn-primary {
      background: #0077b6;
      border: none
    }

    .btn-primary:hover {
      background: #005f8c
    }

    /* Chat layout */
    .chat-wrap {
      display: flex;
      gap: 1rem
    }

    .chat-panel {
      flex: 1;
      display: flex;
      flex-direction: column;
      height: 70vh
    }

    .chat-header {
      border-bottom: 1px solid #e9ecef;
      padding: 1rem
    }

    .chat-body {
      flex: 1;
      overflow: auto;
      padding: 1rem;
      background: #f5f7fb
    }

    .chat-footer {
      border-top: 1px solid #e9ecef;
      padding: .75rem;
      background: #fff
    }

    .msg {
      max-width: 70%;
      padding: .75rem 1rem;
      border-radius: 14px;
      margin-bottom: .75rem;
      display: inline-block
    }

    .msg-bot {
      background: #ffffff;
      border: 1px solid #e9ecef;
      border-top-left-radius: 6px
    }

    .msg-user {
      background: #0077b6;
      color: #fff;
      border-top-right-radius: 6px;
      margin-left: auto
    }

    .msg-meta {
      font-size: .75rem;
      color: #6c757d;
      margin-top: .25rem
    }

    .quick-replies .btn {
      border-radius: 20px;
      padding: .25rem .75rem
    }

    .avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover
    }

    .chat-row {
      display: flex;
      gap: .5rem;
      align-items: flex-end
    }

    .chat-row.bot .avatar {
      order: 0
    }

    .chat-row.user {
      justify-content: flex-end
    }

    .chat-row.user .avatar {
      display: none
    }

    @media (max-width: 991px) {
      .chat-wrap {
        flex-direction: column
      }

      .chat-panel {
        height: 65vh
      }
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
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

  <!-- Navbar -->
  <nav class="navbar-top">
    <h5 class="m-0">Vista previa del chatbot</h5>
    <div class="user-info">
      <span class="text-muted small me-2">Usuario: <strong>admin@empresa.com</strong></span>
      <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Usuario" width="35" height="35" class="rounded-circle">
    </div>
  </nav>

  <!-- Main -->
  <main class="main-content">
    <div class="row g-3">
      <!-- Panel de opciones -->
      <div class="col-lg-4">
        <div class="card section-card">
          <div class="card-body">
            <h5 class="card-title mb-3">Opciones de simulación</h5>
            <div class="mb-3">
              <label class="form-label">Canal</label>
              <select class="form-select">
                <option selected>WhatsApp</option>
                <option>Telegram</option>
                <option>Web chat</option>
                <option>Email (Gmail)</option>
              </select>
              <div class="form-text">Simula el comportamiento por canal.</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Personalidad del bot</label>
              <select class="form-select">
                <option selected>Profesional</option>
                <option>Cálido y cercano</option>
                <option>Formal</option>
                <option>Directo</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Contexto</label>
              <textarea class="form-control" rows="3" placeholder="(Opcional) Añade contexto: sector, promo activa, horario, etc."></textarea>
            </div>
            <button class="btn btn-primary w-100">Aplicar</button>
            <hr>
            <div class="quick-replies d-flex flex-wrap gap-2">
              <button class="btn btn-outline-secondary btn-sm">Horario de atención</button>
              <button class="btn btn-outline-secondary btn-sm">Reservar cita</button>
              <button class="btn btn-outline-secondary btn-sm">Precios</button>
              <button class="btn btn-outline-secondary btn-sm">Hablar con agente</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel de chat -->
      <div class="col-lg-8">
        <div class="card section-card chat-panel">
          <!-- Header chat -->
          <div class="chat-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" class="avatar" alt="Bot">
              <div>
                <strong>AutomAI Bot</strong><br>
                <span class="text-muted small">Estado: Activo · v1.2.4</span>
              </div>
            </div>
            <div>
              <span class="badge bg-success">Simulación</span>
            </div>
          </div>

          <!-- Body chat -->
          <div class="chat-body">
            <!-- Bot -->
            <div class="chat-row bot">
              <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" class="avatar" alt="Bot">
              <div>
                <div class="msg msg-bot">
                  ¡Hola! Soy <strong>AutomAI</strong> 🤖 ¿En qué puedo ayudarte hoy?
                </div>
                <div class="msg-meta">Bot · 10:20</div>
              </div>
            </div>

            <!-- Usuario -->
            <div class="chat-row user">
              <div>
                <div class="msg msg-user">
                  ¿Cuál es vuestro horario de atención?
                </div>
                <div class="msg-meta text-end">Tú · 10:21</div>
              </div>
            </div>

            <!-- Bot -->
            <div class="chat-row bot">
              <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" class="avatar" alt="Bot">
              <div>
                <div class="msg msg-bot">
                  Atendemos de <strong>lunes a viernes, 9:00–18:00</strong>. Fuera de ese horario puedes dejarnos tu consulta y te respondemos al abrir. 😊
                </div>
                <div class="msg-meta">Bot · 10:21</div>
              </div>
            </div>

            <!-- Usuario -->
            <div class="chat-row user">
              <div>
                <div class="msg msg-user">
                  Genial. ¿Puedo cambiar mi plan desde aquí?
                </div>
                <div class="msg-meta text-end">Tú · 10:22</div>
              </div>
            </div>

            <!-- Bot -->
            <div class="chat-row bot">
              <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" class="avatar" alt="Bot">
              <div>
                <div class="msg msg-bot">
                  Sí. Entra en <em>Configuración &gt; Plan</em> o dime “Cambiar plan” y te guío. ¿Quieres ver las opciones Básico, Avanzado o Premium?
                </div>
                <div class="msg-meta">Bot · 10:22</div>
              </div>
            </div>
          </div>

          <?php if ($mensaje_usuario && $respuesta_bot): ?>

            <!-- Usuario (mensaje enviado) -->
            <div class="chat-row user">
              <div>
                <div class="msg msg-user">
                  <?= htmlspecialchars($mensaje_usuario) ?>
                </div>
                <div class="msg-meta text-end">Tú · ahora</div>
              </div>
            </div>

            <!-- Bot (respuesta de OLLAMA) -->
            <div class="chat-row bot">
              <img src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png" class="avatar" alt="Bot">
              <div>
                <div class="msg msg-bot">
                  <?= nl2br(htmlspecialchars($respuesta_bot)) ?>
                </div>
                <div class="msg-meta">Bot · ahora</div>
              </div>
            </div>

          <?php endif; ?>

          <div class="chat-footer">
            <form method="post" enctype="multipart/form-data">
              <div class="input-group">
                <input
                  type="text"
                  name="mensaje"
                  class="form-control"
                  placeholder="Escribe un mensaje para probar el bot..."
                  required>

                <input type="file" name="documento" class="form-control">

                <button type="submit" name="enviar" class="btn btn-primary">Enviar</button>
              </div>
            </form>

            <div class="form-text">
              Esta es una simulación local. Las respuestas dependen de tu configuración actual.
            </div>
          </div>

  </main>

</body>

</html>