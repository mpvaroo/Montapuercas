<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutomAI Gym — AI Routines & Tracking</title>
    <link rel="stylesheet" href="{{ asset('css/fitness-editorial.css') }}?v=3">
</head>

<body>

    @include('components.fitness-navbar')

    <main class="main">
        <div class="main-wrapper">

            <section class="hero" aria-label="Hero Section">
                <h1>
                    AI ROUTINES <br>
                    <span class="accent">TRACK</span> · RANGES
                </h1>
                <p class="subtitle">
                    Genera rutinas con IA, registra tus entrenos y mide rangos por músculo.
                    Minimal y premium.
                </p>
            </section>

            <div class="dashboard-grid">
                <!-- Left Column -->
                <div class="column-left">

                    <section class="card" aria-labelledby="routines-title">
                        <div class="card-title" id="routines-title">
                            Tus Rutinas
                            <button class="btn btn-outline btn-arrow" style="padding: 6px 12px;">Ver todas ↘</button>
                        </div>

                        <div class="routine-list">
                            <div class="routine-item">
                                <div class="routine-info">
                                    <h4>Torso / Pierna</h4>
                                    <span>4 días · Hipertrofia</span>
                                </div>
                                <span class="btn-arrow">↘</span>
                            </div>

                            <div class="routine-item">
                                <div class="routine-info">
                                    <h4>Full Body Minimal</h4>
                                    <span>3 días · Fuerza base</span>
                                </div>
                                <span class="btn-arrow">↘</span>
                            </div>

                            <div class="routine-item">
                                <div class="routine-info">
                                    <h4>Pierna Rendimiento</h4>
                                    <span>2 días · Potencia</span>
                                </div>
                                <span class="btn-arrow">↘</span>
                            </div>
                        </div>

                        <div class="muscle-map">
                            <div class="muscle-bar">
                                <div class="muscle-label">Pecho <span>70%</span></div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: 70%"></div>
                                </div>
                            </div>
                            <div class="muscle-bar">
                                <div class="muscle-label">Espalda <span>85%</span></div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: 85%"></div>
                                </div>
                            </div>
                            <div class="muscle-bar">
                                <div class="muscle-label">Pierna <span>60%</span></div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: 60%"></div>
                                </div>
                            </div>
                            <div class="muscle-bar">
                                <div class="muscle-label">Hombro <span>50%</span></div>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: 50%"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="card" style="margin-top: 24px;" aria-labelledby="logs-title">
                        <div class="card-title" id="logs-title">
                            Últimos Registros
                        </div>

                        <div class="logs-list">
                            <div class="log-item">
                                <span class="log-date">Hoy</span>
                                <span class="log-session">Torso B (Hiper)</span>
                                <span class="log-score">9.2</span>
                            </div>
                            <div class="log-item">
                                <span class="log-date">19 Ene</span>
                                <span class="log-session">Pierna A (Fuerza)</span>
                                <span class="log-score">8.8</span>
                            </div>
                            <div class="log-item">
                                <span class="log-date">17 Ene</span>
                                <span class="log-session">Full Body</span>
                                <span class="log-score">7.5</span>
                            </div>
                        </div>
                    </section>

                </div>

                <!-- Right Column -->
                <div class="column-right">

                    <section class="chat-container" aria-label="AI Coach Chat">
                        <div class="chat-messages" id="chatMessages">
                            <div class="message ai">
                                Hola. Soy tu coach de IA. ¿En qué te ayudo hoy?
                                Puedo generar rutinas, analizar tu volumen o sugerir progresiones.
                            </div>

                            <div class="message user">
                                Quiero una rutina de 4 días enfocada en espalda y hombro lateral.
                            </div>

                            <div class="message ai">
                                Entendido. Aquí tienes una estructura Torso/Pierna modificada con énfasis en deltoides
                                lateral y amplitud dorsal. La frecuencia es 2x semana.
                            </div>
                        </div>

                        <div class="chat-input-area">
                            <div class="chat-chips">
                                <button class="chip" onclick="fillInput('Crear rutina de fuerza 3 días')">Rutina
                                    Fuerza</button>
                                <button class="chip" onclick="fillInput('Registrar entreno de hoy')">Log
                                    Training</button>
                                <button class="chip" onclick="fillInput('¿Cómo va mi volumen de pecho?')">Volumen
                                    Pecho</button>
                            </div>

                            <div class="input-wrapper">
                                <textarea class="chat-input" id="chatInput" placeholder="Escribe aquí para empezar la conversación con la IA…"></textarea>
                                <button class="send-btn" aria-label="Enviar mensaje" type="button">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </section>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 24px;">
                        <section class="card" style="padding: 20px;">
                            <span
                                style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">
                                Semana Actual
                            </span>
                            <div style="font-size: 2rem; font-weight: 800; margin-top: 8px;">12.5k</div>
                            <div style="font-size: 0.8rem; color: var(--accent); margin-top: 4px;">Volumen Total (kg)
                            </div>
                        </section>

                        <section class="card" style="padding: 20px;">
                            <span
                                style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em;">
                                Readiness
                            </span>
                            <div style="font-size: 2rem; font-weight: 800; margin-top: 8px;">high</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Recuperación
                                óptima</div>
                        </section>
                    </div>

                </div>
            </div>

        </div>
    </main>

    @include('components.fitness-footer')

    <script>
        function fillInput(text) {
            const input = document.getElementById('chatInput');
            input.value = text;
            input.focus();
        }
    </script>

</body>

</html>
