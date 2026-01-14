<?php
class EmpleadoPerfil
{
    private $usuario_id;
    private $bio;
    private $especialidad;
    private $activo_en_reservas;

    public function __construct($usuario_id, $bio, $especialidad, $activo_en_reservas)
    {
        $this->usuario_id = $usuario_id;
        $this->bio = $bio;
        $this->especialidad = $especialidad;
        $this->activo_en_reservas = $activo_en_reservas;
    }
    public function getUsuario_id()
    {
        return $this->usuario_id;
    }
    public function getBio()
    {
        return $this->bio;
    }
    public function getEspecialidad()
    {
        return $this->especialidad;
    }
    public function getActivo_en_reservas()
    {
        return $this->activo_en_reservas;
    }
    public function setUsuario_id($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }
    public function setBio($bio)
    {
        $this->bio = $bio;
    }
    public function setEspecialidad($especialidad)
    {
        $this->especialidad = $especialidad;
    }
    public function setActivo_en_reservas($activo_en_reservas)
    {
        $this->activo_en_reservas = $activo_en_reservas;
    }
    public function __toString()
    {
        return "Perfil del empleado {$this->usuario_id}: {$this->especialidad}";
    }
}
