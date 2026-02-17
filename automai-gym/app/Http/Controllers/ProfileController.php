<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        return view('perfil');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $perfil = $user->perfil;

        $validated = $request->validated();

        if ($request->hasFile('foto_perfil')) {
            // Eliminar foto anterior si existe
            if ($perfil && $perfil->ruta_foto_perfil_usuario) {
                Storage::disk('public')->delete($perfil->ruta_foto_perfil_usuario);
            }

            // Guardar nueva foto
            $path = $request->file('foto_perfil')->store('avatars', 'public');
            $validated['ruta_foto_perfil_usuario'] = $path;
        }

        $user->perfil()->updateOrCreate(
            ['id_usuario' => $user->id_usuario],
            $validated
        );

        return back()->with('status', 'perfil-updated');
    }
}
