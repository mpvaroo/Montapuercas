<?php
// gymbot/vistas/auth/register.php
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario - GymBot</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../../assets/gym_background.png') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            color: #fff;
        }

        .register-container {
            background: rgba(0, 0, 0, 0.6);
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            width: 420px;
            text-align: center;
        }

        .register-container h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 26px;
            font-weight: 700;
        }

        .register-container p.motivacion {
            font-size: 14px;
            margin-bottom: 20px;
            color: #cccccc;
        }

        .register-container input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .register-container input:focus {
            outline: none;
            box-shadow: 0 0 5px #27ae60;
        }

        .register-container button {
            width: 100%;
            padding: 12px;
            background-color: #27ae60;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            margin-top: 15px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .register-container button:hover {
            background-color: #1e8449;
        }

        .register-container .enlace {
            margin-top: 15px;
            font-size: 14px;
        }

        .register-container .enlace a {
            color: #27ae60;
            text-decoration: none;
        }

        .register-container .enlace a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Únete a GymBot</h2>
        <p class="motivacion">Comienza tu viaje fitness con nosotros</p>
        <form action="" method="post">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="text" name="dni" placeholder="DNI" required>
            <input type="tel" name="telefono" placeholder="Teléfono" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Repetir contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
        <div class="enlace">
            ¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a>
        </div>
    </div>
</body>

</html>