<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RegistroProgreso;

class RegistrosProgresoSeeder extends Seeder
{
    /**
     * Inserta 11 semanas de registros de progreso con pérdida de peso gradual
     * para el usuario mario@gmail.com (creado en DatabaseSeeder).
     * Usa updateOrCreate para que sea idempotente (se puede re-ejecutar sin duplicar).
     */
    public function run(): void
    {
        $user = User::where('correo_usuario', 'mario@gmail.com')->first();

        if (!$user) {
            $this->command->warn('Usuario mario@gmail.com no encontrado. Ejecuta DatabaseSeeder primero.');
            return;
        }

        $registros = [
            ['fecha' => '2025-11-24', 'peso' => 93.00, 'cintura' => 89.50, 'pecho' => 105.00, 'cadera' => 103.00, 'notas' => 'Inicio. A por ello.'],
            ['fecha' => '2025-12-01', 'peso' => 92.20, 'cintura' => 89.00, 'pecho' => 104.50, 'cadera' => 102.50, 'notas' => null],
            ['fecha' => '2025-12-08', 'peso' => 91.50, 'cintura' => 88.50, 'pecho' => 104.00, 'cadera' => 102.00, 'notas' => 'Primera bajada visible.'],
            ['fecha' => '2025-12-15', 'peso' => 90.80, 'cintura' => 88.00, 'pecho' => 103.50, 'cadera' => 101.50, 'notas' => null],
            ['fecha' => '2025-12-22', 'peso' => 90.20, 'cintura' => 87.50, 'pecho' => 103.00, 'cadera' => 101.00, 'notas' => 'Navidades complicadas.'],
            ['fecha' => '2025-12-29', 'peso' => 90.00, 'cintura' => 87.20, 'pecho' => 102.80, 'cadera' => 100.80, 'notas' => null],
            ['fecha' => '2026-01-05', 'peso' => 89.30, 'cintura' => 86.80, 'pecho' => 102.50, 'cadera' => 100.50, 'notas' => 'Vuelta al ritmo.'],
            ['fecha' => '2026-01-12', 'peso' => 88.60, 'cintura' => 86.20, 'pecho' => 102.00, 'cadera' => 100.00, 'notas' => null],
            ['fecha' => '2026-01-19', 'peso' => 87.90, 'cintura' => 85.80, 'pecho' => 101.50, 'cadera' =>  99.50, 'notas' => 'Gran semana. Duermo mejor.'],
            ['fecha' => '2026-01-26', 'peso' => 87.20, 'cintura' => 85.40, 'pecho' => 101.00, 'cadera' =>  99.00, 'notas' => null],
            ['fecha' => '2026-02-02', 'peso' => 86.50, 'cintura' => 85.00, 'pecho' => 100.50, 'cadera' =>  98.50, 'notas' => 'Progreso visible. Cintura bajando.'],
        ];

        foreach ($registros as $r) {
            RegistroProgreso::updateOrCreate(
                [
                    'id_usuario'     => $user->id_usuario,
                    'fecha_registro' => $r['fecha'],
                ],
                [
                    'peso_kg_registro'    => $r['peso'],
                    'cintura_cm_registro' => $r['cintura'],
                    'pecho_cm_registro'   => $r['pecho'],
                    'cadera_cm_registro'  => $r['cadera'],
                    'notas_progreso'      => $r['notas'],
                ]
            );
        }

        $this->command->info('✓ ' . count($registros) . ' registros de progreso creados para ' . $user->correo_usuario);
    }
}
