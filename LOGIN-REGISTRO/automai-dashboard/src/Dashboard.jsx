import React from "react";
import { motion } from "framer-motion";
import Plasma from "./components/Plasma";

const fadeUp = {
  hidden: { opacity: 0, y: 18 },
  visible: (delay = 0) => ({
    opacity: 1,
    y: 0,
    transition: { delay, duration: 0.55, ease: [0.22, 0.61, 0.36, 1] }
  })
};

export default function Dashboard() {
  return (
    <>
      {/* Fondo plasma animado detrás de todo */}
      <Plasma
        color="#ff6b35"
        speed={0.3}
        direction="forward"
        scale={1.1}
        opacity={0.6}
        mouseInteractive={false}
      />

      <div className="automai-shell">
        {/* Si quieres solo plasma, no usamos los blobs extra */}
        {/* <div className="bg-orb bg-orb-1" />
        <div className="bg-orb bg-orb-2" />
        <div className="bg-orb bg-orb-3" /> */}

        {/* Sidebar estilo panel VisionOS pegado a la izquierda */}
        <aside className="automai-sidebar">
          <div className="sidebar-logo">
            <span className="logo-inner">A</span>
          </div>

          <nav className="sidebar-nav">
            <button className="nav-icon active">
              <span>🏠</span>
              <span className="nav-tooltip">Dashboard</span>
            </button>
            <button className="nav-icon">
              <span>🤖</span>
              <span className="nav-tooltip">Chatbots</span>
            </button>
            <button className="nav-icon">
              <span>🔌</span>
              <span className="nav-tooltip">Integraciones</span>
            </button>
            <button className="nav-icon">
              <span>📊</span>
              <span className="nav-tooltip">Reportes</span>
            </button>
            <button className="nav-icon">
              <span>👥</span>
              <span className="nav-tooltip">Usuarios</span>
            </button>
            <button className="nav-icon">
              <span>⚙️</span>
              <span className="nav-tooltip">Ajustes</span>
            </button>
          </nav>

          <div className="sidebar-footer">
            <button className="nav-icon nav-logout">
              <span>🚪</span>
              <span className="nav-tooltip">Cerrar sesión</span>
            </button>
          </div>
        </aside>

        <div className="automai-main">
          {/* Navbar superior */}
          <header className="automai-navbar">
            <div className="navbar-left">
              <span className="navbar-subtitle">AutomAI Solutions</span>
              <h1 className="navbar-title">Panel de analítica de canales</h1>
            </div>

            <div className="navbar-right">
              <div className="user-pill">
                <span className="user-email">admin@empresa.com</span>
                <img
                  className="user-avatar"
                  src="https://cdn-icons-png.flaticon.com/512/3177/3177440.png"
                  alt="Usuario"
                />
              </div>
            </div>
          </header>

          {/* Contenido */}
          <main className="automai-content">
            <div className="content-grid">
              {/* HERO Vision Pro + métricas grandes */}
              <motion.section
                className="card hero-card"
                initial="hidden"
                animate="visible"
                variants={fadeUp}
                custom={0}
              >
                <div className="hero-main">
                  {/* Lado texto */}
                  <div className="hero-copy">
                    <div className="hero-badge">
                      <span className="dot-live" />
                      Live Channel Analytics
                    </div>
                    <h2>Control total de tus chatbots omnicanal</h2>
                    <p>
                      Supervisa el rendimiento en todos los canales, detecta
                      cuellos de botella y deja que la IA optimice la
                      experiencia de tus clientes en tiempo real.
                    </p>

                    <motion.button
                      whileHover={{ scale: 1.04 }}
                      whileTap={{ scale: 0.97 }}
                      className="btn-hero"
                    >
                      ⚡ Optimizar ahora
                    </motion.button>
                  </div>

                  {/* Lado “pantalla flotante” estilo VisionOS */}
                  <div className="hero-photo-wrapper hero-media">
                    <video
                      className="hero-video"
                      autoPlay
                      muted
                      loop
                      playsInline
                    >
                      <source src="https://assets.mixkit.co/videos/preview/mixkit-digital-lines-1248-large.mp4" type="video/mp4" />
                    </video>
                    <div className="hero-photo-glow" />
                  </div>
                </div>

                {/* KPIs grandes debajo del hero */}
                <div className="hero-kpis">
                  {[
                    { label: "Consultas totales", value: "76k", sub: "+14% este mes" },
                    { label: "Usuarios únicos", value: "1.5M", sub: "Omnicanal activo" },
                    { label: "Valor generado", value: "$3.6k", sub: "Tickets auto-resueltos" },
                    { label: "CSAT medio", value: "4.7★", sub: "Basado en encuestas" }
                  ].map((kpi, idx) => (
                    <motion.div
                      key={kpi.label}
                      className="hero-kpi"
                      initial="hidden"
                      animate="visible"
                      variants={fadeUp}
                      custom={0.1 + idx * 0.06}
                    >
                      <span className="kpi-label">{kpi.label}</span>
                      <span className="kpi-value">{kpi.value}</span>
                      <span className="kpi-sub">{kpi.sub}</span>
                    </motion.div>
                  ))}
                </div>
              </motion.section>

              {/* Gráfico + mini-métricas vision style */}
              <motion.section
                className="card side-card"
                initial="hidden"
                animate="visible"
                variants={fadeUp}
                custom={0.15}
              >
                <div className="side-header">
                  <div>
                    <span className="side-subtitle">Actividad global</span>
                    <h3>Usuarios activos por canal</h3>
                  </div>
                  <div className="side-chip">Últimos 7 días</div>
                </div>

                <div className="chart-shell">
                  <div className="chart-grid" />
                  <div className="chart-glow" />
                  <div className="chart-lines">
                    <div className="chart-line chart-line-1" />
                    <div className="chart-line chart-line-2" />
                    <div className="chart-line chart-line-3" />
                  </div>
                </div>

                <div className="side-metrics">
                  <div className="side-metric">
                    <span className="label">WhatsApp</span>
                    <span className="value accent">+32%</span>
                    <span className="hint">Canal más activo</span>
                  </div>
                  <div className="side-metric">
                    <span className="label">Web Chat</span>
                    <span className="value">18.4k</span>
                    <span className="hint">Sesiones semanales</span>
                  </div>
                  <div className="side-metric">
                    <span className="label">Instagram DM</span>
                    <span className="value amber">8.9k</span>
                    <span className="hint">Campaña en curso</span>
                  </div>
                  <div className="side-metric">
                    <span className="label">Tasa automatización</span>
                    <span className="value green">87%</span>
                    <span className="hint">Resueltas sin agente</span>
                  </div>
                </div>
              </motion.section>
            </div>

            {/* Fila inferior */}
            <div className="bottom-grid">
              <motion.section
                className="card list-card"
                initial="hidden"
                animate="visible"
                variants={fadeUp}
                custom={0.18}
              >
                <div className="list-header">
                  <h3>Top chatbots</h3>
                  <div className="list-chip">Ordenado por volumen</div>
                </div>

                <ul className="bot-list">
                  <li className="bot-item">
                    <div className="bot-main">
                      <span className="bot-title">Soporte Ecommerce · ES</span>
                      <span className="bot-sub">Web · WhatsApp</span>
                    </div>
                    <div className="bot-kpis">
                      <span>24.5k consultas</span>
                      <span className="trend trend-up">↑ 18% rendimiento</span>
                    </div>
                  </li>
                  <li className="bot-item">
                    <div className="bot-main">
                      <span className="bot-title">Onboarding SaaS · LATAM</span>
                      <span className="bot-sub">Web · Instagram</span>
                    </div>
                    <div className="bot-kpis">
                      <span>11.2k consultas</span>
                      <span className="trend">T. resp. 1.9s</span>
                    </div>
                  </li>
                  <li className="bot-item">
                    <div className="bot-main">
                      <span className="bot-title">Soporte técnico nivel 1</span>
                      <span className="bot-sub">Web · Telegram</span>
                    </div>
                    <div className="bot-kpis">
                      <span>8.7k consultas</span>
                      <span className="trend trend-warn">Derivación 9%</span>
                    </div>
                  </li>
                </ul>
              </motion.section>

              <motion.section
                className="card highlight-card"
                initial="hidden"
                animate="visible"
                variants={fadeUp}
                custom={0.22}
              >
                <div className="highlight-header">
                  <h3>Instant insights</h3>
                  <span className="highlight-chip">Resumen rápido</span>
                </div>

                <div className="highlight-main">
                  <div className="highlight-icon">💬</div>
                  <div className="highlight-copy">
                    <h4>Satisfacción del cliente</h4>
                    <p>
                      94% de valoraciones positivas en las últimas 500
                      interacciones gestionadas por tus chatbots.
                    </p>
                  </div>
                </div>

                <div className="highlight-metrics">
                  <div className="highlight-metric">
                    <span className="label">Tiempo medio respuesta</span>
                    <span className="value">2.1s</span>
                    <span className="hint">Optimizado</span>
                  </div>
                  <div className="highlight-metric">
                    <span className="label">Ahorro estimado</span>
                    <span className="value">$ 586</span>
                    <span className="hint">Mensual en soporte</span>
                  </div>
                </div>
              </motion.section>
            </div>

            <footer className="automai-footer">
              © 2025 AutomAI Solutions · Dashboard de analítica de canales
            </footer>
          </main>
        </div>
      </div>
    </>
  );
}
