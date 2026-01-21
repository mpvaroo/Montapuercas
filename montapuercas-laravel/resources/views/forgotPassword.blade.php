<?php
// gymbot/vistas/auth/forgotPassword.php
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - GymBot</title>
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

        .forgot-container {
            background: rgba(0, 0, 0, 0.6);
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            width: 360px;
            text-align: center;
        }

        .forgot-container h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 26px;
            font-weight: 700;
        }

        .forgot-container p.motivacion {
            font-size: 14px;
            margin-bottom: 20px;
            color: #cccccc;
        }

        .forgot-container input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .forgot-container input:focus {
            outline: none;
            box-shadow: 0 0 5px #9b59b6;
        }

        .forgot-container button {
            width: 100%;
            padding: 12px;
            background-color: #9b59b6;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            margin-top: 15px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .forgot-container button:hover {
            background-color: #824695;
        }
    </style>
</head>

<body>
    <div class="forgot-container">
        <h2>¿Olvidaste tu contraseña?</h2>
        <p class="motivacion">No pierdas ni un día de entrenamiento</p>
        <form action="" method="post">
            <input type="email" name="email" placeholder="Introduce tu correo" required>
            <button type="submit">Enviar enlace de recuperación</button>
        </form>
    </div>
</body>

</html>