<?php
declare(strict_types=1);
session_start();

class LogoutPage
{
    public static function doLogout(): void
    {
        // Vaciar variables de sesión
        $_SESSION = [];

        // Borrar cookie de sesión (si existe)
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'] ?? '/',
                $params['domain'] ?? '',
                (bool)($params['secure'] ?? false),
                (bool)($params['httponly'] ?? true)
            );
        }

        // Destruir sesión
        session_destroy();
    }

    public static function render(): void
    {
        // Logout REAL antes de mostrar nada
        self::doLogout();
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cerrando sesión | AutomAI Solutions</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    background: linear-gradient(135deg, #0077b6, #00b4d8);
                    color: white;
                    font-family: 'Segoe UI', sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    text-align: center;
                    overflow: hidden;
                    margin: 0;
                }
                .logout-box {
                    background: rgba(255,255,255,0.1);
                    backdrop-filter: blur(10px);
                    border-radius: 16px;
                    padding: 3rem 4rem;
                    box-shadow: 0 4px 30px rgba(0,0,0,0.2);
                    animation: fadeIn 0.8s ease;
                    max-width: 520px;
                    width: calc(100% - 32px);
                }
                h1 {
                    font-size: 1.8rem;
                    margin-bottom: 1rem;
                    font-weight: 600;
                }
                p {
                    font-size: 1rem;
                    color: #e0f7ff;
                    margin: 0;
                }
                .spinner-border {
                    width: 3rem;
                    height: 3rem;
                    color: #fff;
                    margin: 1.5rem 0;
                }
                @keyframes fadeIn {
                    from {opacity: 0; transform: scale(0.9);}
                    to {opacity: 1; transform: scale(1);}
                }
                footer {
                    position: absolute;
                    bottom: 15px;
                    width: 100%;
                    text-align: center;
                    font-size: 0.9rem;
                    color: rgba(255,255,255,0.7);
                    padding: 0 12px;
                }
            </style>
        </head>
        <body>
            <div class="logout-box">
                <div class="mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/595/595067.png" alt="logout icon" width="64" height="64">
                </div>
                <h1>Cerrando sesión...</h1>
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p>
                    Gracias por usar <strong>AutomAI Solutions</strong>.<br>
                    Serás redirigido al inicio de sesión en unos segundos.
                </p>
            </div>

            <footer>© 2025 AutomAI Solutions · Todos los derechos reservados</footer>

            <script>
                // Redirigir tras 4 segundos al login
                setTimeout(() => {
                    window.location.href = "Login.php";
                }, 4000);
            </script>
        </body>
        </html>
        <?php
    }
}

LogoutPage::render();
