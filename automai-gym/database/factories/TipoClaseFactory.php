<?php

namespace Database\Factories;

use App\Models\TipoClase;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoClaseFactory extends Factory
{
    protected $model = TipoClase::class;

    public function definition(): array
    {
        return [
            'nombre_tipo_clase' => fake()->unique()->word(),
            'descripcion_tipo_clase' => fake()->sentence(),
        ];
    }
}
