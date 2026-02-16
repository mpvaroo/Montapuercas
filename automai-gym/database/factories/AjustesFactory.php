<?php

namespace Database\Factories;

use App\Models\Ajustes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AjustesFactory extends Factory
{
    protected $model = Ajustes::class;

    public function definition(): array
    {
        return [
            'notificaciones_entrenamiento_activas' => true,
            'notificaciones_clases_activas' => true,
            'tono_ia_coach' => 'directo',
            'idioma_preferido' => 'es',
            'semana_empieza_en' => 'lunes',
        ];
    }
}
