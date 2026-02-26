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
            'required',
            'string',
            'min:9',
            'max:20',
            'not_in:9999999999999',
            // Formato español: 9 dígitos, empieza por 6,7,8,9 con prefijo opcional +34, 0034 o 34
            'regex:/^(\+34|0034|34)?[ -]?[6789]\d{8}$/',
            $userId === null
                ? Rule::unique('perfiles_usuario', 'telefono_usuario')
                : Rule::unique('perfiles_usuario', 'telefono_usuario')->ignore($userId, 'id_usuario'),
        ];
    }
}
