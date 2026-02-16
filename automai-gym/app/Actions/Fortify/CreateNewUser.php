<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'nombre_real_usuario' => ['nullable', 'string', 'max:120'],
            'objetivo_principal_usuario' => ['required', 'string', 'in:salud,definir,volumen,rendimiento'],
            'nivel_usuario' => ['required', 'string', 'in:principiante,intermedio,avanzado'],
            'dias_entrenamiento_semana_usuario' => ['required', 'integer', 'min:1', 'max:7'],
            'telefono_usuario' => ['nullable', 'string', 'max:20'],
            'peso_kg_usuario' => ['nullable', 'numeric', 'min:0'],
            'altura_cm_usuario' => ['nullable', 'integer', 'min:0'],
            'tono_ia_coach' => ['required', 'string', 'in:directo,motivador'],
            'idioma_preferido' => ['required', 'string', 'in:es,en'],
        ])->validate();

        return \Illuminate\Support\Facades\DB::transaction(function () use ($input) {
            $user = User::create([
                'nombre_mostrado_usuario' => $input['nombre_mostrado_usuario'],
                'correo_usuario' => $input['correo_usuario'],
                'hash_contrasena_usuario' => $input['password'],
                'estado_usuario' => 'activo',
            ]);

            // Create related profile record with all form data
            \App\Models\Perfil::create([
                'id_usuario' => $user->id_usuario,
                'nombre_real_usuario' => $input['nombre_real_usuario'] ?? $input['nombre_mostrado_usuario'],
                'telefono_usuario' => $input['telefono_usuario'] ?? null,
                'objetivo_principal_usuario' => $input['objetivo_principal_usuario'],
                'dias_entrenamiento_semana_usuario' => $input['dias_entrenamiento_semana_usuario'],
                'nivel_usuario' => $input['nivel_usuario'],
                'peso_kg_usuario' => $input['peso_kg_usuario'] ?? null,
                'altura_cm_usuario' => $input['altura_cm_usuario'] ?? null,
                'fecha_inicio_usuario' => now(),
            ]);

            // Create related settings record with form data
            \App\Models\Ajustes::create([
                'id_usuario' => $user->id_usuario,
                'notificaciones_entrenamiento_activas' => true,
                'notificaciones_clases_activas' => true,
                'tono_ia_coach' => $input['tono_ia_coach'],
                'idioma_preferido' => $input['idioma_preferido'],
                'semana_empieza_en' => 'lunes',
            ]);

            // Create related security record
            \App\Models\Seguridad::create([
                'id_usuario' => $user->id_usuario,
                'requiere_cambio_contrasena' => false,
                'intentos_fallidos_login' => 0,
            ]);

            return $user;
        });
    }
}
