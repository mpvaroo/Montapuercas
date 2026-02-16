<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Perfil;
use App\Models\Ajustes;
use App\Models\Seguridad;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_mostrado_usuario' => fake()->name(),
            'correo_usuario' => fake()->unique()->safeEmail(),
            'hash_contrasena_usuario' => static::$password ??= Hash::make('password'),
            'estado_usuario' => 'activo',
            'fecha_creacion_usuario' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            Perfil::factory()->create(['id_usuario' => $user->id_usuario]);
            Ajustes::factory()->create(['id_usuario' => $user->id_usuario]);
            Seguridad::factory()->create(['id_usuario' => $user->id_usuario]);
        });
    }
}
