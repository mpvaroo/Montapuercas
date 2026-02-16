<?php

namespace Database\Factories;

use App\Models\ReservaClase;
use App\Models\User;
use App\Models\ClaseGimnasio;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaClaseFactory extends Factory
{
    protected $model = ReservaClase::class;

    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(),
            'id_clase_gimnasio' => ClaseGimnasio::factory(),
            'estado_reserva' => 'reservada',
            'fecha_reserva' => now(),
            'origen_reserva' => 'usuario',
        ];
    }
}
