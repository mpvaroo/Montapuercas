<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Montapuercas - Automai Gym</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f8b803;
            --primary-dark: #cc9900;
            --bg: #121212;
            --card-bg: #1e1e1e;
            --text: #ffffff;
            --text-dim: #a0a0a0;
        }

        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 600px;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-weight: 300;
            color: var(--text-dim);
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 0.8rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #000;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background-color: rgba(248, 184, 3, 0.1);
            transform: translateY(-2px);
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: var(--text-dim);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.47 4.74-3.35 8.98-7 10.23V11.99H5V6.3l7-3.11v8.8z" />
            </svg>
            Montapuercas
        </div>
        <h1>Tu entrenamiento nivel Automai</h1>

        <div class="actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Entrar al App</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline">Registrarse</a>
                @endif
            @endauth
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Montapuercas Gym. Potenciado por Automai.
        </div>
    </div>
</body>

</html>