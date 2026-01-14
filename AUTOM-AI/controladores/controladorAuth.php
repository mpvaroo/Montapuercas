<?php

declare(strict_types=1);

require_once __DIR__ . "/../controladores/Conexion.php";

class controladorAuth
{
    public static function login(): array
    {
        $errores = [];

        try {
            // ========================
            // VALIDACIONES BÁSICAS
            // ========================
            if (!isset($_POST["email"]) || trim((string)$_POST["email"]) === "") {
                $errores["email"] = "El email es obligatorio";
            }

            if (!isset($_POST["pass"]) || trim((string)$_POST["pass"]) === "") {
                $errores["pass"] = "La contraseña es obligatoria";
            }

            if (!empty($errores)) {
                return $errores;
            }

            $email = trim((string)$_POST["email"]);
            $pass  = (string)$_POST["pass"];

            // ========================
            // BD: BUSCAR USUARIO
            // ========================
            $bd = (new Conexion())->getConexion();

            $sql = "SELECT id, empresa_id, email, password_hash, estado, intentos_fallidos, bloqueado_hasta
                    FROM usuario
                    WHERE email = ?
                    LIMIT 1";
            $st = $bd->prepare($sql);
            $st->execute([$email]);
            $u = $st->fetch(PDO::FETCH_ASSOC);

            // Mensaje genérico para no revelar si existe
            if (!$u) {
                $errores["general"] = "Email o contraseña incorrectos";
                return $errores;
            }

            if ((string)$u["estado"] !== "ACTIVO") {
                $errores["general"] = "Tu cuenta no está activa";
                return $errores;
            }

            // ========================
            // BLOQUEO POR TIEMPO
            // ========================
            if (!empty($u["bloqueado_hasta"])) {
                $bh = strtotime((string)$u["bloqueado_hasta"]);
                if ($bh !== false && $bh > time()) {
                    $mins = (int)ceil(($bh - time()) / 60);
                    $errores["general"] = "Cuenta bloqueada. Intenta de nuevo en $mins minuto(s).";
                    return $errores;
                }
            }

            // ========================
            // COMPROBAR PASSWORD (BCRYPT)
            // ========================
            if (password_verify($pass, (string)$u["password_hash"])) {

                // Login OK -> reset contadores/bloqueo
                $up = $bd->prepare("UPDATE usuario
                                    SET intentos_fallidos = 0,
                                        bloqueado_hasta = NULL
                                    WHERE id = ?");
                $up->execute([(int)$u["id"]]);

                // Sesión
                $_SESSION["usuario_id"] = (int)$u["id"];
                $_SESSION["empresa_id"] = (int)$u["empresa_id"];
                $_SESSION["email"]      = (string)$u["email"];

                return []; // OK
            }

            // ========================
            // PASSWORD INCORRECTA -> sumar intento y bloquear si llega a 3
            // ========================
            $intentos = (int)$u["intentos_fallidos"] + 1;

            if ($intentos >= 3) {
                $lock = $bd->prepare("UPDATE usuario
                                      SET intentos_fallidos = 0,
                                          bloqueado_hasta = DATE_ADD(NOW(), INTERVAL 10 MINUTE)
                                      WHERE id = ?");
                $lock->execute([(int)$u["id"]]);

                $errores["general"] = "Demasiados intentos. Cuenta bloqueada 10 minutos.";
                return $errores;
            }

            $up2 = $bd->prepare("UPDATE usuario
                                 SET intentos_fallidos = ?
                                 WHERE id = ?");
            $up2->execute([$intentos, (int)$u["id"]]);

            $restantes = 3 - $intentos;
            $errores["general"] = "Email o contraseña incorrectos. Te quedan $restantes intento(s).";
            return $errores;
        } catch (Throwable $e) {
            $errores["general"] = "Error en el login.";
            return $errores;
        }
    }

    public static function registrar(): array
    {
        $errores = [];

        try {
            $company  = trim((string)($_POST["company"] ?? ""));
            $email    = trim((string)($_POST["email"] ?? ""));
            $phone    = trim((string)($_POST["phone"] ?? ""));
            $sectorId = (int)($_POST["sector_id"] ?? 0);
            $planId   = (int)($_POST["plan_id"] ?? 0);
            $pass     = (string)($_POST["pass"] ?? "");

            // Validaciones básicas
            if ($company === "" || mb_strlen($company) < 2) {
                $errores[] = "Nombre de empresa inválido.";
            }

            if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = "Correo electrónico inválido.";
            }

            if ($pass === "" || strlen($pass) < 8) {
                $errores[] = "La contraseña debe tener al menos 8 caracteres.";
            }

            if ($sectorId <= 0) {
                $errores[] = "Debes seleccionar un sector.";
            }

            if ($planId <= 0) {
                $errores[] = "Debes seleccionar un plan.";
            }

            if (!empty($errores)) {
                return $errores;
            }

            $conexion = new Conexion();
            $bd = $conexion->getConexion();

            // Validar que sector existe y está activo
            $stmt = $bd->prepare("SELECT COUNT(*) FROM sector WHERE id = ? AND activo = 1");
            $stmt->execute([$sectorId]);
            if ((int)$stmt->fetchColumn() === 0) {
                $errores[] = "Sector no válido.";
                return $errores;
            }

            // Validar que plan existe y está activo
            $stmt = $bd->prepare("SELECT COUNT(*) FROM plan WHERE id = ? AND activo = 1");
            $stmt->execute([$planId]);
            if ((int)$stmt->fetchColumn() === 0) {
                $errores[] = "Plan no válido.";
                return $errores;
            }

            // Email duplicado
            $stmt = $bd->prepare("SELECT id FROM usuario WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $errores[] = "Ya existe un usuario con ese email.";
                return $errores;
            }

            // Insert empresa + usuario (transacción)
            try {
                $bd->beginTransaction();

                // Empresa
                $stmt = $bd->prepare("
                INSERT INTO empresa (nombre, email_contacto, telefono, sector_id, plan_id, estado)
                VALUES (?, ?, ?, ?, ?, 'ACTIVA')
            ");
                $stmt->execute([
                    $company,
                    $email,
                    ($phone !== "" ? $phone : null),
                    $sectorId,
                    $planId
                ]);

                $empresaId = (int)$bd->lastInsertId();

                // Usuario inicial
                $passwordHash = password_hash($pass, PASSWORD_BCRYPT);

                // OJO: si luego eliminas email_verificado y ultimo_login, aquí no pasa nada porque no los usamos
                $stmt = $bd->prepare("
                INSERT INTO usuario (empresa_id, email, password_hash, estado)
                VALUES (?, ?, ?, 'ACTIVO')
            ");
                $stmt->execute([$empresaId, $email, $passwordHash]);

                $bd->commit();

                header("Location: login.php?ok=1");
                exit;
            } catch (Throwable $e) {
                if ($bd->inTransaction()) {
                    $bd->rollBack();
                }
                $errores[] = "No se pudo completar el registro. Intenta de nuevo.";
                return $errores;
            }
        } catch (Throwable $e) {
            $errores[] = "Error en el registro.";
            return $errores;
        }
    }

    public static function forgotPassword(): array
    {
        $res = [
            "ok" => false,
            "err" => false,
            "mensaje" => ""
        ];

        try {
            $email = trim((string)($_POST["email"] ?? ""));

            if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $res["err"] = true;
                $res["mensaje"] = "Introduce un correo válido.";
                return $res;
            }

            // Por privacidad: siempre OK
            $res["ok"] = true;

            $bd = (new Conexion())->getConexion();

            // Buscar usuario ACTIVO
            $stmt = $bd->prepare("SELECT id FROM usuario WHERE email = ? AND estado = 'ACTIVO' LIMIT 1");
            $stmt->execute([$email]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$u) {
                // No existe -> devolvemos ok igualmente
                return $res;
            }

            $usuarioId = (int)$u["id"];

            // Token real
            $token = bin2hex(random_bytes(32)); // 64 hex
            $tokenHash = hash("sha256", $token);

            // Guardar en BD (expira en 30 min)
            $ins = $bd->prepare("
            INSERT INTO password_reset (usuario_id, token_hash, expira_en, usado)
            VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE), 0)
        ");
            $ins->execute([$usuarioId, $tokenHash]);

            // Link absoluto (recomendado)
            // Ajusta BASE_URL a tu proyecto
            $baseUrl = "http://localhost/tu_proyecto/auth"; // <-- CAMBIA ESTO
            $link = $baseUrl . "/resetpassword.php?token=" . rawurlencode($token);

            // Enviar email real
            self::enviarEmailReset($email, $link);

            return $res;
        } catch (Throwable $e) {
            // Importante: por privacidad, incluso si falla, puedes devolver OK
            // Pero en desarrollo es útil avisar algo genérico
            $res["ok"] = true;
            return $res;
        }
    }

    private static function enviarEmailReset(string $destino, string $link): void
    {
        // Requiere composer autoload
        require_once __DIR__ . "/../vendor/autoload.php";

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        // IMPORTANTE: para Gmail usa "App Password" (no tu contraseña normal)
        $gmailUser = "TUCUENTA@gmail.com";
        $gmailAppPassword = "TU_APP_PASSWORD_AQUI";

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = $gmailUser;
        $mail->Password = $gmailAppPassword;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($gmailUser, "AutomAI Solutions");
        $mail->addAddress($destino);

        $mail->isHTML(true);
        $mail->Subject = "Restablecer contraseña - AutomAI";
        $mail->Body = "
        <div style='font-family:Arial,sans-serif'>
            <h2>Restablecer contraseña</h2>
            <p>Has solicitado restablecer tu contraseña. Pulsa aquí:</p>
            <p><a href='{$link}'>{$link}</a></p>
            <p>Este enlace expira en 30 minutos.</p>
        </div>
    ";

        $mail->AltBody = "Restablecer contraseña: " . $link . " (expira en 30 minutos)";

        $mail->send();
    }
}
