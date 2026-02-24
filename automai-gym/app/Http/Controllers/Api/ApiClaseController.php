<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClaseGimnasio;
use Illuminate\Http\JsonResponse;

class ApiClaseController extends Controller
{
    /**
     * Display a listing of the gym classes.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $clases = ClaseGimnasio::with('tipoClase')
            ->where('estado_clase', 'publicada')
            ->orderBy('fecha_inicio_clase', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $clases
        ]);
    }

    /**
     * Display the specified gym class.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $clase = ClaseGimnasio::with('tipoClase')->find($id);

        if (!$clase) {
            return response()->json([
                'status' => 'error',
                'message' => 'Clase no encontrada'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $clase
        ]);
    }
}
