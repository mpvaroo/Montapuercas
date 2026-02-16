<?php

namespace Database\Factories;

use App\Models\Perfil;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerfilFactory extends Factory
{
    protected $model = Perfil::class;

    public function definition(): array
    {
        return [
            'nombre_real_usuario' => fake()->name(),
            'telefono_usuario' => fake()->phoneNumber(),
            'objetivo_principal_usuario' => fake()->randomElement(['definir', 'volumen', 'rendimiento', 'salud']),
            'dias_entrenamiento_semana_usuario' => fake()->numberBetween(1, 7),
            'nivel_usuario' => fake()->randomElement(['principiante', 'intermedio', 'avanzado']),
            'peso_kg_usuario' => fake()->randomFloat(2, 50, 120),
            'altura_cm_usuario' => fake()->numberBetween(150, 210),
            'fecha_inicio_usuario' => fake()->date(),
        ];
    }
}
