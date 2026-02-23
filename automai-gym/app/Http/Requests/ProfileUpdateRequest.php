<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
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
        return [
            'telefono_usuario' => 'nullable|string|max:20',
            'fecha_inicio_usuario' => 'nullable|date',
            'objetivo_principal_usuario' => 'nullable|string|in:salud,definir,volumen,rendimiento',
            'nivel_usuario' => 'nullable|string|in:principiante,intermedio,avanzado',
            'dias_entrenamiento_semana_usuario' => 'nullable|integer|min:1|max:7',
            'peso_kg_usuario' => 'nullable|numeric|min:0',
            'altura_cm_usuario' => 'nullable|numeric|min:0',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'foto_perfil.image' => 'El archivo debe ser una imagen.',
            'foto_perfil.mimes' => 'Solo se permiten imágenes JPG y PNG.',
            'foto_perfil.max' => 'La imagen no debe pesar más de 2MB.',
            'dias_entrenamiento_semana_usuario.max' => 'El máximo de días es 7.',
            'dias_entrenamiento_semana_usuario.min' => 'El mínimo de días es 1.',
        ];
    }
}
