<?php

namespace Database\Factories;

use App\Models\Seguridad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeguridadFactory extends Factory
{
    protected $model = Seguridad::class;

    public function definition(): array
    {
        return [
            'requiere_cambio_contrasena' => false,
            'intentos_fallidos_login' => 0,
        ];
    }
}
