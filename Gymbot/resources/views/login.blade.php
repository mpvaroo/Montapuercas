<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - GymBot</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <h2>Bienvenido a GymBot</h2>
        <p class="motivacion">Potencia tu entrenamiento y alcanza tus metas</p>

        {{-- Mensaje de éxito (por ejemplo, después de logout) --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensaje de error general --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            {{-- Campo Email --}}
            <div>
                <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}"
                    required autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            {{-- Campo Password --}}
            <div>
                <input type="password" name="password" placeholder="Contraseña" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <div class="enlace">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a>
        </div>
    </div>
</body>

</html>
