<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas para Clases y Reservas (Públicas para pruebas)
Route::get('/clases', [\App\Http\Controllers\Api\ApiClaseController::class, 'index']);
Route::get('/clases/{id}', [\App\Http\Controllers\Api\ApiClaseController::class, 'show']);
Route::post('/reservas', [\App\Http\Controllers\Api\ApiReservaController::class, 'store']);
