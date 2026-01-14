<?php
class Usuario
{
    private $id;
    private $gimnasio_id;
    private $nombre;
    private $apellidos;
    private $email;
    private $password_hash;
    private $estado;
    private $ultimo_login;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $nombre, $apellidos, $email, $password_hash, $estado, $ultimo_login, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->estado = $estado;
        $this->ultimo_login = $ultimo_login;
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
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellidos()
    {
        return $this->apellidos;
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
    public function getUltimo_login()
    {
        return $this->ultimo_login;
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
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
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
    public function setUltimo_login($ultimo_login)
    {
        $this->ultimo_login = $ultimo_login;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Usuario: {$this->nombre} {$this->apellidos} ({$this->email})";
    }
}
