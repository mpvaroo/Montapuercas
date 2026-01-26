<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/usuarios', 'usuarios');
Route::get('/fitness-home', function () {
    return view('fitness-home');
});

Route::view('dashboard', 'dashboard')
    ->name('dashboard');

require __DIR__ . '/settings.php';
