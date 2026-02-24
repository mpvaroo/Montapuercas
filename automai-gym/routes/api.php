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

// ── IA Coach (autenticado via sesión web) ──────────────────────────────────
Route::middleware(['web', 'auth'])->prefix('ia')->group(function () {
    Route::post('/chat', [\App\Http\Controllers\Api\IaCoachController::class, 'chat']);
    Route::get('/conversations', [\App\Http\Controllers\Api\IaCoachController::class, 'listConversations']);
    Route::post('/conversations', [\App\Http\Controllers\Api\IaCoachController::class, 'createConversation']);
    Route::get('/conversations/{id}/messages', [\App\Http\Controllers\Api\IaCoachController::class, 'messagesPaginated']);
    Route::delete('/conversations/{id}', [\App\Http\Controllers\Api\IaCoachController::class, 'deleteConversation']);
});

