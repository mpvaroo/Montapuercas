<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ReservaClase;

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

        // Obtener todas las reservas activas futuras (para el calendario)
        // No filtramos por mes aquí para permitir navegar en JS sin recargar si es posible,
        // o podríamos limitar a +/- 3 meses si son muchas.
        // Dado el contexto, traer las futuras y recientes pasadas (último mes) está bien.

        $startWindow = Carbon::now()->subMonth()->startOfMonth();
        $endWindow = Carbon::now()->addMonths(6)->endOfMonth();

        $reservas = $user->reservas()
            ->with(['clase.tipoClase'])
            ->whereIn('estado_reserva', ['reservada'])
            ->whereHas('clase', function ($query) use ($startWindow, $endWindow) {
                $query->whereBetween('fecha_inicio_clase', [$startWindow, $endWindow]);
            })
            ->get();

        // Formatear para JS: "YYYY-MM-DD" => [eventos]
        $events = [];
        foreach ($reservas as $reserva) {
            $dateKey = $reserva->clase->fecha_inicio_clase->format('Y-m-d');

            $events[$dateKey][] = [
                'title' => $reserva->clase->titulo_clase, // O tipo de clase
                'time' => $reserva->clase->fecha_inicio_clase->format('H:i'),
                'place' => $reserva->clase->ubicacion_clase ?? 'Gimnasio', // Asumiendo campo o default
                'instructor' => $reserva->clase->instructor_clase,
            ];
        }

        return view('dashboard', compact('events'));
    }
}
