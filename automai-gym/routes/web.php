<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth',])->group(function () {

    Route::get('/', function () {
        return view('welcome');
    })->name('home');
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('rutinas', 'rutinas')->name('rutinas');
    Route::view('reservas', 'reservas')->name('reservas');
    Route::view('calendario', 'calendario')->name('calendario');
    Route::view('progreso', 'progreso')->name('progreso');
    Route::view('ia-coach', 'iaCoach')->name('ia-coach');
    Route::view('detalle-reserva', 'detalle-reserva')->name('detalle-reserva');
    Route::view('detalle-rutina', 'detalle-rutina')->name('detalle-rutina');

    Route::middleware(['admin'])->group(function () {
        Route::view('panel-admin', 'panel-admin')->name('panel-admin');
        // Add more admin-only routes here
    });

    Route::get('perfil', [ProfileController::class, 'edit'])->name('perfil');
    Route::put('perfil', [ProfileController::class, 'update'])->name('perfil.update');
    Route::view('ajustes', 'ajustes')->name('ajustes');
});

require __DIR__ . '/settings.php';
