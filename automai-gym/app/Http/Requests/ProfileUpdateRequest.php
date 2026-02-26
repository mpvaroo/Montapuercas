<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    use \App\Concerns\ProfileValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->gymRules() + [
            // El controlador de perfil (no Livewire) solo actualiza la tabla perfiles_usuario y la foto,
            // pero mantenemos consistencia si decidimos añadir más campos.
            'telefono_usuario' => $this->phoneRules(Auth::id()),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return $this->profileMessages();
    }
}
