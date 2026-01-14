<?php
class Socio
{
    private $id;
    private $gimnasio_id;
    private $dni;
    private $nombre;
    private $apellidos;
    private $telefono;
    private $email;
    private $password_hash;
    private $estado;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $dni, $nombre, $apellidos, $telefono, $email, $password_hash, $estado, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->estado = $estado;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getGimnasio_id()
    {
        return $this->gimnasio_id;
    }
    public function getDni()
    {
        return $this->dni;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellidos()
    {
        return $this->apellidos;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword_hash()
    {
        return $this->password_hash;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setGimnasio_id($gimnasio_id)
    {
        $this->gimnasio_id = $gimnasio_id;
    }
    public function setDni($dni)
    {
        $this->dni = $dni;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setPassword_hash($password_hash)
    {
        $this->password_hash = $password_hash;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Socio: {$this->nombre} {$this->apellidos} (Estado: {$this->estado})";
    }
}
