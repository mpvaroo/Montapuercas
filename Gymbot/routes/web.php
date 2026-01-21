<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación (sin middleware por ahora)
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('forgotPassword');
})->name('forgot.password');

// Rutas de contenido principal
Route::get('/index', function () {
    return view('index');
})->name('index');

Route::get('/contenido', function () {
    return view('contenido');
})->name('contenido');

// Rutas de rutinas
Route::get('/generar', function () {
    return view('generar');
})->name('generar');

Route::get('/mis-rutinas', function () {
    return view('mis_rutinas');
})->name('mis.rutinas');

Route::get('/nueva', function () {
    return view('nueva');
})->name('nueva');

// Rutas de reservas
Route::get('/mis-reservas', function () {
    return view('mis_reservas');
})->name('mis.reservas');

// Rutas de administración
Route::get('/usuarios', function () {
    return view('usuarios');
})->name('usuarios');

Route::get('/exports', function () {
    return view('exports');
})->name('exports');
