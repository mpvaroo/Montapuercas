<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RutinaUsuario extends Model
{
    use HasFactory;

    protected $table = 'rutinas_usuario';
    protected $primaryKey = 'id_rutina_usuario';
    public $timestamps = false; // fecha_creacion_rutina used instead

    protected $fillable = [
        'id_usuario',
        'nombre_rutina_usuario',
        'objetivo_rutina_usuario',
        'nivel_rutina_usuario',
        'duracion_estimada_minutos',
        'origen_rutina',
        'instrucciones_rutina',
        'rutina_activa',
    ];

    protected $casts = [
        'rutina_activa' => 'boolean',
        'fecha_creacion_rutina' => 'datetime',
    ];

    /**
     * Get the user that owns the routine.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * The exercises that belong to the routine.
     */
    public function ejercicios(): BelongsToMany
    {
        return $this->belongsToMany(Ejercicio::class, 'rutinas_ejercicios', 'id_rutina_usuario', 'id_ejercicio')
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
