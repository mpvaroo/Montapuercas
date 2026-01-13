<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Configuración General | AutomAI Solutions</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      --shadow-strong: 0 18px 45px rgba(15, 23, 42, 0.9), 0 0 60px rgba(15, 23, 42, 0.95);
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

    /* NAVBAR */
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
      .navbar-top {
        margin-left: 0;
      }

      .sidebar {
        position: static;
        width: 100%;
        height: auto;
        border-right: none;
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
      font-weight: 700;
      letter-spacing: .06em;
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
      margin-left: 240px;
      padding: 1.75rem 1.75rem 2.5rem;
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
      box-shadow: var(--shadow-strong);
      backdrop-filter: blur(22px);
      color: var(--text-main);
    }

    .card-head {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 1rem;
      margin-bottom: .25rem;
    }

    .card-title {
      margin: 0;
      font-size: 1rem;
      font-weight: 700;
      color: #f9fafb;
      letter-spacing: .02em;
    }

    .card-subtitle {
      margin-top: .25rem;
      font-size: .82rem;
      color: var(--text-muted);
    }

    /* FORMS */
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

    .form-control::placeholder {
      color: rgba(203, 213, 245, 0.55);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.5);
      background: rgba(15, 23, 42, 0.98);
      color: var(--text-main);
    }

    .form-text {
      color: var(--text-muted);
      font-size: .82rem;
    }

    .form-check-label {
      color: var(--text-muted);
      font-size: .9rem;
    }

    /* BUTTONS */
    .btn-primary {
      background: radial-gradient(circle at 10% 0, #38bdf8, #2563eb);
      border: none;
      border-radius: 999px;
      font-size: .9rem;
      font-weight: 700;
      padding: .5rem 1.25rem;
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

    /* PILL */
    .pill {
      display: inline-flex;
      align-items: center;
      gap: .45rem;
      padding: .35rem .7rem;
      border-radius: 999px;
      background: rgba(56, 189, 248, 0.18);
      color: #e0f2fe;
      border: 1px solid rgba(56, 189, 248, 0.45);
      font-weight: 700;
      font-size: .85rem;
      white-space: nowrap;
    }

    /* ENTRY ANIM */
    .fade-in-up {
      animation: fadeInUp .4s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(8px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    footer {
      text-align: center;
      padding: 1.2rem 0 0.4rem;
      font-size: 0.8rem;
      color: var(--text-muted);
      margin-top: 2rem;
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
    <a href="Reportes-Dashboard.php">📊 Reportes</a>
    <a href="Usuarios-Gestion.php">👥 Usuarios</a>
    <a href="Configuracion-General.php" class="active">⚙️ Configuración</a>
    <a href="Soporte-Ayuda.php">💬 Soporte</a>
    <hr>
    <a href="Logout.php" class="text-danger">🚪 Cerrar sesión</a>
  </aside>

  <!-- Navbar -->
  <nav class="navbar-top">
    <div class="navbar-title">
      <h5>CONFIGURACIÓN GENERAL</h5>
      <span>Datos básicos, seguridad, plan y API</span>
    </div>
    <div class="user-info">
      <span>Usuario: <strong>admin@empresa.com</strong></span>
      <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Usuario" class="user-avatar">
    </div>
  </nav>

  <!-- Main -->
  <main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h4 class="mb-1" style="font-weight:800;">Ajustes de la cuenta</h4>
        <div class="form-text">Configura la información principal de la empresa y parámetros básicos de seguridad.</div>
      </div>
    </div>

    <!-- 1) Datos básicos -->
    <div class="card section-card mb-4 fade-in-up">
      <div class="card-body">
        <div class="card-head">
          <div>
            <h5 class="card-title">Datos básicos</h5>
            <div class="card-subtitle">Información general de la cuenta / empresa</div>
          </div>
          <button class="btn btn-primary btn-sm" type="button">Guardar</button>
        </div>

        <form class="row g-3 mt-1">
          <div class="col-md-6">
            <label class="form-label">Nombre comercial</label>
            <input type="text" class="form-control" placeholder="AutomAI Solutions">
          </div>
          <div class="col-md-6">
            <label class="form-label">CIF/NIF</label>
            <input type="text" class="form-control" placeholder="B12345678">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email de contacto</label>
            <input type="email" class="form-control" placeholder="contacto@empresa.com">
          </div>
          <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input type="tel" class="form-control" placeholder="+34 600 000 000">
          </div>
          <div class="col-12">
            <label class="form-label">Dirección</label>
            <input type="text" class="form-control" placeholder="C/ Ejemplo, 123 · 29000 · Málaga · España">
          </div>
          <div class="col-md-6">
            <label class="form-label">Zona horaria</label>
            <select class="form-select">
              <option selected>Europe/Madrid (UTC+01:00)</option>
              <option>Europe/Lisbon (UTC+00:00)</option>
              <option>America/Bogota (UTC-05:00)</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Idioma</label>
            <select class="form-select">
              <option selected>Español</option>
              <option>Inglés</option>
              <option>Portugués</option>
            </select>
          </div>
        </form>
      </div>
    </div>

    <!-- 2) Seguridad básica -->
    <div class="card section-card mb-4 fade-in-up">
      <div class="card-body">
        <div class="card-head">
          <div>
            <h5 class="card-title">Seguridad</h5>
            <div class="card-subtitle">Ajustes básicos de acceso</div>
          </div>
          <button class="btn btn-primary btn-sm" type="button">Guardar</button>
        </div>

        <form class="row g-3 mt-1">
          <div class="col-md-4">
            <label class="form-label">2FA requerido</label>
            <select class="form-select">
              <option selected>Opcional</option>
              <option>Obligatorio (Admins)</option>
              <option>Obligatorio (Todos)</option>
              <option>No</option>
            </select>
            <div class="form-text mt-1">Controla si se exige verificación en dos pasos.</div>
          </div>

          <div class="col-md-4">
            <label class="form-label">Sesión inactiva (min)</label>
            <input type="number" class="form-control" value="30" min="5" max="240">
            <div class="form-text mt-1">Cierre automático por inactividad.</div>
          </div>

          <div class="col-md-4">
            <label class="form-label">Política de contraseña</label>
            <select class="form-select">
              <option>Estándar (8+)</option>
              <option selected>Fuerte (12+)</option>
              <option>Muy fuerte (16+)</option>
            </select>
            <div class="form-text mt-1">Reglas mínimas para nuevas contraseñas.</div>
          </div>

          <div class="col-12">
            <div class="form-check mt-1">
              <input class="form-check-input" type="checkbox" id="audit">
              <label class="form-check-label" for="audit">Activar logs de auditoría</label>
            </div>
            <div class="form-text mt-1">Registra cambios importantes (usuarios, accesos, exportaciones).</div>
          </div>
        </form>
      </div>
    </div>

    <!-- 3) Plan (solo lectura / básico) -->
    <div class="card section-card mb-4 fade-in-up">
      <div class="card-body">
        <div class="card-head">
          <div>
            <h5 class="card-title">Plan</h5>
            <div class="card-subtitle">Estado de suscripción (demo)</div>
          </div>
          <span class="pill">Activo · Premium</span>
        </div>

        <div class="row g-3 mt-2">
          <div class="col-md-6">
            <label class="form-label">Plan actual</label>
            <input type="text" class="form-control" value="Premium (300 €/mes)" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Próxima renovación</label>
            <input type="text" class="form-control" value="30/11/2025" readonly>
          </div>
        </div>
      </div>
    </div>

    <!-- 4) API Key -->
    <div class="card section-card fade-in-up">
      <div class="card-body">
        <div class="card-head">
          <div>
            <h5 class="card-title">API Key</h5>
            <div class="card-subtitle">Credencial para integraciones (demo)</div>
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" type="button">Copiar</button>
            <button class="btn btn-outline-secondary btn-sm" type="button">Regenerar</button>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-md-8">
            <label class="form-label">Clave</label>
            <input type="text" class="form-control" value="sk_live_************************" readonly>
            <div class="form-text mt-1">No la compartas. Guárdala en un gestor de contraseñas.</div>
          </div>
          <div class="col-md-4">
            <label class="form-label">Permisos</label>
            <select class="form-select">
              <option selected>Lectura & Escritura</option>
              <option>Solo lectura</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <footer>© 2025 AutomAI Solutions · Configuración General</footer>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>