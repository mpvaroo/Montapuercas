<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')->name('dashboard');
Route::view('rutinas', 'rutinas')->name('rutinas');
Route::view('reservas', 'reservas')->name('reservas');
Route::view('calendario', 'calendario')->name('calendario');
Route::view('progreso', 'progreso')->name('progreso');
Route::view('ia-coach', 'iaCoach')->name('ia-coach');
Route::view('detalle-reserva', 'detalle-reserva')->name('detalle-reserva');
Route::view('detalle-rutina', 'detalle-rutina')->name('detalle-rutina');
Route::view('panel-admin', 'panel-admin')->name('panel-admin');

require __DIR__ . '/settings.php';
