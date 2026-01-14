<?php

/**
 * Este archivo contiene las clases de modelo para el proyecto GymBot.
 * Cada clase representa una tabla de la base de datos simplificada y
 * sigue el esquema solicitado: propiedades privadas, constructor,
 * getters, setters y método __toString().
 */

class Gimnasio
{
    private $id;
    private $nombre;
    private $email_contacto;
    private $telefono;
    private $direccion;
    private $estado;
    private $creado_en;

    public function __construct($id, $nombre, $email_contacto, $telefono, $direccion, $estado, $creado_en)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email_contacto = $email_contacto;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->estado = $estado;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getEmail_contacto()
    {
        return $this->email_contacto;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function getDireccion()
    {
        return $this->direccion;
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
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setEmail_contacto($email_contacto)
    {
        $this->email_contacto = $email_contacto;
    }
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
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
        return "Gimnasio: {$this->nombre} ({$this->estado})";
    }
}

class GimnasioConfig
{
    private $gimnasio_id;
    private $zona_horaria;
    private $idioma;
    private $lunes_inicio;
    private $lunes_fin;
    private $martes_inicio;
    private $martes_fin;
    private $miercoles_inicio;
    private $miercoles_fin;
    private $jueves_inicio;
    private $jueves_fin;
    private $viernes_inicio;
    private $viernes_fin;
    private $sabado_inicio;
    private $sabado_fin;
    private $domingo_inicio;
    private $domingo_fin;
    private $aforo_default;
    private $max_reservas_por_dia;
    private $export_ruta;
    private $actualizado_en;

    public function __construct(
        $gimnasio_id,
        $zona_horaria,
        $idioma,
        $lunes_inicio,
        $lunes_fin,
        $martes_inicio,
        $martes_fin,
        $miercoles_inicio,
        $miercoles_fin,
        $jueves_inicio,
        $jueves_fin,
        $viernes_inicio,
        $viernes_fin,
        $sabado_inicio,
        $sabado_fin,
        $domingo_inicio,
        $domingo_fin,
        $aforo_default,
        $max_reservas_por_dia,
        $export_ruta,
        $actualizado_en
    ) {
        $this->gimnasio_id = $gimnasio_id;
        $this->zona_horaria = $zona_horaria;
        $this->idioma = $idioma;
        $this->lunes_inicio = $lunes_inicio;
        $this->lunes_fin = $lunes_fin;
        $this->martes_inicio = $martes_inicio;
        $this->martes_fin = $martes_fin;
        $this->miercoles_inicio = $miercoles_inicio;
        $this->miercoles_fin = $miercoles_fin;
        $this->jueves_inicio = $jueves_inicio;
        $this->jueves_fin = $jueves_fin;
        $this->viernes_inicio = $viernes_inicio;
        $this->viernes_fin = $viernes_fin;
        $this->sabado_inicio = $sabado_inicio;
        $this->sabado_fin = $sabado_fin;
        $this->domingo_inicio = $domingo_inicio;
        $this->domingo_fin = $domingo_fin;
        $this->aforo_default = $aforo_default;
        $this->max_reservas_por_dia = $max_reservas_por_dia;
        $this->export_ruta = $export_ruta;
        $this->actualizado_en = $actualizado_en;
    }
    public function getGimnasio_id()
    {
        return $this->gimnasio_id;
    }
    public function getZona_horaria()
    {
        return $this->zona_horaria;
    }
    public function getIdioma()
    {
        return $this->idioma;
    }
    public function getLunes_inicio()
    {
        return $this->lunes_inicio;
    }
    public function getLunes_fin()
    {
        return $this->lunes_fin;
    }
    public function getMartes_inicio()
    {
        return $this->martes_inicio;
    }
    public function getMartes_fin()
    {
        return $this->martes_fin;
    }
    public function getMiercoles_inicio()
    {
        return $this->miercoles_inicio;
    }
    public function getMiercoles_fin()
    {
        return $this->miercoles_fin;
    }
    public function getJueves_inicio()
    {
        return $this->jueves_inicio;
    }
    public function getJueves_fin()
    {
        return $this->jueves_fin;
    }
    public function getViernes_inicio()
    {
        return $this->viernes_inicio;
    }
    public function getViernes_fin()
    {
        return $this->viernes_fin;
    }
    public function getSabado_inicio()
    {
        return $this->sabado_inicio;
    }
    public function getSabado_fin()
    {
        return $this->sabado_fin;
    }
    public function getDomingo_inicio()
    {
        return $this->domingo_inicio;
    }
    public function getDomingo_fin()
    {
        return $this->domingo_fin;
    }
    public function getAforo_default()
    {
        return $this->aforo_default;
    }
    public function getMax_reservas_por_dia()
    {
        return $this->max_reservas_por_dia;
    }
    public function getExport_ruta()
    {
        return $this->export_ruta;
    }
    public function getActualizado_en()
    {
        return $this->actualizado_en;
    }
    public function setGimnasio_id($gimnasio_id)
    {
        $this->gimnasio_id = $gimnasio_id;
    }
    public function setZona_horaria($zona_horaria)
    {
        $this->zona_horaria = $zona_horaria;
    }
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
    }
    public function setLunes_inicio($lunes_inicio)
    {
        $this->lunes_inicio = $lunes_inicio;
    }
    public function setLunes_fin($lunes_fin)
    {
        $this->lunes_fin = $lunes_fin;
    }
    public function setMartes_inicio($martes_inicio)
    {
        $this->martes_inicio = $martes_inicio;
    }
    public function setMartes_fin($martes_fin)
    {
        $this->martes_fin = $martes_fin;
    }
    public function setMiercoles_inicio($miercoles_inicio)
    {
        $this->miercoles_inicio = $miercoles_inicio;
    }
    public function setMiercoles_fin($miercoles_fin)
    {
        $this->miercoles_fin = $miercoles_fin;
    }
    public function setJueves_inicio($jueves_inicio)
    {
        $this->jueves_inicio = $jueves_inicio;
    }
    public function setJueves_fin($jueves_fin)
    {
        $this->jueves_fin = $jueves_fin;
    }
    public function setViernes_inicio($viernes_inicio)
    {
        $this->viernes_inicio = $viernes_inicio;
    }
    public function setViernes_fin($viernes_fin)
    {
        $this->viernes_fin = $viernes_fin;
    }
    public function setSabado_inicio($sabado_inicio)
    {
        $this->sabado_inicio = $sabado_inicio;
    }
    public function setSabado_fin($sabado_fin)
    {
        $this->sabado_fin = $sabado_fin;
    }
    public function setDomingo_inicio($domingo_inicio)
    {
        $this->domingo_inicio = $domingo_inicio;
    }
    public function setDomingo_fin($domingo_fin)
    {
        $this->domingo_fin = $domingo_fin;
    }
    public function setAforo_default($aforo_default)
    {
        $this->aforo_default = $aforo_default;
    }
    public function setMax_reservas_por_dia($max_reservas_por_dia)
    {
        $this->max_reservas_por_dia = $max_reservas_por_dia;
    }
    public function setExport_ruta($export_ruta)
    {
        $this->export_ruta = $export_ruta;
    }
    public function setActualizado_en($actualizado_en)
    {
        $this->actualizado_en = $actualizado_en;
    }
    public function __toString()
    {
        return "Config gimnasio {$this->gimnasio_id}: zona {$this->zona_horaria}, idioma {$this->idioma}";
    }
}

class Rol
{
    private $id;
    private $nombre;

    public function __construct($id, $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function __toString()
    {
        return "Rol: {$this->nombre}";
    }
}

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

class Actividad
{
    private $id;
    private $gimnasio_id;
    private $nombre;
    private $descripcion;
    private $duracion_min;
    private $aforo;
    private $activa;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $nombre, $descripcion, $duracion_min, $aforo, $activa, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->duracion_min = $duracion_min;
        $this->aforo = $aforo;
        $this->activa = $activa;
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
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getDuracion_min()
    {
        return $this->duracion_min;
    }
    public function getAforo()
    {
        return $this->aforo;
    }
    public function getActiva()
    {
        return $this->activa;
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
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setDuracion_min($duracion_min)
    {
        $this->duracion_min = $duracion_min;
    }
    public function setAforo($aforo)
    {
        $this->aforo = $aforo;
    }
    public function setActiva($activa)
    {
        $this->activa = $activa;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Actividad: {$this->nombre} ({$this->duracion_min} min)";
    }
}

class Servicio
{
    private $id;
    private $gimnasio_id;
    private $nombre;
    private $descripcion;
    private $duracion_min;
    private $precio;
    private $activo;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $nombre, $descripcion, $duracion_min, $precio, $activo, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->duracion_min = $duracion_min;
        $this->precio = $precio;
        $this->activo = $activo;
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
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getDuracion_min()
    {
        return $this->duracion_min;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function getActivo()
    {
        return $this->activo;
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
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setDuracion_min($duracion_min)
    {
        $this->duracion_min = $duracion_min;
    }
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Servicio: {$this->nombre} ({$this->duracion_min} min, Precio: {$this->precio})";
    }
}

class ActividadHorario
{
    private $id;
    private $gimnasio_id;
    private $actividad_id;
    private $monitor_usuario_id;
    private $dia_semana;
    private $hora_inicio;
    private $duracion_min;
    private $aforo;
    private $activo;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $actividad_id, $monitor_usuario_id, $dia_semana, $hora_inicio, $duracion_min, $aforo, $activo, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->actividad_id = $actividad_id;
        $this->monitor_usuario_id = $monitor_usuario_id;
        $this->dia_semana = $dia_semana;
        $this->hora_inicio = $hora_inicio;
        $this->duracion_min = $duracion_min;
        $this->aforo = $aforo;
        $this->activo = $activo;
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
    public function getActividad_id()
    {
        return $this->actividad_id;
    }
    public function getMonitor_usuario_id()
    {
        return $this->monitor_usuario_id;
    }
    public function getDia_semana()
    {
        return $this->dia_semana;
    }
    public function getHora_inicio()
    {
        return $this->hora_inicio;
    }
    public function getDuracion_min()
    {
        return $this->duracion_min;
    }
    public function getAforo()
    {
        return $this->aforo;
    }
    public function getActivo()
    {
        return $this->activo;
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
    public function setActividad_id($actividad_id)
    {
        $this->actividad_id = $actividad_id;
    }
    public function setMonitor_usuario_id($monitor_usuario_id)
    {
        $this->monitor_usuario_id = $monitor_usuario_id;
    }
    public function setDia_semana($dia_semana)
    {
        $this->dia_semana = $dia_semana;
    }
    public function setHora_inicio($hora_inicio)
    {
        $this->hora_inicio = $hora_inicio;
    }
    public function setDuracion_min($duracion_min)
    {
        $this->duracion_min = $duracion_min;
    }
    public function setAforo($aforo)
    {
        $this->aforo = $aforo;
    }
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Horario {$this->id}: día {$this->dia_semana}, hora {$this->hora_inicio}, actividad {$this->actividad_id}";
    }
}

class ChatSesion
{
    private $id;
    private $gimnasio_id;
    private $socio_id;
    private $canal;
    private $estado;
    private $creado_en;
    private $actualizado_en;

    public function __construct($id, $gimnasio_id, $socio_id, $canal, $estado, $creado_en, $actualizado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->socio_id = $socio_id;
        $this->canal = $canal;
        $this->estado = $estado;
        $this->creado_en = $creado_en;
        $this->actualizado_en = $actualizado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getGimnasio_id()
    {
        return $this->gimnasio_id;
    }
    public function getSocio_id()
    {
        return $this->socio_id;
    }
    public function getCanal()
    {
        return $this->canal;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function getActualizado_en()
    {
        return $this->actualizado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setGimnasio_id($gimnasio_id)
    {
        $this->gimnasio_id = $gimnasio_id;
    }
    public function setSocio_id($socio_id)
    {
        $this->socio_id = $socio_id;
    }
    public function setCanal($canal)
    {
        $this->canal = $canal;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function setActualizado_en($actualizado_en)
    {
        $this->actualizado_en = $actualizado_en;
    }
    public function __toString()
    {
        return "Chat sesión {$this->id} (canal {$this->canal})";
    }
}

class ChatMensaje
{
    private $id;
    private $sesion_id;
    private $rol;
    private $mensaje;
    private $creado_en;

    public function __construct($id, $sesion_id, $rol, $mensaje, $creado_en)
    {
        $this->id = $id;
        $this->sesion_id = $sesion_id;
        $this->rol = $rol;
        $this->mensaje = $mensaje;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getSesion_id()
    {
        return $this->sesion_id;
    }
    public function getRol()
    {
        return $this->rol;
    }
    public function getMensaje()
    {
        return $this->mensaje;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setSesion_id($sesion_id)
    {
        $this->sesion_id = $sesion_id;
    }
    public function setRol($rol)
    {
        $this->rol = $rol;
    }
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Mensaje {$this->id}: {$this->rol} dice \"{$this->mensaje}\"";
    }
}

class IAExtraccion
{
    private $id;
    private $gimnasio_id;
    private $socio_id;
    private $sesion_id;
    private $tipo;
    private $texto_usuario;
    private $json_resultado;
    private $confianza_pct;
    private $estado;
    private $errores;
    private $creado_en;

    public function __construct(
        $id,
        $gimnasio_id,
        $socio_id,
        $sesion_id,
        $tipo,
        $texto_usuario,
        $json_resultado,
        $confianza_pct,
        $estado,
        $errores,
        $creado_en
    ) {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->socio_id = $socio_id;
        $this->sesion_id = $sesion_id;
        $this->tipo = $tipo;
        $this->texto_usuario = $texto_usuario;
        $this->json_resultado = $json_resultado;
        $this->confianza_pct = $confianza_pct;
        $this->estado = $estado;
        $this->errores = $errores;
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
    public function getSocio_id()
    {
        return $this->socio_id;
    }
    public function getSesion_id()
    {
        return $this->sesion_id;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getTexto_usuario()
    {
        return $this->texto_usuario;
    }
    public function getJson_resultado()
    {
        return $this->json_resultado;
    }
    public function getConfianza_pct()
    {
        return $this->confianza_pct;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getErrores()
    {
        return $this->errores;
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
    public function setSocio_id($socio_id)
    {
        $this->socio_id = $socio_id;
    }
    public function setSesion_id($sesion_id)
    {
        $this->sesion_id = $sesion_id;
    }
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    public function setTexto_usuario($texto_usuario)
    {
        $this->texto_usuario = $texto_usuario;
    }
    public function setJson_resultado($json_resultado)
    {
        $this->json_resultado = $json_resultado;
    }
    public function setConfianza_pct($confianza_pct)
    {
        $this->confianza_pct = $confianza_pct;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function setErrores($errores)
    {
        $this->errores = $errores;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Extracción {$this->id}: tipo {$this->tipo} con confianza {$this->confianza_pct}%";
    }
}

class Reserva
{
    private $id;
    private $gimnasio_id;
    private $socio_id;
    private $tipo;
    private $actividad_horario_id;
    private $servicio_id;
    private $monitor_usuario_id;
    private $fecha;
    private $hora;
    private $duracion_min;
    private $estado;
    private $creada_por;
    private $notas;
    private $creado_en;
    private $actualizado_en;

    public function __construct(
        $id,
        $gimnasio_id,
        $socio_id,
        $tipo,
        $actividad_horario_id,
        $servicio_id,
        $monitor_usuario_id,
        $fecha,
        $hora,
        $duracion_min,
        $estado,
        $creada_por,
        $notas,
        $creado_en,
        $actualizado_en
    ) {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->socio_id = $socio_id;
        $this->tipo = $tipo;
        $this->actividad_horario_id = $actividad_horario_id;
        $this->servicio_id = $servicio_id;
        $this->monitor_usuario_id = $monitor_usuario_id;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->duracion_min = $duracion_min;
        $this->estado = $estado;
        $this->creada_por = $creada_por;
        $this->notas = $notas;
        $this->creado_en = $creado_en;
        $this->actualizado_en = $actualizado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getGimnasio_id()
    {
        return $this->gimnasio_id;
    }
    public function getSocio_id()
    {
        return $this->socio_id;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getActividad_horario_id()
    {
        return $this->actividad_horario_id;
    }
    public function getServicio_id()
    {
        return $this->servicio_id;
    }
    public function getMonitor_usuario_id()
    {
        return $this->monitor_usuario_id;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getHora()
    {
        return $this->hora;
    }
    public function getDuracion_min()
    {
        return $this->duracion_min;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getCreada_por()
    {
        return $this->creada_por;
    }
    public function getNotas()
    {
        return $this->notas;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function getActualizado_en()
    {
        return $this->actualizado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setGimnasio_id($gimnasio_id)
    {
        $this->gimnasio_id = $gimnasio_id;
    }
    public function setSocio_id($socio_id)
    {
        $this->socio_id = $socio_id;
    }
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    public function setActividad_horario_id($actividad_horario_id)
    {
        $this->actividad_horario_id = $actividad_horario_id;
    }
    public function setServicio_id($servicio_id)
    {
        $this->servicio_id = $servicio_id;
    }
    public function setMonitor_usuario_id($monitor_usuario_id)
    {
        $this->monitor_usuario_id = $monitor_usuario_id;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setHora($hora)
    {
        $this->hora = $hora;
    }
    public function setDuracion_min($duracion_min)
    {
        $this->duracion_min = $duracion_min;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function setCreada_por($creada_por)
    {
        $this->creada_por = $creada_por;
    }
    public function setNotas($notas)
    {
        $this->notas = $notas;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function setActualizado_en($actualizado_en)
    {
        $this->actualizado_en = $actualizado_en;
    }
    public function __toString()
    {
        return "Reserva {$this->id}: {$this->tipo} el {$this->fecha} a las {$this->hora} (Estado: {$this->estado})";
    }
}

class ReservaLogEstado
{
    private $id;
    private $reserva_id;
    private $estado_anterior;
    private $estado_nuevo;
    private $cambiado_por_usuario_id;
    private $detalle;
    private $creado_en;

    public function __construct($id, $reserva_id, $estado_anterior, $estado_nuevo, $cambiado_por_usuario_id, $detalle, $creado_en)
    {
        $this->id = $id;
        $this->reserva_id = $reserva_id;
        $this->estado_anterior = $estado_anterior;
        $this->estado_nuevo = $estado_nuevo;
        $this->cambiado_por_usuario_id = $cambiado_por_usuario_id;
        $this->detalle = $detalle;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getReserva_id()
    {
        return $this->reserva_id;
    }
    public function getEstado_anterior()
    {
        return $this->estado_anterior;
    }
    public function getEstado_nuevo()
    {
        return $this->estado_nuevo;
    }
    public function getCambiado_por_usuario_id()
    {
        return $this->cambiado_por_usuario_id;
    }
    public function getDetalle()
    {
        return $this->detalle;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setReserva_id($reserva_id)
    {
        $this->reserva_id = $reserva_id;
    }
    public function setEstado_anterior($estado_anterior)
    {
        $this->estado_anterior = $estado_anterior;
    }
    public function setEstado_nuevo($estado_nuevo)
    {
        $this->estado_nuevo = $estado_nuevo;
    }
    public function setCambiado_por_usuario_id($cambiado_por_usuario_id)
    {
        $this->cambiado_por_usuario_id = $cambiado_por_usuario_id;
    }
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Cambio de estado {$this->id}: de {$this->estado_anterior} a {$this->estado_nuevo}";
    }
}

class Rutina
{
    private $id;
    private $gimnasio_id;
    private $socio_id;
    private $nombre;
    private $objetivo;
    private $nivel;
    private $duracion_min;
    private $origen;
    private $notas_generales;
    private $creado_en;

    public function __construct($id, $gimnasio_id, $socio_id, $nombre, $objetivo, $nivel, $duracion_min, $origen, $notas_generales, $creado_en)
    {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->socio_id = $socio_id;
        $this->nombre = $nombre;
        $this->objetivo = $objetivo;
        $this->nivel = $nivel;
        $this->duracion_min = $duracion_min;
        $this->origen = $origen;
        $this->notas_generales = $notas_generales;
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
    public function getSocio_id()
    {
        return $this->socio_id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getObjetivo()
    {
        return $this->objetivo;
    }
    public function getNivel()
    {
        return $this->nivel;
    }
    public function getDuracion_min()
    {
        return $this->duracion_min;
    }
    public function getOrigen()
    {
        return $this->origen;
    }
    public function getNotas_generales()
    {
        return $this->notas_generales;
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
    public function setSocio_id($socio_id)
    {
        $this->socio_id = $socio_id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setObjetivo($objetivo)
    {
        $this->objetivo = $objetivo;
    }
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
    }
    public function setDuracion_min($duracion_min)
    {
        $this->duracion_min = $duracion_min;
    }
    public function setOrigen($origen)
    {
        $this->origen = $origen;
    }
    public function setNotas_generales($notas_generales)
    {
        $this->notas_generales = $notas_generales;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "Rutina {$this->id}: {$this->nombre} ({$this->objetivo}, {$this->nivel})";
    }
}

class RutinaEjercicio
{
    private $id;
    private $rutina_id;
    private $orden;
    private $ejercicio;
    private $series;
    private $reps;
    private $descanso_seg;
    private $notas;

    public function __construct($id, $rutina_id, $orden, $ejercicio, $series, $reps, $descanso_seg, $notas)
    {
        $this->id = $id;
        $this->rutina_id = $rutina_id;
        $this->orden = $orden;
        $this->ejercicio = $ejercicio;
        $this->series = $series;
        $this->reps = $reps;
        $this->descanso_seg = $descanso_seg;
        $this->notas = $notas;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getRutina_id()
    {
        return $this->rutina_id;
    }
    public function getOrden()
    {
        return $this->orden;
    }
    public function getEjercicio()
    {
        return $this->ejercicio;
    }
    public function getSeries()
    {
        return $this->series;
    }
    public function getReps()
    {
        return $this->reps;
    }
    public function getDescanso_seg()
    {
        return $this->descanso_seg;
    }
    public function getNotas()
    {
        return $this->notas;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setRutina_id($rutina_id)
    {
        $this->rutina_id = $rutina_id;
    }
    public function setOrden($orden)
    {
        $this->orden = $orden;
    }
    public function setEjercicio($ejercicio)
    {
        $this->ejercicio = $ejercicio;
    }
    public function setSeries($series)
    {
        $this->series = $series;
    }
    public function setReps($reps)
    {
        $this->reps = $reps;
    }
    public function setDescanso_seg($descanso_seg)
    {
        $this->descanso_seg = $descanso_seg;
    }
    public function setNotas($notas)
    {
        $this->notas = $notas;
    }
    public function __toString()
    {
        return "Ejercicio {$this->orden}: {$this->ejercicio} ({$this->series} x {$this->reps})";
    }
}

class ExportJob
{
    private $id;
    private $gimnasio_id;
    private $usuario_id;
    private $tipo;
    private $formato;
    private $estado;
    private $fecha_desde;
    private $fecha_hasta;
    private $nombre_archivo;
    private $ruta_archivo;
    private $tamano_bytes;
    private $error_msg;
    private $creado_en;
    private $actualizado_en;

    public function __construct(
        $id,
        $gimnasio_id,
        $usuario_id,
        $tipo,
        $formato,
        $estado,
        $fecha_desde,
        $fecha_hasta,
        $nombre_archivo,
        $ruta_archivo,
        $tamano_bytes,
        $error_msg,
        $creado_en,
        $actualizado_en
    ) {
        $this->id = $id;
        $this->gimnasio_id = $gimnasio_id;
        $this->usuario_id = $usuario_id;
        $this->tipo = $tipo;
        $this->formato = $formato;
        $this->estado = $estado;
        $this->fecha_desde = $fecha_desde;
        $this->fecha_hasta = $fecha_hasta;
        $this->nombre_archivo = $nombre_archivo;
        $this->ruta_archivo = $ruta_archivo;
        $this->tamano_bytes = $tamano_bytes;
        $this->error_msg = $error_msg;
        $this->creado_en = $creado_en;
        $this->actualizado_en = $actualizado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getGimnasio_id()
    {
        return $this->gimnasio_id;
    }
    public function getUsuario_id()
    {
        return $this->usuario_id;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getFormato()
    {
        return $this->formato;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getFecha_desde()
    {
        return $this->fecha_desde;
    }
    public function getFecha_hasta()
    {
        return $this->fecha_hasta;
    }
    public function getNombre_archivo()
    {
        return $this->nombre_archivo;
    }
    public function getRuta_archivo()
    {
        return $this->ruta_archivo;
    }
    public function getTamano_bytes()
    {
        return $this->tamano_bytes;
    }
    public function getError_msg()
    {
        return $this->error_msg;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function getActualizado_en()
    {
        return $this->actualizado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setGimnasio_id($gimnasio_id)
    {
        $this->gimnasio_id = $gimnasio_id;
    }
    public function setUsuario_id($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }
    public function setFormato($formato)
    {
        $this->formato = $formato;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function setFecha_desde($fecha_desde)
    {
        $this->fecha_desde = $fecha_desde;
    }
    public function setFecha_hasta($fecha_hasta)
    {
        $this->fecha_hasta = $fecha_hasta;
    }
    public function setNombre_archivo($nombre_archivo)
    {
        $this->nombre_archivo = $nombre_archivo;
    }
    public function setRuta_archivo($ruta_archivo)
    {
        $this->ruta_archivo = $ruta_archivo;
    }
    public function setTamano_bytes($tamano_bytes)
    {
        $this->tamano_bytes = $tamano_bytes;
    }
    public function setError_msg($error_msg)
    {
        $this->error_msg = $error_msg;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function setActualizado_en($actualizado_en)
    {
        $this->actualizado_en = $actualizado_en;
    }
    public function __toString()
    {
        return "ExportJob {$this->id}: {$this->tipo} en formato {$this->formato} (Estado: {$this->estado})";
    }
}

class PasswordReset
{
    private $id;
    private $tipo_cuenta;
    private $usuario_id;
    private $socio_id;
    private $token_hash;
    private $expira_en;
    private $usado;
    private $creado_en;

    public function __construct($id, $tipo_cuenta, $usuario_id, $socio_id, $token_hash, $expira_en, $usado, $creado_en)
    {
        $this->id = $id;
        $this->tipo_cuenta = $tipo_cuenta;
        $this->usuario_id = $usuario_id;
        $this->socio_id = $socio_id;
        $this->token_hash = $token_hash;
        $this->expira_en = $expira_en;
        $this->usado = $usado;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getTipo_cuenta()
    {
        return $this->tipo_cuenta;
    }
    public function getUsuario_id()
    {
        return $this->usuario_id;
    }
    public function getSocio_id()
    {
        return $this->socio_id;
    }
    public function getToken_hash()
    {
        return $this->token_hash;
    }
    public function getExpira_en()
    {
        return $this->expira_en;
    }
    public function getUsado()
    {
        return $this->usado;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setTipo_cuenta($tipo_cuenta)
    {
        $this->tipo_cuenta = $tipo_cuenta;
    }
    public function setUsuario_id($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }
    public function setSocio_id($socio_id)
    {
        $this->socio_id = $socio_id;
    }
    public function setToken_hash($token_hash)
    {
        $this->token_hash = $token_hash;
    }
    public function setExpira_en($expira_en)
    {
        $this->expira_en = $expira_en;
    }
    public function setUsado($usado)
    {
        $this->usado = $usado;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "PasswordReset {$this->id}: para {$this->tipo_cuenta}, expiración {$this->expira_en}";
    }
}

class RememberToken
{
    private $id;
    private $tipo_cuenta;
    private $usuario_id;
    private $socio_id;
    private $token_hash;
    private $expira_en;
    private $creado_en;

    public function __construct($id, $tipo_cuenta, $usuario_id, $socio_id, $token_hash, $expira_en, $creado_en)
    {
        $this->id = $id;
        $this->tipo_cuenta = $tipo_cuenta;
        $this->usuario_id = $usuario_id;
        $this->socio_id = $socio_id;
        $this->token_hash = $token_hash;
        $this->expira_en = $expira_en;
        $this->creado_en = $creado_en;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getTipo_cuenta()
    {
        return $this->tipo_cuenta;
    }
    public function getUsuario_id()
    {
        return $this->usuario_id;
    }
    public function getSocio_id()
    {
        return $this->socio_id;
    }
    public function getToken_hash()
    {
        return $this->token_hash;
    }
    public function getExpira_en()
    {
        return $this->expira_en;
    }
    public function getCreado_en()
    {
        return $this->creado_en;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setTipo_cuenta($tipo_cuenta)
    {
        $this->tipo_cuenta = $tipo_cuenta;
    }
    public function setUsuario_id($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }
    public function setSocio_id($socio_id)
    {
        $this->socio_id = $socio_id;
    }
    public function setToken_hash($token_hash)
    {
        $this->token_hash = $token_hash;
    }
    public function setExpira_en($expira_en)
    {
        $this->expira_en = $expira_en;
    }
    public function setCreado_en($creado_en)
    {
        $this->creado_en = $creado_en;
    }
    public function __toString()
    {
        return "RememberToken {$this->id}: para {$this->tipo_cuenta}, expira {$this->expira_en}";
    }
}
