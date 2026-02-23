<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Str;

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

        // Remove the uploaded file object — we only store the derived path string
        unset($validated['foto_perfil']);

        if ($request->hasFile('foto_perfil')) {
            // Delete previous photo if it exists
            if ($perfil && $perfil->ruta_foto_perfil_usuario) {
                $oldPath = public_path($perfil->ruta_foto_perfil_usuario);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Store directly in public/avatars/ — no symlink required
            $avatarDir = public_path('avatars');
            if (!File::isDirectory($avatarDir)) {
                File::makeDirectory($avatarDir, 0755, true);
            }

            $filename = Str::random(40) . '.' . $request->file('foto_perfil')->getClientOriginalExtension();
            $request->file('foto_perfil')->move($avatarDir, $filename);

            // Store as a relative path from public root
            $validated['ruta_foto_perfil_usuario'] = 'avatars/' . $filename;
        }

        $user->perfil()->updateOrCreate(
            ['id_usuario' => $user->id_usuario],
            $validated
        );

        return back()->with('status', 'perfil-updated');
    }
}
