<?php
class Conexion
{
    private $host    = "localhost";
    private $usuario = "dwes";        // Cambia por tu usuario de MySQL
    private $clave   = "abc123.";            // Cambia por tu contraseña
    private $bd      = "gymbot";      // Base de datos que estás utilizando

    private $conexion;

    public function __construct()
    {
        try {
            // DSN define host, base de datos y charset
            $dsn = "mysql:host={$this->host};dbname={$this->bd};charset=utf8mb4";

            // Opciones recomendadas para PDO
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_CASE               => PDO::CASE_NATURAL
            ];

            // Crea la conexión PDO
            $this->conexion = new PDO($dsn, $this->usuario, $this->clave, $opciones);
        } catch (PDOException $ex) {
            die("❌ Error al conectar con MySQL (PDO): " . $ex->getMessage());
        }
    }

    public function getConexion()
    {
        return $this->conexion;
    }
}
