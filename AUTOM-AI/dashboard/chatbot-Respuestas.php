<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Respuestas automáticas | AutomAI Solutions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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

    /* MAIN CONTENT */
    .main-content {
      padding: 1.75rem 1.75rem 2.5rem;
      margin-left: 240px;
    }

    @media (max-width: 992px) {
      .main-content { margin-left: 0; }
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

    .section-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: .75rem;
    }

    .badge-pill {
      border-radius: 999px;
      padding: .25rem .9rem;
      font-size: .72rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      border: 1px solid rgba(148, 163, 184, 0.55);
      color: #e0f2fe;
      background: rgba(15, 23, 42, 0.9);
    }

    .pill-primary {
      background: rgba(56, 189, 248, 0.16);
      border-color: rgba(56, 189, 248, 0.7);
      color: #e0f2fe;
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

    .btn-outline-primary {
      border-radius: 999px;
      font-size: .8rem;
      border-color: rgba(56, 189, 248, 0.8);
      color: #e0f2fe;
      background: rgba(15, 23, 42, 0.95);
    }

    .btn-outline-primary:hover {
      background: linear-gradient(135deg, rgba(56, 189, 248, 0.2), rgba(37, 99, 235, 0.6));
      color: #f9fafb;
      border-color: rgba(56, 189, 248, 1);
    }

    .btn-outline-danger {
      border-radius: 999px;
      font-size: .8rem;
    }

    /* ============================
       TABLA MODO OSCURO AUTOMAI
       ============================ */

    .table-automai {
      width: 100%;
      border-collapse: collapse;
      --bs-table-bg: rgba(15, 23, 42, 0.96);
      --bs-table-color: #f9fafb;
      --bs-table-border-color: rgba(51, 65, 85, 0.95);
      --bs-table-hover-bg: rgba(30, 64, 175, 0.88);
      --bs-table-hover-color: #ffffff;
    }

    .table-automai thead {
      background: linear-gradient(90deg, #0b1222 0%, #0f1a33 100%);
    }

    .table-automai thead th {
      color: #e5f0ff !important;
      font-weight: 600;
      font-size: 0.78rem;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      border-bottom: 1px solid rgba(55, 65, 81, 0.9) !important;
    }

    .table-automai tbody tr {
      background-color: rgba(15, 23, 42, 0.96) !important;
      transition: background 0.18s ease, transform 0.12s ease;
    }

    .table-automai tbody tr:hover {
      background-color: rgba(30, 64, 175, 0.88) !important;
      transform: translateY(-1px);
    }

    .table-automai tbody td,
    .table-automai tbody th {
      background-color: transparent !important;
      color: #f9fafb !important;
      border-color: rgba(51, 65, 85, 0.95) !important;
      vertical-align: middle;
      font-size: 0.9rem;
    }

    .table-automai tbody td:first-child {
      color: #e0eaff !important;
      font-weight: 600;
    }

    .table-automai tbody td:last-child {
      text-align: center;
    }

    .table-automai {
      border-radius: 12px;
      overflow: hidden;
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

  <!-- Navbar superior -->
  <div class="navbar-top">
    <div class="navbar-title">
      <h5>RESPUESTAS AUTOMÁTICAS</h5>
      <span>Gestiona las respuestas predefinidas de tus chatbots</span>
    </div>
    <div class="user-info">
      <span>Usuario: <strong>admin@empresa.com</strong></span>
      <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Usuario" class="user-avatar">
    </div>
  </div>

  <!-- Contenido principal -->
  <main class="main-content">
    <div class="card section-card fade-in-up">
      <div class="card-body">
        <div class="section-card-header">
          <div>
            <h4 class="mb-1">Respuestas automáticas del chatbot</h4>
            <small style="color: var(--text-muted);">
              Edita, elimina o añade respuestas predefinidas que tu asistente usará al interactuar con clientes.
            </small>
          </div>
          <span class="badge-pill pill-primary">Plantillas activas</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2 mt-1">
          <div style="font-size:.82rem; color: var(--text-muted); max-width: 60%;">
            Estas respuestas se aplican antes de llamar al modelo de IA. Úsalas para FAQs, horarios, precios base y mensajes
            que no necesitan IA completa.
          </div>
          <button class="btn btn-primary btn-sm">➕ Nueva respuesta</button>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle table-automai">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Pregunta</th>
                <th scope="col">Respuesta</th>
                <th scope="col" class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>¿Cuál es el horario de atención?</td>
                <td>Nuestro horario es de lunes a viernes de 9:00 a 18:00.</td>
                <td class="text-center">
                  <button class="btn btn-outline-primary btn-sm">Editar</button>
                  <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>¿Cómo puedo cambiar mi plan?</td>
                <td>Puedes hacerlo desde la sección “Configuración general” del panel.</td>
                <td class="text-center">
                  <button class="btn btn-outline-primary btn-sm">Editar</button>
                  <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>¿Dónde puedo contactar soporte?</td>
                <td>Escríbenos a soporte@automai.es o abre un ticket en la sección “Soporte”.</td>
                <td class="text-center">
                  <button class="btn btn-outline-primary btn-sm">Editar</button>
                  <button class="btn btn-outline-danger btn-sm">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <footer>
          © 2025 AutomAI Solutions · Configuración del Chatbot · Respuestas automáticas
        </footer>
      </div>
    </div>
  </main>

</body>
</html>
