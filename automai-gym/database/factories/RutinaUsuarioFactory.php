<?php

namespace Database\Factories;

use App\Models\RutinaUsuario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RutinaUsuarioFactory extends Factory
{
    protected $model = RutinaUsuario::class;

    public function definition(): array
    {
        return [
            'id_usuario' => User::factory(),
            'nombre_rutina_usuario' => fake()->sentence(2),
            'objetivo_rutina_usuario' => fake()->randomElement(['definir', 'volumen', 'rendimiento', 'salud']),
            'nivel_rutina_usuario' => fake()->randomElement(['principiante', 'intermedio', 'avanzado']),
            'duracion_estimada_minutos' => fake()->numberBetween(30, 90),
            'origen_rutina' => 'usuario',
            'instrucciones_rutina' => fake()->paragraph(),
            'fecha_creacion_rutina' => now(),
            'rutina_activa' => true,
        ];
    }
}
