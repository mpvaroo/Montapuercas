<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // 1. Calendario de reservas (existente)
        $startWindow = Carbon::now()->subMonth()->startOfMonth();
        $endWindow = Carbon::now()->addMonths(6)->endOfMonth();

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
                'title' => $reserva->clase->titulo_clase,
                'time' => $reserva->clase->fecha_inicio_clase->format('H:i'),
                'place' => $reserva->clase->ubicacion_clase ?? 'Gimnasio',
                'instructor' => $reserva->clase->instructor_clase,
            ];
        }

        // 2. Rutina para hoy
        $daysMap = [
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
            0 => 'domingo',
        ];
        $todayName = $daysMap[Carbon::now()->dayOfWeek];

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
            // Filtrar solo los que tengan valor
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
