<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/contenido', 'contenido');
Route::view('/exports', 'exports');
Route::view('/forgot-password', 'forgotPassword');
Route::view('/generar', 'generar');
Route::view('/index', 'index');
Route::view('/login', 'login');
Route::view('/logout', 'logout');
Route::view('/mis-reservas', 'mis_reservas');
Route::view('/mis-rutinas', 'mis_rutinas');
Route::view('/nueva', 'nueva');
Route::view('/register', 'register');
Route::view('/usuarios', 'usuarios');
