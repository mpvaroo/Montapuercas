<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\RegistroProgreso;

class ProgresoController extends Controller
{
    /**
     * Página principal de progreso.
     */
    public function index()
    {
        $user = Auth::user();

        // Último registro
        $lastRecord = $user->registrosProgreso()
            ->orderBy('fecha_registro', 'desc')
            ->first();

        // Historial — últimos 6 registros para el panel
        $history = $user->registrosProgreso()
            ->orderBy('fecha_registro', 'desc')
            ->take(6)
            ->get();

        // Notas recientes — registros con nota, últimos 4
        $notes = $user->registrosProgreso()
            ->whereNotNull('notas_progreso')
            ->where('notas_progreso', '!=', '')
            ->orderBy('fecha_registro', 'desc')
            ->take(4)
            ->get();

        // Gráfico: últimas 10 semanas que tienen algún registro (sin importar fecha futura/pasada)
        // Agrupa todos los registros por semana ISO, toma las 10 más recientes en orden cronológico
        $allByWeek = $user->registrosProgreso()
            ->orderBy('fecha_registro', 'desc')
            ->get()
            ->groupBy(function ($r) {
                return Carbon::parse($r->fecha_registro)
                    ->startOfWeek(Carbon::MONDAY)
                    ->format('Y-m-d');
            });

        // Tomar las 10 semanas más recientes y ponerlas en orden cronológico
        $last10Weeks = $allByWeek->take(10)->reverse();

        $chartLabels  = [];
        $chartWeights = [];

        foreach ($last10Weeks as $weekStart => $weekRecords) {
            $chartLabels[]  = Carbon::parse($weekStart)->format('d/m');
            // El registro con mayor fecha dentro de esa semana
            $best = $weekRecords->sortByDesc('fecha_registro')->first();
            $chartWeights[] = $best && $best->peso_kg_registro !== null
                ? (float) $best->peso_kg_registro
                : null;
        }

        return view('progreso', compact(
            'lastRecord',
            'history',
            'notes',
            'chartLabels',
            'chartWeights'
        ));
    }

    /**
     * Guarda / actualiza un registro (UPSERT por usuario + fecha).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Fecha mínima: día en que se creó la cuenta del usuario
        $minDate = Carbon::parse($user->fecha_creacion_usuario)->format('Y-m-d');

        $validated = $request->validate([
            'fecha_registro'       => ['required', 'date', 'after_or_equal:' . $minDate],
            'peso_kg_registro'     => 'nullable|numeric|min:0.01|max:999.99',
            'cintura_cm_registro'  => 'nullable|numeric|min:0.01|max:999.99',
            'pecho_cm_registro'    => 'nullable|numeric|min:0.01|max:999.99',
            'cadera_cm_registro'   => 'nullable|numeric|min:0.01|max:999.99',
            'notas_progreso'       => 'nullable|string|max:220',
        ], [
            'fecha_registro.after_or_equal' => 'La fecha no puede ser anterior a cuando creaste tu cuenta (' . $minDate . ').',
            'peso_kg_registro.min'          => 'El peso debe ser mayor que 0.',
            'cintura_cm_registro.min'       => 'La cintura debe ser mayor que 0.',
            'pecho_cm_registro.min'         => 'El pecho debe ser mayor que 0.',
            'cadera_cm_registro.min'        => 'La cadera debe ser mayor que 0.',
        ]);

        RegistroProgreso::updateOrCreate(
            [
                'id_usuario'     => $user->id_usuario,
                'fecha_registro' => $validated['fecha_registro'],
            ],
            [
                'peso_kg_registro'    => $validated['peso_kg_registro']    ?? null,
                'cintura_cm_registro' => $validated['cintura_cm_registro']  ?? null,
                'pecho_cm_registro'   => $validated['pecho_cm_registro']    ?? null,
                'cadera_cm_registro'  => $validated['cadera_cm_registro']   ?? null,
                'notas_progreso'      => $validated['notas_progreso']        ?? null,
            ]
        );

        return redirect()->route('progreso')
            ->with('success', 'Registro del ' . $validated['fecha_registro'] . ' guardado.');
    }

    /**
     * Vista de historial — los datos los carga el componente Livewire.
     */
    public function historial()
    {
        return view('progreso-historial');
    }

    /**
     * Detalle de un registro concreto.
     */
    public function detalle($id)
    {
        $user   = Auth::user();
        $record = $user->registrosProgreso()
            ->where('id_registro_progreso', $id)
            ->firstOrFail();

        return view('progreso-detalle', compact('record'));
    }
}
