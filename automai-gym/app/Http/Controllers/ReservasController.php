<?php

namespace App\Http\Controllers;

use App\Models\ClaseGimnasio;
use App\Models\ReservaClase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReservasController extends Controller
{
    /**
     * Display a listing of upcoming classes and user's reservations.
     */
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // Obtener clases futuras (o en curso si se permite unirse tarde)
        $clases = ClaseGimnasio::with('tipoClase')
            ->where('fecha_inicio_clase', '>=', $now)
            ->orderBy('fecha_inicio_clase', 'asc')
            ->get();

        // Obtener IDs de clases donde el usuario ya tiene reserva activa
        $reservasUsuarioIds = $user->reservas()
            ->whereIn('estado_reserva', ['reservada'])
            ->pluck('id_clase_gimnasio')
            ->toArray();

        return view('reservas', compact('clases', 'reservasUsuarioIds'));
    }

    /**
     * Sign up for a class.
     */
    public function store(Request $request, ClaseGimnasio $clase)
    {
        $user = Auth::user();

        // Validar si ya está inscrito
        if ($user->reservas()->where('id_clase_gimnasio', $clase->id_clase_gimnasio)->where('estado_reserva', 'reservada')->exists()) {
            return back()->with('error', 'Ya tienes una reserva para esta clase.');
        }

        // Validar cupo
        $reservasCount = $clase->reservas()->where('estado_reserva', 'reservada')->count();
        if ($reservasCount >= $clase->cupo_maximo_clase) {
            return back()->with('error', 'La clase está llena.');
        }

        // Crear o actualizar reserva (si estaba cancelada)
        ReservaClase::updateOrCreate(
            [
                'id_usuario' => $user->id_usuario,
                'id_clase_gimnasio' => $clase->id_clase_gimnasio,
            ],
            [
                'estado_reserva' => 'reservada',
                'origen_reserva' => $user->isAdmin() ? 'admin' : 'usuario',
                'fecha_reserva' => Carbon::now(),
            ]
        );

        return back()->with('success', '¡Te has apuntado a la clase correctamente!');
    }

    /**
     * Show the details of a specific class.
     */
    public function show($id)
    {
        $clase = ClaseGimnasio::with('tipoClase')->findOrFail($id);
        $user = Auth::user();

        $reserva = $user->reservas()
            ->where('id_clase_gimnasio', $id)
            ->whereIn('estado_reserva', ['reservada'])
            ->first();

        return view('detalle-reserva', compact('clase', 'reserva'));
    }

    /**
     * Display a personalized calendar with user's reservations.
     */
    public function calendario(Request $request)
    {
        $user = Auth::user();
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $date = Carbon::createFromDate($year, $month, 1);
        $monthName = $date->translatedFormat('F Y');

        // Reservas del usuario para este mes
        $reservas = $user->reservas()
            ->with(['clase.tipoClase'])
            ->where('estado_reserva', 'reservada')
            ->whereHas('clase', function ($query) use ($month, $year) {
                $query->whereMonth('fecha_inicio_clase', $month)
                    ->whereYear('fecha_inicio_clase', $year);
            })
            ->get();

        // Rutinas activas del usuario (protegido: si falta la columna dia_semana, no rompemos el calendario)
        $rutinas = collect();
        try {
            if (Schema::hasColumn('rutinas_usuario', 'dia_semana')) {
                $rutinas = \App\Models\RutinaUsuario::where('id_usuario', $user->id_usuario)
                    ->whereNotNull('dia_semana')
                    ->where('rutina_activa', true)
                    ->get();
            }
        } catch (\Exception $e) {
            // La columna no existe todavía; el calendario seguirá mostrando reservas
        }

        // Agrupar todo por día
        $eventosPorDia = [];

        // Añadir Reservas
        foreach ($reservas as $reserva) {
            $dia = $reserva->clase->fecha_inicio_clase->day;
            $eventosPorDia[$dia][] = [
                'tipo' => 'clase',
                'titulo' => $reserva->clase->titulo_clase,
                'sub' => $reserva->clase->instructor_clase,
                'hora' => $reserva->clase->fecha_inicio_clase->format('H:i'),
                'id' => $reserva->clase->id_clase_gimnasio
            ];
        }

        // Mapear Rutinas a días específicos del mes
        $daysInMonth = $date->daysInMonth;
        $mapaDias = [
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
            7 => 'domingo',
        ];

        if ($rutinas->isNotEmpty()) {
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $currentDate = Carbon::createFromDate($year, $month, $d);
                $dayOfWeek = $currentDate->dayOfWeekIso;
                $dayNameInDB = $mapaDias[$dayOfWeek] ?? null;

                foreach ($rutinas as $rutina) {
                    if ($rutina->dia_semana === $dayNameInDB) {
                        $eventosPorDia[$d][] = [
                            'tipo' => 'rutina',
                            'titulo' => $rutina->nombre_rutina_usuario,
                            'sub' => 'Entrenamiento Personal',
                            'hora' => 'Todo el día',
                            'id' => $rutina->id_rutina_usuario
                        ];
                    }
                }
            }
        }

        // Lógica de cuadrícula
        $daysInMonth = $date->daysInMonth;
        $startOfWeek = $date->dayOfWeekIso; // 1 (Mon) to 7 (Sun)

        // Calcular prev/next month
        $prevMonth = $date->copy()->subMonth();
        $nextMonth = $date->copy()->addMonth();

        return view('calendario', compact(
            'monthName',
            'daysInMonth',
            'startOfWeek',
            'eventosPorDia',
            'month',
            'year',
            'prevMonth',
            'nextMonth',
            'date'
        ));
    }

    /**
     * Cancel a reservation.
     */
    public function destroy(ClaseGimnasio $clase)
    {
        $user = Auth::user();

        $reserva = ReservaClase::where('id_usuario', $user->id_usuario)
            ->where('id_clase_gimnasio', $clase->id_clase_gimnasio)
            ->whereIn('estado_reserva', ['reservada'])
            ->first();

        if ($reserva) {
            $reserva->update(['estado_reserva' => 'cancelada']);
            return back()->with('success', 'Reservación cancelada.');
        }

        return back()->with('error', 'No tienes una reserva activa para esta clase.');
    }
}
