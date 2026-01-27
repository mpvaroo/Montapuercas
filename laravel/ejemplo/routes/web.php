<?php

use App\Http\Middleware\mayoredad;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('contacto/{nombre?}/{edad?}', function ($nombre = "pepe", $edad = 123) {
    return view('contacto', compact('nombre', 'edad'));
    //return view('contacto')->with('nombre' , $nombre)->with('edad', $edad);
})->where(['nombre' => '[a-zA-Z]+', 'edad' => '[0-9]+'])->name('contacto')->middleware('mayoredad:25');

Route::get('alerta', function () {
    return view('vista_alert');
});
