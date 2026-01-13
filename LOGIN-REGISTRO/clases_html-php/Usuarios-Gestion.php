<?php

declare(strict_types=1);

/**
 * Usuarios-Gestion.php (SOLO VISUAL)
 * - NO usa sesiones
 * - NO consulta BD
 * - Datos MOCK para que luego conectes con usuario + usuario_rol + rol
 */

$usuario = [
  "email"   => "admin@empresa.com",
  "rol"     => "admin",
  "empresa" => "AutomAI Demo",
  "plan"    => "Premium"
];

$usuariosMock = [
  [
    "nombre" => "Sofía Vázquez",
    "email"  => "sofia@automai.solutions",
    "rol"    => "admin",
    "estado" => "ACTIVO",
    "ultimo" => "31/10/2025 12:22",
    "twofa"  => "Activado"
  ],
  [
    "nombre" => "Manuel Varo",
    "email"  => "manuel@automai.solutions",
    "rol"    => "manager",
    "estado" => "PENDIENTE",
    "ultimo" => "—",
    "twofa"  => "No"
  ],
  [
    "nombre" => "María López",
    "email"  => "maria@cliente.com",
    "rol"    => "agente",
    "estado" => "ACTIVO",
    "ultimo" => "30/10/2025 18:04",
    "twofa"  => "No"
  ],
  [
    "nombre" => "Pablo Ruiz",
    "email"  => "pablo@cliente.com",
    "rol"    => "visor",
    "estado" => "DESHABILITADO",
    "ultimo" => "12/10/2025 09:11",
    "twofa"  => "No"
  ],
];

function labelRol(string $rol): array
{
  return match (strtolower($rol)) {
    "admin"   => ["ADMIN",   "pill-pill pill-blue"],
    "manager" => ["MANAGER", "pill-pill pill-pink"],
    "agente"  => ["AGENTE",  "pill-pill pill-green"],
    "visor"   => ["VISOR",   "pill-pill pill-gray"],
    default   => [strtoupper($rol), "pill-pill pill-gray"],
  };
}

function labelEstado(string $estado): array
{
  return match (strtoupper($estado)) {
    "ACTIVO"        => ["ACTIVO", "pill-pill pill-green"],
    "PENDIENTE"     => ["PENDIENTE", "pill-pill pill-amber"],
    "DESHABILITADO" => ["DESHABILITADO", "pill-pill pill-red"],
    default         => [strtoupper($estado), "pill-pill pill-gray"],
  };
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Usuarios | AutomAI Solutions</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
      --shadow-strong: 0 18px 45px rgba(15, 23, 42, 0.9), 0 0 60px rgba(15, 23, 42, 0.95);
      --radius-lg: 1.5rem;
      --sidebar-w: 240px;
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
       SIDEBAR (IGUAL QUE CONFIGURACIÓN GENERAL) - 240px
       ========================================================= */
    .sidebar {
      width: var(--sidebar-w);
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

    .top-actions {
      display: flex;
      align-items: center;
      gap: .9rem;
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
      padding: 2px;
      box-shadow: 0 0 12px rgba(56, 189, 248, .6);
    }

    /* botón principal (igual estética) */
    .btn-primary-glow {
      border-radius: 999px;
      border: none;
      padding: .55rem 1.05rem;
      font-size: .88rem;
      font-weight: 800;
      color: #f9fafb;
      background: radial-gradient(circle at 10% 0, var(--accent), #2563eb);
      box-shadow: 0 10px 25px rgba(59, 130, 246, .65), 0 0 24px rgba(56, 189, 248, .85);
      transition: transform .18s ease, box-shadow .22s ease, background .18s ease;
      white-space: nowrap;
    }

    .btn-primary-glow:hover {
      transform: translateY(-1px);
      background: radial-gradient(circle at 10% 0, var(--accent-strong), #1d4ed8);
      box-shadow: 0 16px 35px rgba(37, 99, 235, .9), 0 0 30px rgba(56, 189, 248, 1);
    }

    .btn-ghost {
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .65);
      background: rgba(15, 23, 42, .65);
      color: rgba(203, 213, 245, .9);
      padding: .55rem 1rem;
      font-size: .88rem;
      font-weight: 800;
      transition: all .18s ease;
    }

    .btn-ghost:hover {
      border-color: rgba(56, 189, 248, .85);
      color: #e0f2fe;
      background: rgba(15, 23, 42, .85);
      box-shadow: 0 12px 28px rgba(15, 23, 42, .85), 0 0 22px rgba(56, 189, 248, .35);
    }

    /* =========================================================
       MAIN
       ========================================================= */
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

    .card-pad {
      padding: 1.25rem 1.4rem;
    }

    .card-header-row {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: .85rem;
    }

    .card-header-row h6 {
      margin: 0;
      font-size: .9rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: rgba(249, 250, 251, .95);
    }

    .card-sub {
      margin: .2rem 0 0;
      font-size: .82rem;
      color: rgba(203, 213, 245, .75);
      max-width: 70ch;
    }

    .badge-soft {
      padding: .18rem .6rem;
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .6);
      font-size: .7rem;
      color: rgba(203, 213, 245, .85);
      background: rgba(15, 23, 42, .65);
      text-transform: uppercase;
      letter-spacing: .10em;
      white-space: nowrap;
    }

    /* Inputs dark */
    .form-label {
      font-size: .72rem;
      color: rgba(203, 213, 245, .75);
      text-transform: uppercase;
      letter-spacing: .14em;
      margin-bottom: .35rem;
    }

    .form-control,
    .form-select {
      background: rgba(15, 23, 42, .75);
      border: 1px solid rgba(148, 163, 184, .45);
      color: var(--text-main);
      border-radius: 1rem;
      padding: .6rem .85rem;
    }

    .form-control::placeholder {
      color: rgba(148, 163, 184, .55)
    }

    .form-control:focus,
    .form-select:focus {
      border-color: rgba(56, 189, 248, .85);
      box-shadow: 0 0 0 2px rgba(56, 189, 248, .18);
      background: rgba(15, 23, 42, .85);
      color: var(--text-main);
    }

    /* Pills (rol/estado) */
    .pill-pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: .26rem .75rem;
      border-radius: 999px;
      font-size: .72rem;
      letter-spacing: .10em;
      text-transform: uppercase;
      border: 1px solid rgba(148, 163, 184, .6);
      background: rgba(15, 23, 42, .65);
      white-space: nowrap;
      font-weight: 900;
    }

    .pill-blue {
      border-color: rgba(56, 189, 248, .65);
      color: #e0f2fe;
      background: rgba(56, 189, 248, .12);
    }

    .pill-green {
      border-color: rgba(34, 197, 94, .55);
      color: #bbf7d0;
      background: rgba(34, 197, 94, .10);
    }

    .pill-amber {
      border-color: rgba(245, 158, 11, .55);
      color: #fed7aa;
      background: rgba(245, 158, 11, .10);
    }

    .pill-red {
      border-color: rgba(248, 113, 113, .60);
      color: #fecaca;
      background: rgba(248, 113, 113, .10);
    }

    .pill-gray {
      border-color: rgba(148, 163, 184, .55);
      color: #cbd5e1;
      background: rgba(148, 163, 184, .08);
    }

    .pill-pink {
      border-color: rgba(236, 72, 153, .55);
      color: #fbcfe8;
      background: rgba(236, 72, 153, .10);
    }

    /* Table dark */
    .table-wrap {
      border-radius: 1.25rem;
      overflow: hidden;
      border: 1px solid rgba(148, 163, 184, .35);
      background: rgba(10, 14, 30, .28);
    }

    table.users-table {
      margin: 0;
      width: 100%;
      color: var(--text-main);
      border-collapse: separate;
      border-spacing: 0;
    }

    .users-table thead th {
      background: rgba(15, 23, 42, .85) !important;
      color: rgba(249, 250, 251, .92) !important;
      border-bottom: 1px solid rgba(255, 255, 255, .10) !important;
      font-size: .78rem;
      letter-spacing: .10em;
      text-transform: uppercase;
      padding: .95rem 1rem;
      white-space: nowrap;
    }

    .users-table tbody td {
      background: rgba(10, 14, 30, .18);
      border-bottom: 1px solid rgba(255, 255, 255, .08);
      padding: .95rem 1rem;
      vertical-align: middle;
      font-size: .9rem;
      color: rgba(249, 250, 251, .88) !important;
    }

    .users-table tbody tr:hover td {
      background: rgba(56, 189, 248, .06);
    }

    .user-name {
      font-weight: 800;
      color: rgba(249, 250, 251, .95);
    }

    .user-email {
      font-size: .82rem;
      color: rgba(203, 213, 245, .75);
      margin-top: .12rem;
    }

    /* Action buttons (table) */
    .btn-sm-soft {
      border-radius: 999px;
      border: 1px solid rgba(148, 163, 184, .55);
      background: rgba(15, 23, 42, .70);
      color: rgba(249, 250, 251, .9);
      padding: .38rem .75rem;
      font-size: .78rem;
      font-weight: 800;
      transition: all .18s ease;
      white-space: nowrap;
    }

    .btn-sm-soft:hover {
      border-color: rgba(56, 189, 248, .75);
      box-shadow: 0 10px 25px rgba(15, 23, 42, .85), 0 0 18px rgba(56, 189, 248, .35);
    }

    .btn-sm-danger {
      border-radius: 999px;
      border: 1px solid rgba(248, 113, 113, .65);
      background: rgba(127, 29, 29, .25);
      color: #fecaca;
      padding: .38rem .75rem;
      font-size: .78rem;
      font-weight: 800;
      transition: all .18s ease;
      white-space: nowrap;
    }

    .btn-sm-danger:hover {
      background: rgba(190, 24, 24, .75);
      box-shadow: 0 12px 30px rgba(127, 29, 29, .9), 0 0 18px rgba(248, 113, 113, .55);
    }

    /* Pagination dark */
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

    /* Responsive (igual que config) */
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
        border-right: none;
      }
    }

    /* Modal theme (dark) */
    .modal-content {
      background: rgba(15, 23, 42, .96);
      border: 1px solid rgba(148, 163, 184, .35);
      border-radius: 1.25rem;
      color: var(--text-main);
      box-shadow: 0 18px 45px rgba(15, 23, 42, .9);
    }

    .modal-header,
    .modal-footer {
      border-color: rgba(148, 163, 184, .18);
    }

    .btn-close {
      filter: invert(1);
      opacity: .7;
    }

    .modal-title {
      font-size: .9rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: rgba(249, 250, 251, .95);
      font-weight: 900;
    }

    .form-text {
      color: rgba(203, 213, 245, .75);
    }

    /* NO sobrescribir bootstrap a blanco raro */
    code {
      color: #f472b6;
    }
  </style>
</head>

<body>

  <!-- Sidebar (igual Configuración) -->
  <aside class="sidebar">
    <h4>AutomAI</h4>

    <a href="Dashboard.php">🏠 Dashboard</a>
    <a href="Chatbot-Configuracion.php">🤖 Configurar Chatbot</a>
    <a href="Integraciones-Canales.php">🔌 Integraciones</a>
    <a href="Reportes-Dashboard.php">📊 Reportes</a>
    <a href="Usuarios-Gestion.php" class="active">👥 Usuarios</a>
    <a href="Configuracion-General.php">⚙️ Configuración</a>
    <a href="Soporte-Ayuda.php">💬 Soporte</a>

    <hr>
    <a href="Logout.php" class="text-danger">🚪 Cerrar sesión</a>
  </aside>

  <!-- Navbar superior -->
  <header class="navbar-top">
    <div class="navbar-title">
      <span><?= htmlspecialchars($usuario["empresa"], ENT_QUOTES, "UTF-8"); ?></span>
      <h5>Gestión de usuarios</h5>
    </div>

    <div class="top-actions">
      <button class="btn-primary-glow" data-bs-toggle="modal" data-bs-target="#modalInvitar">➕ Invitar</button>

      <div class="user-pill">
        <span><?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8"); ?></span>
        <img src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png" alt="Avatar usuario" class="user-avatar">
      </div>
    </div>
  </header>

  <!-- Contenido principal -->
  <main class="main-content">

    <!-- FILTROS -->
    <section class="section-card card-pad mb-4">
      <div class="card-header-row">
        <div>
          <h6>Filtro y búsqueda</h6>
          <p class="card-sub">Demo visual. Luego estos filtros se conectan a <code>usuario</code> + <code>usuario_rol</code> + <code>rol</code>.</p>
        </div>
        <span class="badge-soft">Mock data</span>
      </div>

      <form class="row g-3 align-items-end">
        <div class="col-lg-5">
          <label class="form-label">Buscar</label>
          <input class="form-control" type="text" placeholder="nombre, email, IP... (demo)">
        </div>

        <div class="col-lg-3">
          <label class="form-label">Rol</label>
          <select class="form-select">
            <option selected>Todos</option>
            <option>Admin</option>
            <option>Manager</option>
            <option>Agente</option>
            <option>Visor</option>
          </select>
        </div>

        <div class="col-lg-3">
          <label class="form-label">Estado</label>
          <select class="form-select">
            <option selected>Todos</option>
            <option>Activo</option>
            <option>Pendiente</option>
            <option>Deshabilitado</option>
          </select>
        </div>

        <div class="col-lg-1 d-grid gap-2">
          <button type="button" class="btn-ghost">Limpiar</button>
          <button type="button" class="btn-primary-glow">Aplicar</button>
        </div>
      </form>
    </section>

    <!-- TABLA -->
    <section class="section-card card-pad">
      <div class="card-header-row">
        <div>
          <h6>Usuarios</h6>
          <p class="card-sub">Listado visual. Más adelante irá paginado y con acciones reales (invitar, reset, deshabilitar…).</p>
        </div>
        <span class="badge-soft"><?= count($usuariosMock) ?> usuarios</span>
      </div>

      <div class="table-wrap">
        <div class="table-responsive">
          <table class="users-table align-middle">
            <thead>
              <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Último acceso</th>
                <th>2FA</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($usuariosMock as $u): ?>
                <?php [$rolTxt, $rolClass] = labelRol($u["rol"]); ?>
                <?php [$estTxt, $estClass] = labelEstado($u["estado"]); ?>

                <tr>
                  <td>
                    <div class="user-name"><?= htmlspecialchars($u["nombre"], ENT_QUOTES, "UTF-8"); ?></div>
                    <div class="user-email"><?= htmlspecialchars($u["email"], ENT_QUOTES, "UTF-8"); ?></div>
                  </td>

                  <td><span class="<?= $rolClass; ?>"><?= $rolTxt; ?></span></td>
                  <td><span class="<?= $estClass; ?>"><?= $estTxt; ?></span></td>
                  <td><?= htmlspecialchars($u["ultimo"], ENT_QUOTES, "UTF-8"); ?></td>
                  <td><?= htmlspecialchars($u["twofa"], ENT_QUOTES, "UTF-8"); ?></td>

                  <td class="text-end">
                    <div class="d-inline-flex flex-wrap gap-2 justify-content-end">
                      <button class="btn-sm-soft" data-bs-toggle="modal" data-bs-target="#modalEditar">Editar</button>
                      <button class="btn-sm-soft">Reset pass</button>
                      <button class="btn-sm-danger">Acción</button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted-soft" style="font-size:.82rem;">Mostrando 1–4 de 27 usuarios</div>
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
      © 2026 AutomAI Solutions · Gestión de Usuarios · Plan: <?= htmlspecialchars($usuario["plan"], ENT_QUOTES, "UTF-8"); ?>
    </footer>

  </main>

  <!-- Modal: Invitar usuario -->
  <div class="modal fade" id="modalInvitar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Invitar nuevo usuario</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3">
            <div class="col-12">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" placeholder="nombre@empresa.com">
            </div>
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" placeholder="Nombre">
            </div>
            <div class="col-md-6">
              <label class="form-label">Apellido</label>
              <input type="text" class="form-control" placeholder="Apellido">
            </div>
            <div class="col-12">
              <label class="form-label">Rol</label>
              <select class="form-select">
                <option>Admin</option>
                <option selected>Manager</option>
                <option>Agente</option>
                <option>Visor</option>
              </select>
              <div class="form-text">Los permisos se pueden ajustar más tarde.</div>
            </div>
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="inv-2fa">
                <label class="form-check-label" for="inv-2fa" style="color:rgba(203,213,245,.85);">Requerir 2FA al primer acceso</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn-ghost" data-bs-dismiss="modal" type="button">Cancelar</button>
          <button class="btn-primary-glow" type="button">Enviar invitación</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Editar usuario -->
  <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Editar usuario</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" value="Nombre">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" value="usuario@dominio.com">
            </div>
            <div class="col-md-4">
              <label class="form-label">Rol</label>
              <select class="form-select">
                <option>Admin</option>
                <option>Manager</option>
                <option selected>Agente</option>
                <option>Visor</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Estado</label>
              <select class="form-select">
                <option selected>Activo</option>
                <option>Pendiente</option>
                <option>Deshabilitado</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">2FA</label>
              <select class="form-select">
                <option selected>Activado</option>
                <option>Desactivado</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Notas internas</label>
              <textarea class="form-control" rows="3" placeholder="Observaciones…"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn-ghost" data-bs-dismiss="modal" type="button">Cancelar</button>
          <button class="btn-primary-glow" type="button">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>