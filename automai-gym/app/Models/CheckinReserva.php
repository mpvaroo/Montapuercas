<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckinReserva extends Model
{
    use HasFactory;

    protected $table = 'checkin_reserva';
    protected $primaryKey = 'id_checkin_reserva';
    public $timestamps = false;

    protected $fillable = [
        'id_reserva_clase',
        'realizado_checkin',
        'fecha_checkin',
        'metodo_checkin',
    ];

    protected $casts = [
        'realizado_checkin' => 'boolean',
        'fecha_checkin' => 'datetime',
    ];

    /**
     * Get the reservation that owns the check-in.
     */
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(ReservaClase::class, 'id_reserva_clase', 'id_reserva_clase');
    }
}
