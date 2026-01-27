<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('productos', 'productos')
    ->middleware(['auth'])
    ->name('productos');

require __DIR__ . '/settings.php';
