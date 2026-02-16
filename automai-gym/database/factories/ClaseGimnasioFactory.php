<?php

namespace Database\Factories;

use App\Models\ClaseGimnasio;
use App\Models\TipoClase;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaseGimnasioFactory extends Factory
{
    protected $model = ClaseGimnasio::class;

    public function definition(): array
    {
        $inicio = fake()->dateTimeBetween('now', '+1 month');
        $fin = (clone $inicio)->modify('+1 hour');

        return [
            'id_tipo_clase' => TipoClase::factory(),
            'titulo_clase' => fake()->sentence(3),
            'descripcion_clase' => fake()->text(200),
            'instructor_clase' => fake()->name(),
            'fecha_inicio_clase' => $inicio,
            'fecha_fin_clase' => $fin,
            'cupo_maximo_clase' => fake()->numberBetween(10, 30),
            'estado_clase' => 'publicada',
            'fecha_creacion_clase' => now(),
        ];
    }
}
