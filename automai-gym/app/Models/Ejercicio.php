<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ejercicio extends Model
{
    use HasFactory;

    protected $table = 'ejercicios';
    protected $primaryKey = 'id_ejercicio';
    public $timestamps = false;

    protected $fillable = [
        'nombre_ejercicio',
        'grupo_muscular_principal',
        'descripcion_ejercicio',
    ];

    /**
     * The routines that belong to the exercise.
     */
    public function rutinas(): BelongsToMany
    {
        return $this->belongsToMany(RutinaUsuario::class, 'rutinas_ejercicios', 'id_ejercicio', 'id_rutina_usuario')
            ->withPivot([
                'orden_en_rutina',
                'series_objetivo',
                'repeticiones_objetivo',
                'peso_objetivo_kg',
                'rpe_objetivo',
                'descanso_segundos',
                'notas_ejercicio'
            ]);
    }
}
