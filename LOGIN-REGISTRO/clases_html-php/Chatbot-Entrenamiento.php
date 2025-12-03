<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Entrenamiento IA | AutomAI Solutions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>

  <style>
    :root {
      --bg-main: #020617;
      --bg-elevated: rgba(6, 16, 40, 0.96);
      --accent: #38bdf8;
      --accent-strong: #0ea5e9;
      --text-main: #f9fafb;
      --text-muted: #cbd5f5;
      --border-subtle: rgba(148, 163, 184, 0.4);
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
      backdrop-filter: blur(24px);
      pointer-events: none;
      z-index: -1;
    }

    /* SIDEBAR */
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
      border-bottom: 1px solid rgba(148, 163, 184, 0.35);
      background: linear-gradient(90deg,
          rgba(6, 16, 40, 0.98),
          rgba(17, 24, 39, 0.98),
          rgba(6, 16, 40, 0.98));
      backdrop-filter: blur(18px);
      position: sticky;
      top: 0;
      z-index: 40;
      margin-left: 240px;
    }

    @media (max-width: 992px) {
      .navbar-top { margin-left: 0; }
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
      color: #f9fafb;
    }

    .navbar-title span {
      font-size: .8rem;
      color: var(--text-muted);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: .75rem;
      font-size: .85rem;
      color: var(--text-muted);
    }

    .user-info span { font-size: .8rem; }

    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, 0.5);
      padding: 2px;
      background: radial-gradient(circle at 30% 0, #38bdf8, #0f172a);
      box-shadow: 0 0 12px rgba(56, 189, 248, .55);
    }

    /* MAIN CONTENT CENTRADO */
    .main-content {
      margin-left: 240px;
      padding: 1.75rem 1.75rem 2.5rem;
      display: flex;
      justify-content: center;
    }

    .main-inner {
      width: 100%;
      max-width: 1200px;
    }

    @media (max-width: 992px) {
      .main-content {
        margin-left: 0;
        padding-inline: 1.25rem;
      }
    }

    .section-card {
      background: var(--bg-elevated);
      border-radius: 1.4rem;
      border: 1px solid var(--border-subtle);
      box-shadow:
        0 18px 45px rgba(15, 23, 42, 0.9),
        0 0 60px rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(22px);
      color: var(--text-main);
    }

    .card-title {
      font-size: 1rem;
      font-weight: 600;
    }

    .form-label {
      font-size: .85rem;
      color: var(--text-muted);
    }

    .form-control,
    .form-select {
      background: rgba(15, 23, 42, 0.9);
      border-radius: .9rem;
      border: 1px solid rgba(148, 163, 184, 0.5);
      color: var(--text-main);
      font-size: .9rem;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.5);
      background: rgba(15, 23, 42, 0.98);
      color: var(--text-main);
    }

    .form-control::file-selector-button {
      border: none;
      border-radius: 999px;
      padding: .3rem .9rem;
      margin-right: .75rem;
      background: radial-gradient(circle at 10% 0, #38bdf8, #2563eb);
      color: #f9fafb;
      font-size: .8rem;
      cursor: pointer;
    }

    .form-help {
      font-size: .82rem;
      color: var(--text-muted);
    }

    /* Botones */
    .btn-primary {
      background: radial-gradient(circle at 10% 0, #38bdf8, #2563eb);
      border: none;
      border-radius: 999px;
      font-size: .9rem;
      font-weight: 600;
      padding: .5rem 1.3rem;
      color: #f9fafb;
      box-shadow:
        0 10px 25px rgba(59, 130, 246, 0.6),
        0 0 22px rgba(56, 189, 248, 0.85);
    }

    .btn-primary:hover {
      background: radial-gradient(circle at 10% 0, #0ea5e9, #1d4ed8);
      box-shadow:
        0 14px 32px rgba(37, 99, 235, 0.8),
        0 0 26px rgba(56, 189, 248, 1);
    }

    .btn-outline-secondary {
      border-radius: 999px;
      font-size: .9rem;
      border-color: rgba(148, 163, 184, 0.7);
      color: var(--text-muted);
      background: rgba(15, 23, 42, 0.9);
    }

    .btn-outline-secondary:hover {
      border-color: var(--accent);
      color: #e0f2fe;
      background: rgba(15, 23, 42, 1);
    }

    .btn-outline-danger {
      border-radius: 999px;
      font-size: .9rem;
    }

    /* PROGRESS BAR + EFECTO */
    .progress {
      height: 1.3rem;
      border-radius: 999px;
      background: rgba(15, 23, 42, 0.9);
      border: 1px solid rgba(30, 64, 175, 0.7);
      overflow: hidden;
    }

    .progress-bar {
      background: linear-gradient(90deg, #22c55e, #4ade80);
      box-shadow: 0 0 18px rgba(34, 197, 94, 0.75);
      font-size: .8rem;
      font-weight: 600;
      width: 0; /* empieza vacía */
      animation: progress-fill 1.8s ease-out forwards;
    }

    @keyframes progress-fill {
      from { width: 0; }
      to   { width: 68%; } /* mismo valor que el texto 68% */
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 1.2rem 0 0.4rem;
      font-size: 0.8rem;
      color: var(--text-muted);
      margin-top: 2rem;
    }

    /* Animación entrada */
    .fade-in-up {
      animation: fadeInUp .4s ease-out;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <h4>AutomAI</h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="chatbot-configuracion.php" class="active">🤖 Configurar Chatbot</a>
    <a href="integraciones-canales.php">🔌 Integraciones</a>
    <a href="reportes-dashboard.php">📊 Reportes</a>
    <a href="usuarios-gestion.php">👥 Usuarios</a>
    <a href="configuracion-general.php">⚙️ Ajustes</a>
    <a href="soporte-ayuda.php">💬 Soporte</a>
    <hr>
    <a href="logout.php" class="text-danger">🚪 Cerrar sesión</a>
  </aside>

  <!-- Navbar -->
  <nav class="navbar-top">
    <div class="navbar-title">
      <h5>ENTRENAMIENTO DEL MODELO IA</h5>
      <span>Ajusta cómo aprende tu asistente</span>
    </div>
    <div class="user-info">
      <span>Usuario: <strong>admin@empresa.com</strong></span>
      <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Usuario" class="user-avatar">
    </div>
  </nav>

  <!-- Main -->
  <main class="main-content">
    <div class="main-inner">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Entrenamiento del chatbot con IA</h4>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-secondary">Cancelar</button>
          <button class="btn btn-primary">Guardar configuración</button>
        </div>
      </div>
      <p style="color: var(--text-muted);" class="mb-4">
        Sube tus archivos de entrenamiento o ajusta el modelo IA según las necesidades de tu negocio.
      </p>

      <!-- Subida de dataset -->
      <div class="card section-card mb-4 fade-in-up">
        <div class="card-body">
          <h5 class="card-title mb-3">Subir datasets / FAQs</h5>
          <div class="row g-3 align-items-center">
            <div class="col-md-8">
              <input class="form-control" type="file" id="formFile" accept=".csv,.txt">
              <div class="form-help mt-1">
                Formatos aceptados: <strong>.csv</strong> (pregunta,respuesta) y <strong>.txt</strong> (FAQ). Tamaño máximo: 5 MB.
              </div>
            </div>
            <div class="col-md-4 d-grid">
              <button class="btn btn-primary">📤 Subir archivo</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Parámetros del modelo -->
      <div class="card section-card mb-4 fade-in-up">
        <div class="card-body">
          <h5 class="card-title mb-3">Configuración de entrenamiento</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Modelo base</label>
              <select class="form-select">
                <option>Modelo local (rápido y económico)</option>
                <option selected>Modelo avanzado (mayor comprensión)</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Épocas de entrenamiento</label>
              <input type="number" class="form-control" min="1" max="10" value="3">
            </div>
            <div class="col-md-3">
              <label class="form-label">Tamaño de lote</label>
              <input type="number" class="form-control" min="10" max="100" value="25">
            </div>
            <div class="col-md-12">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="autoTrain" checked>
                <label class="form-check-label" for="autoTrain" style="font-size:.9rem;">
                  Entrenamiento automático al subir nuevo dataset
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Estado del modelo -->
      <div class="card section-card mb-4 fade-in-up">
        <div class="card-body">
          <h5 class="card-title mb-3">Estado del modelo</h5>
          <div class="mb-3">
            <label class="form-label">Progreso de entrenamiento</label>
            <div class="progress">
              <div class="progress-bar progress-bar-striped progress-bar-animated"
                role="progressbar">
                68%
              </div>
            </div>
          </div>
          <div class="row text-center mt-4">
            <div class="col-md-4 mb-3 mb-md-0">
              <h6 class="mb-1">Versión actual</h6>
              <p class="fw-bold mb-0" style="color:#38bdf8;">v1.2.4</p>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
              <h6 class="mb-1">Precisión del modelo</h6>
              <p class="fw-bold mb-0" style="color:#4ade80;">92%</p>
            </div>
            <div class="col-md-4">
              <h6 class="mb-1">Último entrenamiento</h6>
              <p class="fw-bold mb-0">14/10/2025</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Acciones -->
      <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-outline-danger">🗑️ Reiniciar modelo</button>
        <button class="btn btn-primary">🚀 Iniciar entrenamiento</button>
      </div>

      <footer>© 2025 AutomAI Solutions · Entrenamiento IA</footer>
    </div>
  </main>

</body>
</html>
