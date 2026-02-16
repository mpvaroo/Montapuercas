<?php

namespace Database\Factories;

use App\Models\Ejercicio;
use Illuminate\Database\Eloquent\Factories\Factory;

class EjercicioFactory extends Factory
{
    protected $model = Ejercicio::class;

    public function definition(): array
    {
        return [
            'nombre_ejercicio' => fake()->unique()->word(),
            'grupo_muscular_principal' => fake()->randomElement(['pecho', 'espalda', 'pierna', 'hombro', 'biceps', 'triceps', 'core', 'cardio', 'fullbody']),
            'descripcion_ejercicio' => fake()->sentence(),
        ];
    }
}
