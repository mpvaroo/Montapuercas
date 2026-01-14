<?php
// gymbot/vistas/auth/logout.php
// Aquí se podría destruir la sesión y redirigir al login
session_start();
// session_destroy(); // se descomenta cuando se implemente la lógica real
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cerrar Sesión - GymBot</title>
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

        .logout-container {
            text-align: center;
            background: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .logout-container h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: 700;
        }

        .logout-container p {
            margin: 0 0 20px;
            font-size: 16px;
            color: #cccccc;
        }

        .logout-container a {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 25px;
            background: #e74c3c;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .logout-container a:hover {
            background: #c0392b;
        }
    </style>
</head>

<body>
    <div class="logout-container">
        <h2>Sesión cerrada</h2>
        <p>Gracias por mantenerte en forma con GymBot.</p>
        <a href="login.php">Volver a iniciar sesión</a>
    </div>
</body>

</html>