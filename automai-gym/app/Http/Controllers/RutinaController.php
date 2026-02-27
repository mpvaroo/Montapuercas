<?php

namespace App\Http\Controllers;

use App\Models\RutinaUsuario;
use Illuminate\Http\Request;

class RutinaController extends Controller
{
    /**
     * Update the routine primary details.
     */
    public function update(Request $request, $id)
    {
        $request->validateWithBag('updateRoutine', [
            'nombre_rutina_usuario' => 'required|string|max:255',
            'objetivo_rutina_usuario' => 'required|string|in:salud,definir,volumen,rendimiento',
            'nivel_rutina_usuario' => 'required|string|in:principiante,intermedio,avanzado',
            'duracion_estimada_minutos' => 'nullable|integer|min:1|max:480',
            'dia_semana' => 'required|string|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo,descanso',
        ], [
            'nombre_rutina_usuario.required' => 'El nombre de la rutina es obligatorio.',
            'objetivo_rutina_usuario.in' => 'El objetivo seleccionado no es válido.',
            'nivel_rutina_usuario.in' => 'El nivel seleccionado no es válido.',
            'duracion_estimada_minutos.min' => 'La duración debe ser de al menos 1 minuto.',
            'duracion_estimada_minutos.max' => 'La duración no puede exceder las 8 horas.',
            'dia_semana.in' => 'El día de la semana seleccionado no es válido.',
        ]);

        $routine = RutinaUsuario::findOrFail($id);

        if ($routine->origen_rutina === 'plantilla') {
            return back()->with('error', 'No puedes modificar una plantilla p\u00fablica.');
        }

        $routine->update($request->only([
            'nombre_rutina_usuario',
            'objetivo_rutina_usuario',
            'nivel_rutina_usuario',
            'duracion_estimada_minutos',
            'instrucciones_rutina',
            'dia_semana'
        ]));

        return back()->with('success', 'Rutina actualizada correctamente.');
    }

    /**
     * Add an exercise to the routine.
     */
    public function addExercise(Request $request, $id)
    {
        $request->validateWithBag('addExercise', [
            'id_ejercicio' => 'required|exists:ejercicios,id_ejercicio',
            'series' => 'required|integer|min:1|max:50',
            'reps' => 'required|integer|min:1|max:999',
            'peso' => 'nullable|numeric|min:0|max:1000',
            'descanso' => 'nullable|integer|min:0|max:3600',
            'notas' => 'nullable|string|max:500',
        ], [
            'id_ejercicio.required' => 'Debes seleccionar un ejercicio.',
            'id_ejercicio.exists' => 'El ejercicio seleccionado no existe.',
            'series.min' => 'Las series deben ser al menos 1.',
            'series.required' => 'Las series son obligatorias.',
            'reps.required' => 'Las repeticiones son obligatorias.',
            'reps.integer' => 'Las repeticiones deben ser un número entero.',
            'reps.min' => 'Las repeticiones deben ser al menos 1.',
            'reps.max' => 'Las repeticiones no pueden superar 999.',
            'peso.min' => 'El peso no puede ser negativo.',
            'descanso.min' => 'El descanso no puede ser negativo.',
        ]);

        $routine = RutinaUsuario::findOrFail($id);

        if ($routine->origen_rutina === 'plantilla') {
            return back()->with('error', 'No puedes modificar los ejercicios de una plantilla p\u00fablica.');
        }

        $routine->ejercicios()->attach($request->id_ejercicio, [
            'series_objetivo' => $request->series,
            'repeticiones_objetivo' => $request->reps,
            'peso_objetivo_kg' => $request->peso,
            'descanso_segundos' => $request->descanso,
            'notas_ejercicio' => $request->notas,
            'orden_en_rutina' => $routine->ejercicios()->count() + 1
        ]);

        return back()->with('success', 'Ejercicio añadido correctamente.');
    }

    /**
     * Update an exercise within a routine.
     */
    public function updateExercise(Request $request, $id, $exercise_id)
    {
        $request->validateWithBag('updateExercise', [
            'series' => 'required|integer|min:1|max:50',
            'reps' => 'required|integer|min:1|max:999',
            'peso' => 'nullable|numeric|min:0|max:1000',
            'descanso' => 'nullable|integer|min:0|max:3600',
            'notas' => 'nullable|string|max:500',
        ], [
            'series.min' => 'Las series deben ser al menos 1.',
            'series.required' => 'Las series son obligatorias.',
            'reps.required' => 'Las repeticiones son obligatorias.',
            'reps.integer' => 'Las repeticiones deben ser un número entero.',
            'reps.min' => 'Las repeticiones deben ser al menos 1.',
            'reps.max' => 'Las repeticiones no pueden superar 999.',
            'peso.min' => 'El peso no puede ser negativo.',
            'descanso.min' => 'El descanso no puede ser negativo.',
        ]);

        $routine = RutinaUsuario::findOrFail($id);

        if ($routine->origen_rutina === 'plantilla') {
            return back()->with('error', 'No puedes modificar los ejercicios de una plantilla p\u00fablica.');
        }

        $routine->ejercicios()->updateExistingPivot($exercise_id, [
            'series_objetivo' => $request->series,
            'repeticiones_objetivo' => $request->reps,
            'peso_objetivo_kg' => $request->peso,
            'descanso_segundos' => $request->descanso,
            'notas_ejercicio' => $request->notas,
        ]);

        return back()->with('success', 'Detalle del ejercicio actualizado.');
    }

    /**
     * Remove an exercise from a routine (blocked for plantillas).
     */
    public function removeExercise(Request $request, $id, $exercise_id)
    {
        $routine = RutinaUsuario::findOrFail($id);

        if ($routine->origen_rutina === 'plantilla') {
            return back()->with('error', 'No puedes modificar los ejercicios de una plantilla pública.');
        }

        $routine->ejercicios()->detach($exercise_id);

        return back()->with('success', 'Ejercicio eliminado de la rutina.');
    }
}
