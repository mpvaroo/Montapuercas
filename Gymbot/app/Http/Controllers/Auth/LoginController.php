<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Procesar el login (sin validación por ahora)
     * Solo redirige al index para probar la vista
     */
    public function login(Request $request)
    {
        // Por ahora solo redirigimos al index sin validar
        return redirect()->route('index')
            ->with('success', '¡Bienvenido a GymBot!');
    }

    /**
     * Cerrar sesión del usuario
     */
    public function logout(Request $request)
    {
        return redirect()->route('login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}
