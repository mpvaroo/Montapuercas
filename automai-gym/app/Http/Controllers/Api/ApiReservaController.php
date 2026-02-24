<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReservaClase;
use App\Models\ClaseGimnasio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ApiReservaController extends Controller
{
    /**
     * Store a newly created reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_usuario' => 'required|integer|exists:usuarios,id_usuario',
            'id_clase_gimnasio' => 'required|integer|exists:clases_gimnasio,id_clase_gimnasio',
            'notas_reserva' => 'nullable|string|max:180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validación fallida',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user is already registered for this class
        $exists = ReservaClase::where('id_usuario', $request->id_usuario)
            ->where('id_clase_gimnasio', $request->id_clase_gimnasio)
            ->where('estado_reserva', '!=', 'cancelada')
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'El usuario ya tiene una reserva activa para esta clase.'
            ], 422);
        }

        // Check for capacity
        $clase = ClaseGimnasio::find($request->id_clase_gimnasio);
        $reservasCount = ReservaClase::where('id_clase_gimnasio', $request->id_clase_gimnasio)
            ->where('estado_reserva', 'reservada')
            ->count();

        if ($reservasCount >= $clase->cupo_maximo_clase) {
            return response()->json([
                'status' => 'error',
                'message' => 'La clase ya está llena.'
            ], 422);
        }

        $reserva = ReservaClase::create([
            'id_usuario' => $request->id_usuario,
            'id_clase_gimnasio' => $request->id_clase_gimnasio,
            'estado_reserva' => 'reservada',
            'origen_reserva' => 'usuario',
            'notas_reserva' => $request->notas_reserva,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reserva creada con éxito',
            'data' => $reserva
        ], 201);
    }



    
}
