<?php

declare(strict_types=1);
// SOLO VISTA (sin sesión / sin BD / sin lógica)
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Configuración avanzada | AutomAI Solutions</title>

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

    .form-check-label {
      color: var(--text-muted);
      font-size: .9rem;
    }

    .form-help {
      font-size: .82rem;
      color: var(--text-muted);
    }

    /* Botones (igual que tus páginas dark) */
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

    /* Layout horas */
    .hours-grid {
      display: grid;
      grid-template-columns: 160px 1fr 1fr;
      gap: .6rem .75rem;
      align-items: center;
    }

    .hours-grid .day {
      font-weight: 600;
      color: #e5f0ff;
      font-size: .9rem;
    }

    @media (max-width: 991px) {
      .hours-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Animación entrada */
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
      <h5>CONFIGURACIÓN AVANZADA</h5>
      <span>Idioma, tono, horarios, fallback y límites</span>
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
        <h4 class="mb-1">Ajustes del comportamiento del chatbot</h4>
        <div class="form-help">Configura idioma, tono, horario de atención, reglas de derivación a humano y límites operativos.</div>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" type="button">Cancelar</button>
        <button class="btn btn-primary" type="button">Guardar cambios</button>
      </div>
    </div>

    <!-- Idioma y tono -->
    <div class="card section-card mb-4 fade-in-up">
      <div class="card-body">
        <h5 class="card-title mb-3">Idioma y tono</h5>
        <div class="row g-3">
          <div class="col-lg-4">
            <label class="form-label">Idioma principal</label>
            <select class="form-select">
              <option selected>Español (ES)</option>
              <option>Inglés (EN)</option>
              <option>Portugués (PT)</option>
              <option>Francés (FR)</option>
            </select>
            <div class="form-help mt-1">Define el idioma por defecto de las respuestas.</div>
          </div>

          <div class="col-lg-4">
            <label class="form-label">Tono de comunicación</label>
            <select class="form-select">
              <option selected>Profesional</option>
              <option>Cálido y cercano</option>
              <option>Formal</option>
              <option>Directo y breve</option>
            </select>
            <div class="form-help mt-1">Afecta estilo y estructura de las respuestas.</div>
          </div>

          <div class="col-lg-4">
            <label class="form-label">Longitud de respuesta</label>
            <input type="range" class="form-range" min="1" max="3" value="2">
            <div class="d-flex justify-content-between small" style="color: var(--text-muted);">
              <span>Corta</span><span>Media</span><span>Larga</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Horario de atención -->
    <div class="card section-card mb-4 fade-in-up">
      <div class="card-body">
        <h5 class="card-title mb-3">Horario de atención</h5>

        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" id="switchHorario" checked>
          <label class="form-check-label" for="switchHorario">
            Activar horario de atención (fuera de horario el bot responde con mensaje de espera)
          </label>
        </div>

        <div class="hours-grid">
          <div class="day">Lunes</div>
          <div><input type="time" class="form-control" value="09:00"></div>
          <div><input type="time" class="form-control" value="18:00"></div>

          <div class="day">Martes</div>
          <div><input type="time" class="form-control" value="09:00"></div>
          <div><input type="time" class="form-control" value="18:00"></div>

          <div class="day">Miércoles</div>
          <div><input type="time" class="form-control" value="09:00"></div>
          <div><input type="time" class="form-control" value="18:00"></div>

          <div class="day">Jueves</div>
          <div><input type="time" class="form-control" value="09:00"></div>
          <div><input type="time" class="form-control" value="18:00"></div>

          <div class="day">Viernes</div>
          <div><input type="time" class="form-control" value="09:00"></div>
          <div><input type="time" class="form-control" value="18:00"></div>

          <div class="day">Sábado</div>
          <div><input type="time" class="form-control" value="10:00"></div>
          <div><input type="time" class="form-control" value="14:00"></div>

          <div class="day">Domingo</div>
          <div><input type="time" class="form-control" value="00:00" disabled></div>
          <div><input type="time" class="form-control" value="00:00" disabled></div>
        </div>

        <div class="form-help mt-2">Consejo: ajusta el mensaje “fuera de horario” en Respuestas automáticas.</div>
      </div>
    </div>

    <!-- Derivación a humano -->
    <div class="card section-card mb-4 fade-in-up">
      <div class="card-body">
        <h5 class="card-title mb-3">Derivación a humano (fallback)</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="switchFallback" checked>
              <label class="form-check-label" for="switchFallback">Activar derivación a agente humano</label>
            </div>
            <div class="form-help mt-1">Si la IA no puede resolver, se transfiere a un agente.</div>
          </div>

          <div class="col-md-3">
            <label class="form-label">Reintentos IA antes de derivar</label>
            <input type="number" class="form-control" min="1" max="5" value="2">
          </div>

          <div class="col-md-3">
            <label class="form-label">Tiempo de espera (seg)</label>
            <input type="number" class="form-control" min="5" max="120" value="20">
          </div>

          <div class="col-md-6">
            <label class="form-label">Email de soporte (fallback)</label>
            <input type="email" class="form-control" value="soporte@tuempresa.com">
          </div>

          <div class="col-md-6">
            <label class="form-label">Teléfono / WhatsApp de soporte</label>
            <input type="tel" class="form-control" placeholder="+34 600 000 000">
          </div>
        </div>
      </div>
    </div>

    <!-- Límites y seguridad -->
    <div class="card section-card mb-4 fade-in-up">
      <div class="card-body">
        <h5 class="card-title mb-3">Límites y seguridad</h5>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Límite de mensajes/minuto</label>
            <input type="number" class="form-control" min="10" max="500" value="120">
            <div class="form-help mt-1">Ayuda a evitar abuso y costes excesivos.</div>
          </div>

          <div class="col-md-4">
            <label class="form-label">Longitud máxima por respuesta (car.)</label>
            <input type="number" class="form-control" min="100" max="1000" value="350">
          </div>

          <div class="col-md-4">
            <label class="form-label">Moderación de contenido</label>
            <select class="form-select">
              <option selected>Básica (recomendada)</option>
              <option>Estricta</option>
              <option>Desactivada</option>
            </select>
          </div>

          <div class="col-12">
            <div class="form-check mt-2">
              <input class="form-check-input" type="checkbox" id="rgpdCheck" checked>
              <label class="form-check-label" for="rgpdCheck">
                Activar políticas RGPD: anonimización de datos y borrado bajo solicitud.
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer>© 2025 AutomAI Solutions · Configuración avanzada del chatbot</footer>
  </main>

</body>

</html>