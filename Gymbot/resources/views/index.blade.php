{{--
    Dashboard principal de GymBot
    Por ahora usa valores por defecto, después se integrará con autenticación real
--}}
@php
    // Valores temporales por defecto (después vendrán del controlador)
    $nombre = 'Usuario';
    $apellidos = 'Demo';
    $nombreCompleto = trim($nombre . ' ' . $apellidos);

    $tipoCuenta = 'SOCIO';
    $roles = [];
    $esAdmin = false;

    $hora = (int) date('H');
    $saludo = 'Bienvenido';
    if ($hora >= 6 && $hora <= 12) {
        $saludo = 'Buenos días';
    } elseif ($hora >= 13 && $hora <= 20) {
        $saludo = 'Buenas tardes';
    } else {
        $saludo = 'Buenas noches';
    }

    $frases = [
        'Hoy se entrena aunque sea poco.',
        'Constancia > motivación.',
        'Un paso más, otra semana más fuerte.',
        'Hazlo simple: calienta, trabaja, repite.',
        'Tu futuro yo te lo agradece.',
    ];
    $frase = $frases[array_rand($frases)];
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - GymBot</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>

<body>
    <div class="app">

        {{-- SIDEBAR estilo ChatGPT --}}
        <aside class="sidebar" aria-label="Navegación principal">
            <div class="brand">
                <div class="logo" aria-hidden="true"></div>
                <div>
                    <h1>GymBot</h1>
                    <p>Reservas • Rutinas • IA</p>
                </div>
            </div>

            <nav class="nav">
                <div class="navSectionTitle">Acciones</div>

                <a class="primary" href="{{ route('nueva') }}">
                    <div class="left">
                        <div class="ico" aria-hidden="true">🏋️</div>
                        <div class="label">
                            <strong>Reservar</strong>
                            <span>Nueva clase/servicio</span>
                        </div>
                    </div>
                    <span class="k">R</span>
                </a>

                <a href="{{ route('mis.reservas') }}">
                    <div class="left">
                        <div class="ico" aria-hidden="true">📅</div>
                        <div class="label">
                            <strong>Mis reservas</strong>
                            <span>Agenda y estado</span>
                        </div>
                    </div>
                    <span class="k">M</span>
                </a>

                <div class="navSectionTitle">Entreno</div>

                <a href="{{ route('generar') }}">
                    <div class="left">
                        <div class="ico" aria-hidden="true">⚡</div>
                        <div class="label">
                            <strong>Generar rutina</strong>
                            <span>Por objetivo y nivel</span>
                        </div>
                    </div>
                    <span class="k">G</span>
                </a>

                <a href="{{ route('mis.rutinas') }}">
                    <div class="left">
                        <div class="ico" aria-hidden="true">🗒️</div>
                        <div class="label">
                            <strong>Mis rutinas</strong>
                            <span>Planes guardados</span>
                        </div>
                    </div>
                    <span class="k">P</span>
                </a>

                @if ($esAdmin)
                    <div class="navSectionTitle">Admin</div>

                    <a href="{{ route('usuarios') }}">
                        <div class="left">
                            <div class="ico" aria-hidden="true">🛠️</div>
                            <div class="label">
                                <strong>Configuración</strong>
                                <span>Contenido IA y sistema</span>
                            </div>
                        </div>
                        <span class="k">A</span>
                    </a>
                @endif

                <div class="navSectionTitle">Sesión</div>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <a class="danger" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                        <div class="left">
                            <div class="ico" aria-hidden="true">🚪</div>
                            <div class="label">
                                <strong>Salir</strong>
                                <span>Cerrar sesión</span>
                            </div>
                        </div>
                        <span class="k">⎋</span>
                    </a>
                </form>
            </nav>

            <div class="sideFooter">
                <div class="status" title="Estado del sistema">
                    <span class="dot" aria-hidden="true"></span>
                    <span>Sesión activa</span>
                </div>
                <span style="color: rgba(255,255,255,.55); font-size:12px;">
                    {{ $esAdmin ? 'Admin' : ($tipoCuenta === 'SOCIO' ? 'Socio' : 'Usuario') }}
                </span>
            </div>
        </aside>

        {{-- MAIN --}}
        <main class="main">
            <div class="topRow">
                <div class="hello">
                    <h2>{{ $saludo }}, {{ $nombreCompleto }}</h2>
                    <p>{{ $frase }}</p>
                </div>

                <div class="pill">
                    <span aria-hidden="true">{{ $esAdmin ? '🛠️' : '👤' }}</span>
                    <span>Cuenta:
                        <b>{{ $esAdmin ? 'Administrador' : ($tipoCuenta === 'SOCIO' ? 'Socio' : 'Usuario') }}</b></span>
                </div>
            </div>

            <section class="center">
                {{-- Panel superior tipo "entrada ChatGPT" --}}
                <div class="panel">
                    <div class="panelInner">
                        <div class="panelTitle">
                            <h3>¿Qué quieres hacer hoy?</h3>
                            <p>
                                Reserva una clase, revisa tu agenda o genera una rutina con estética premium y
                                navegación rápida.
                                Todo lo importante, sin cards "infantiles".
                            </p>
                            <div class="hint">
                                <span>Tip</span>
                                <span class="bar"></span>
                                <span>menos ruido, más acción</span>
                            </div>
                        </div>

                        <div class="quick">
                            <a class="btn primary" href="{{ route('nueva') }}">
                                <span aria-hidden="true">🏋️</span> Reservar
                            </a>
                            <a class="btn" href="{{ route('mis.reservas') }}">
                                <span aria-hidden="true">📅</span> Mis reservas
                            </a>
                            <a class="btn" href="{{ route('generar') }}">
                                <span aria-hidden="true">⚡</span> Generar rutina
                            </a>
                            <a class="btn" href="{{ route('mis.rutinas') }}">
                                <span aria-hidden="true">🗒️</span> Mis rutinas
                            </a>
                            @if ($esAdmin)
                                <a class="btn" href="{{ route('usuarios') }}">
                                    <span aria-hidden="true">🛠️</span> Configuración
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tiles principales (limpio pero potente) --}}
                <div class="tiles" aria-label="Accesos rápidos">
                    <a class="tile js-tile" data-delay="60" href="{{ route('nueva') }}">
                        <div class="tileTop">
                            <div class="tileIco" aria-hidden="true">🏋️</div>
                            <div class="tileArrow" aria-hidden="true">›</div>
                        </div>
                        <h4>Reservar clase/servicio</h4>
                        <p>Elige actividad, día y hora. Diseñado para ir rápido, sin fricción.</p>
                    </a>

                    <a class="tile js-tile" data-delay="120" href="{{ route('mis.reservas') }}">
                        <div class="tileTop">
                            <div class="tileIco" aria-hidden="true">📅</div>
                            <div class="tileArrow" aria-hidden="true">›</div>
                        </div>
                        <h4>Mis reservas</h4>
                        <p>Tu agenda, estados y próximas sesiones en una vista clara.</p>
                    </a>

                    <a class="tile js-tile" data-delay="180" href="{{ route('generar') }}">
                        <div class="tileTop">
                            <div class="tileIco" aria-hidden="true">⚡</div>
                            <div class="tileArrow" aria-hidden="true">›</div>
                        </div>
                        <h4>Generar rutina</h4>
                        <p>Pide una rutina por objetivo y nivel. Guardada para ti.</p>
                    </a>

                    <a class="tile js-tile" data-delay="240" href="{{ route('mis.rutinas') }}">
                        <div class="tileTop">
                            <div class="tileIco" aria-hidden="true">🗒️</div>
                            <div class="tileArrow" aria-hidden="true">›</div>
                        </div>
                        <h4>Mis rutinas</h4>
                        <p>Reutiliza tus planes y entrena sin pensarlo demasiado.</p>
                    </a>

                    @if ($esAdmin)
                        <a class="tile js-tile" data-delay="300" href="{{ route('usuarios') }}">
                            <div class="tileTop">
                                <div class="tileIco" aria-hidden="true">🛠️</div>
                                <div class="tileArrow" aria-hidden="true">›</div>
                            </div>
                            <h4>Configuración (Admin)</h4>
                            <p>Contenido del gimnasio para la IA, usuarios y ajustes del sistema.</p>
                        </a>
                    @endif
                </div>
            </section>
        </main>

    </div>

    <script>
        // Entrada escalonada sin librerías
        (function() {
            const tiles = document.querySelectorAll(".js-tile");
            let extra = 0;
            tiles.forEach((t) => {
                const d = parseInt(t.getAttribute("data-delay") || "0", 10);
                t.style.animationDelay = ((d / 1000) + extra).toFixed(2) + "s";
                extra += 0.02;
                requestAnimationFrame(() => t.classList.add("is-in"));
            });
        })();
    </script>
</body>

</html>
