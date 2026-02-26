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
            'objetivo_principal_usuario' => ['required', 'string', 'in:salud,definir,volumen,rendimiento'],
            'nivel_usuario' => ['required', 'string', 'in:principiante,intermedio,avanzado'],
            'dias_entrenamiento_semana_usuario' => ['required', 'integer', 'min:1', 'max:7'],
            'peso_kg_usuario' => ['required', 'numeric', 'min:30', 'max:300'],
            'altura_cm_usuario' => ['required', 'integer', 'min:100', 'max:250'],
        ], [
            'nombre_mostrado_usuario.unique' => 'Ese nombre de usuario ya está en uso.',
            'nombre_mostrado_usuario.regex' => 'El nombre de usuario debe contener al menos una letra.',
            'nombre_real_usuario.regex' => 'El nombre real debe contener al menos una letra.',
            'correo_usuario.unique' => 'Ese correo electrónico ya está registrado.',
            'telefono_usuario.required' => 'El teléfono es obligatorio.',
            'telefono_usuario.min' => 'El teléfono debe tener al menos 9 caracteres.',
            'telefono_usuario.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'telefono_usuario.unique' => 'Ese número de teléfono ya está registrado.',
            'telefono_usuario.regex' => 'El formato del teléfono debe ser español válido.',
            'telefono_usuario.not_in' => 'Ese número de teléfono no está permitido.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.required' => 'La contraseña es obligatoria.',
            'peso_kg_usuario.min' => 'El peso mínimo debe ser 30 kg.',
            'peso_kg_usuario.max' => 'Recomendamos verificar el peso ingresado.',
            'altura_cm_usuario.min' => 'La altura mínima es de 100 cm.',
            'altura_cm_usuario.max' => 'Recomendamos verificar la altura ingresada.',
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

            // Create related settings record with default data
            \App\Models\Ajustes::create([
                'id_usuario' => $user->id_usuario,
                'notificaciones_entrenamiento_activas' => true,
                'notificaciones_clases_activas' => true,
                'tono_ia_coach' => 'directo', // default value
                'idioma_preferido' => 'es',   // default value
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
