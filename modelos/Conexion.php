<?php

class Conexion
{
    private string $host   = "localhost";
    private string $usuario = "dwes";
    private string $clave   = "abc123.";
    private string $bd      = "automai_mvp";

    private PDO $conexion;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->bd};charset=utf8mb4";

            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_CASE               => PDO::CASE_NATURAL,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            $this->conexion = new PDO($dsn, $this->usuario, $this->clave, $opciones);
        } catch (PDOException $ex) {
            die("❌ Error al conectar con MySQL (PDO): " . $ex->getMessage());
        }
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }
}
