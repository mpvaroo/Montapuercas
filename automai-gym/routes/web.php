<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasController;


Route::middleware(['auth',])->group(function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('rutinas', function () {
        $routines = auth()->user()->rutinas()->with('ejercicios')->get();
        return view('rutinas', compact('routines'));
    })->name('rutinas');
    Route::get('reservas', [ReservasController::class, 'index'])->name('reservas');
    Route::post('reservas/{clase}/apuntar', [ReservasController::class, 'store'])->name('reservas.apuntar');
    Route::delete('reservas/{clase}/cancelar', [ReservasController::class, 'destroy'])->name('reservas.cancelar');
    Route::get('calendario', [ReservasController::class, 'calendario'])->name('calendario');
    Route::view('progreso', 'progreso')->name('progreso');
    Route::view('ia-coach', 'iaCoach')->name('ia-coach');
    Route::get('detalle-reserva/{id}', [ReservasController::class, 'show'])->name('detalle-reserva');
    Route::get('detalle-rutina/{id}', function ($id) {
        $routine = \App\Models\RutinaUsuario::with('ejercicios')->findOrFail($id);
        return view('detalle-rutina', compact('routine'));
    })->name('detalle-rutina');

    Route::middleware(['admin'])->group(function () {
        Route::view('panel-admin', 'panel-admin')->name('panel-admin');
        // Add more admin-only routes here
    });

    Route::get('perfil', [ProfileController::class, 'edit'])->name('perfil');
    Route::put('perfil', [ProfileController::class, 'update'])->name('perfil.update');
    Route::view('ajustes', 'ajustes')->name('ajustes');
});

require __DIR__ . '/settings.php';
