<?php
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
