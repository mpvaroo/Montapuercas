<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReservaClase extends Model
{
    use HasFactory;

    protected $table = 'reservas_clase';
    protected $primaryKey = 'id_reserva_clase';
    public $timestamps = false; // fecha_reserva used instead

    protected $fillable = [
        'id_usuario',
        'id_clase_gimnasio',
        'estado_reserva',
        'origen_reserva',
        'notas_reserva',
    ];

    protected $casts = [
        'fecha_reserva' => 'datetime',
    ];

    /**
     * Get the user that owns the reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the class that owns the reservation.
     */
    public function clase(): BelongsTo
    {
        return $this->belongsTo(ClaseGimnasio::class, 'id_clase_gimnasio', 'id_clase_gimnasio');
    }


}
