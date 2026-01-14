<?php
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
