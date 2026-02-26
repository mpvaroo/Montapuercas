<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'nombre_mostrado_usuario' => $this->nameRules($userId),
            'nombre_real_usuario' => $this->realNameRules(),
            'correo_usuario' => $this->emailRules($userId),
            'telefono_usuario' => $this->phoneRules($userId),
            ...$this->gymRules(),
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function nameRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'max:255',
            'regex:/[a-zA-ZáéíóúÁÉÍÓÚñÑ]/', // Debe contener al menos una letra
            $userId === null
            ? Rule::unique(User::class)
            : Rule::unique(User::class)->ignore($userId, 'id_usuario'),
        ];
    }

    /**
     * Get the validation rules used to validate user emails.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $userId === null
            ? Rule::unique(User::class)
            : Rule::unique(User::class)->ignore($userId, 'id_usuario'),
        ];
    }

    /**
     * Get the validation rules used to validate real names.
     *
     * @return array<int, string>
     */
    protected function realNameRules(): array
    {
        return [
            'required',
            'string',
            'max:120',
            'regex:/[a-zA-ZáéíóúÁÉÍÓÚñÑ]/', // Debe contener al menos una letra
        ];
    }

    /**
     * Get the validation rules used to validate phone numbers.
     *
     * @return array<int, string|\Illuminate\Validation\Rules\Unique>
     */
    protected function phoneRules(?int $userId = null): array
    {
        return [
            'nullable', // Permitimos que sea nulo si no se ha configurado aún, pero si se mete, debe ser válido
            'string',
            'min:9',
            'max:20',
            'not_in:999999999,000000000',
            // Formato español: 9 dígitos, empieza por 6,7,8,9 con prefijo opcional +34, 0034 o 34
            'regex:/^(\+34|0034|34)?[ -]?[6789]\d{8}$/',
            $userId === null
            ? Rule::unique('perfiles_usuario', 'telefono_usuario')
            : Rule::unique('perfiles_usuario', 'telefono_usuario')->ignore($userId, 'id_usuario'),
        ];
    }

    /**
     * Get the validation rules for gym-specific data.
     *
     * @return array<string, array<int, string>>
     */
    protected function gymRules(): array
    {
        return [
            'objetivo_principal_usuario' => ['nullable', 'string', 'in:salud,definir,volumen,rendimiento'],
            'nivel_usuario' => ['nullable', 'string', 'in:principiante,intermedio,avanzado'],
            'dias_entrenamiento_semana_usuario' => ['nullable', 'integer', 'min:1', 'max:7'],
            'peso_kg_usuario' => ['nullable', 'numeric', 'min:30', 'max:300'],
            'altura_cm_usuario' => ['nullable', 'numeric', 'min:50', 'max:250'],
            'fecha_inicio_usuario' => ['nullable', 'date', 'before_or_equal:today'],
            'foto_perfil' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for profile validation.
     *
     * @return array<string, string>
     */
    protected function profileMessages(): array
    {
        return [
            'nombre_mostrado_usuario.required' => 'El nombre de usuario es obligatorio.',
            'nombre_mostrado_usuario.unique' => 'Este nombre de usuario ya está en uso.',
            'nombre_mostrado_usuario.regex' => 'El nombre de usuario debe contener al menos una letra.',
            'nombre_real_usuario.required' => 'El nombre real es obligatorio.',
            'nombre_real_usuario.regex' => 'El nombre real debe contener al menos una letra.',
            'correo_usuario.required' => 'El correo electrónico es obligatorio.',
            'correo_usuario.email' => 'Introduce un correo electrónico válido.',
            'correo_usuario.unique' => 'Este correo electrónico ya está registrado.',
            'telefono_usuario.regex' => 'El formato del teléfono no es válido para España.',
            'telefono_usuario.unique' => 'Este número de teléfono ya está registrado.',
            'peso_kg_usuario.min' => 'El peso debe ser al menos 30kg.',
            'peso_kg_usuario.max' => 'El peso no puede exceder los 300kg.',
            'altura_cm_usuario.min' => 'La altura debe ser al menos 50cm.',
            'altura_cm_usuario.max' => 'La altura no puede exceder los 250cm.',
            'dias_entrenamiento_semana_usuario.min' => 'Introduce al menos 1 día de entrenamiento.',
            'dias_entrenamiento_semana_usuario.max' => 'No puedes entrenar más de 7 días a la semana.',
            'fecha_inicio_usuario.before_or_equal' => 'La fecha de inicio no puede ser en el futuro.',
            'foto_perfil.image' => 'El archivo debe ser una imagen.',
            'foto_perfil.mimes' => 'Solo se permiten formatos JPG y PNG.',
            'foto_perfil.max' => 'La imagen no debe pesar más de 2MB.',
        ];
    }
}
