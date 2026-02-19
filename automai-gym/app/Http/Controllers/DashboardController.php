<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\RutinaUsuario;
use App\Models\RegistroProgreso;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Ventana de tiempo para el calendario (1 mes atrás → 6 meses adelante)
        $startWindow = Carbon::now()->subMonth()->startOfMonth();
        $endWindow = Carbon::now()->addMonths(6)->endOfMonth();

        // 1a. Reservas de clases del usuario en la ventana
        $reservas = $user->reservas()
            ->with(['clase.tipoClase'])
            ->whereIn('estado_reserva', ['reservada'])
            ->whereHas('clase', function ($query) use ($startWindow, $endWindow) {
                $query->whereBetween('fecha_inicio_clase', [$startWindow, $endWindow]);
            })
            ->get();

        $events = [];
        foreach ($reservas as $reserva) {
            $dateKey = $reserva->clase->fecha_inicio_clase->format('Y-m-d');
            $events[$dateKey][] = [
                'type' => 'clase',
                'title' => $reserva->clase->titulo_clase,
                'time' => $reserva->clase->fecha_inicio_clase->format('H:i'),
                'place' => $reserva->clase->ubicacion_clase ?? 'Gimnasio',
                'instructor' => $reserva->clase->instructor_clase,
            ];
        }

        // 1b. Rutinas activas con día asignado → expandir a fechas concretas en la ventana
        $rutinas = collect();
        try {
            if (Schema::hasColumn('rutinas_usuario', 'dia_semana')) {
                $rutinas = $user->rutinas()
                    ->where('rutina_activa', true)
                    ->whereNotNull('dia_semana')
                    ->where('dia_semana', '!=', 'descanso')
                    ->get();
            }
        } catch (\Exception $e) { /* columna aún no existe */
        }

        if ($rutinas->isNotEmpty()) {
            // Mapa: nombre de día (BD) → dayOfWeekIso (1=Lun … 7=Dom)
            $mapaDias = [
                'lunes' => 1,
                'martes' => 2,
                'miercoles' => 3,
                'jueves' => 4,
                'viernes' => 5,
                'sabado' => 6,
                'domingo' => 7,
            ];

            $cursor = $startWindow->copy()->startOfDay();
            $endDay = $endWindow->copy()->startOfDay();

            while ($cursor->lte($endDay)) {
                $isoDay = $cursor->dayOfWeekIso; // 1=Lun … 7=Dom

                foreach ($rutinas as $rutina) {
                    $rutinaDayIso = $mapaDias[$rutina->dia_semana] ?? null;
                    if ($rutinaDayIso === $isoDay) {
                        $dateKey = $cursor->format('Y-m-d');
                        $events[$dateKey][] = [
                            'type' => 'rutina',
                            'title' => $rutina->nombre_rutina_usuario,
                            'time' => 'Todo el día',
                            'place' => 'Entrenamiento Personal',
                        ];
                    }
                }
                $cursor->addDay();
            }
        }

        // 2. Rutina programada para hoy
        $daysMap = [
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
            7 => 'domingo',
        ];
        $todayName = $daysMap[Carbon::now()->dayOfWeekIso];

        $routineToday = $user->rutinas()
            ->where('rutina_activa', true)
            ->where('dia_semana', $todayName)
            ->first();

        // 3. Dato de progreso aleatorio
        $lastProgress = $user->registrosProgreso()
            ->orderBy('fecha_registro', 'desc')
            ->first();

        $randomProgress = null;
        if ($lastProgress) {
            $fields = [
                'Peso' => ['val' => $lastProgress->peso_kg_registro, 'unit' => 'kg'],
                'Cintura' => ['val' => $lastProgress->cintura_cm_registro, 'unit' => 'cm'],
                'Pecho' => ['val' => $lastProgress->pecho_cm_registro, 'unit' => 'cm'],
                'Cadera' => ['val' => $lastProgress->cadera_cm_registro, 'unit' => 'cm'],
            ];
            $fields = array_filter($fields, fn($f) => $f['val'] !== null);

            if (!empty($fields)) {
                $randomKey = array_rand($fields);
                $randomProgress = [
                    'label' => $randomKey,
                    'value' => $fields[$randomKey]['val'],
                    'unit' => $fields[$randomKey]['unit'],
                    'date' => $lastProgress->fecha_registro->format('Y-m-d'),
                    'notes' => $lastProgress->notas_progreso,
                ];
            }
        }

        return view('dashboard', compact('events', 'routineToday', 'randomProgress'));
    }
}
