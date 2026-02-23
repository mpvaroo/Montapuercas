<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgresoController;


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('rutinas', 'rutinas')->name('rutinas');

    Route::get('reservas', [ReservasController::class, 'index'])->name('reservas');
    Route::post('reservas/{clase}/apuntar', [ReservasController::class, 'store'])->name('reservas.apuntar');
    Route::delete('reservas/{clase}/cancelar', [ReservasController::class, 'destroy'])->name('reservas.cancelar');
    Route::get('calendario', [ReservasController::class, 'calendario'])->name('calendario');
    Route::get('progreso', [ProgresoController::class, 'index'])->name('progreso');
    Route::post('progreso', [ProgresoController::class, 'store'])->name('progreso.store');
    Route::get('progreso/historial', [ProgresoController::class, 'historial'])->name('progreso.historial');
    Route::get('progreso/pdf', [ProgresoController::class, 'downloadPdf'])->name('progreso.pdf');
    Route::get('progreso/detalle/{id}', [ProgresoController::class, 'detalle'])->name('progreso.detalle');
    Route::view('ia-coach', 'iaCoach')->name('ia-coach');
    Route::get('detalle-reserva/{id}', [ReservasController::class, 'show'])->name('detalle-reserva');
    Route::get('detalle-rutina/{id}', function ($id) {
        $routine = \App\Models\RutinaUsuario::with('ejercicios')->findOrFail($id);
        $allExercises = \App\Models\Ejercicio::orderBy('nombre_ejercicio')->get();
        return view('detalle-rutina', compact('routine', 'allExercises'));
    })->name('detalle-rutina');

    Route::post('detalle-rutina/{id}/update', function (\Illuminate\Http\Request $request, $id) {
        $routine = \App\Models\RutinaUsuario::findOrFail($id);
        $routine->update($request->only([
            'nombre_rutina_usuario',
            'objetivo_rutina_usuario',
            'nivel_rutina_usuario',
            'duracion_estimada_minutos',
            'instrucciones_rutina',
            'dia_semana'
        ]));
        return back()->with('success', 'Rutina actualizada correctamente.');
    })->name('rutina.update');

    Route::post('detalle-rutina/{id}/add-exercise', function (\Illuminate\Http\Request $request, $id) {
        $routine = \App\Models\RutinaUsuario::findOrFail($id);
        $routine->ejercicios()->attach($request->id_ejercicio, [
            'series_objetivo' => $request->series,
            'repeticiones_objetivo' => $request->reps,
            'peso_objetivo_kg' => $request->peso,
            'descanso_segundos' => $request->descanso,
            'notas_ejercicio' => $request->notas,
            'orden_en_rutina' => $routine->ejercicios()->count() + 1
        ]);
        return back()->with('success', 'Ejercicio añadido.');
    })->name('rutina.add_exercise');

    Route::post('detalle-rutina/{id}/exercise/{exercise_id}/update', function (\Illuminate\Http\Request $request, $id, $exercise_id) {
        $routine = \App\Models\RutinaUsuario::findOrFail($id);
        $routine->ejercicios()->updateExistingPivot($exercise_id, [
            'series_objetivo' => $request->series,
            'repeticiones_objetivo' => $request->reps,
            'peso_objetivo_kg' => $request->peso,
            'descanso_segundos' => $request->descanso,
            'notas_ejercicio' => $request->notas,
        ]);
        return back()->with('success', 'Ejercicio actualizado.');
    })->name('rutina.exercise.update');


    Route::middleware(['admin'])->group(function () {
        Route::view('panel-admin', 'panel-admin')->name('panel-admin');
        // Add more admin-only routes here
    });

    Route::get('perfil', [ProfileController::class, 'edit'])->name('perfil');
    Route::put('perfil', [ProfileController::class, 'update'])->name('perfil.update');
    Route::view('ajustes', 'ajustes')->name('ajustes');
});

require __DIR__ . '/settings.php';


