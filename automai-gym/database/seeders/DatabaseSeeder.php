<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rol;
use App\Models\Ejercicio;
use App\Models\ClaseGimnasio;
use App\Models\TipoClase;
use App\Models\RutinaUsuario;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Cargar datos base (Roles, Tipos de Clase, Ejercicios básicos)
        $this->call(SqlDataSeeder::class);

        // 2. Crear Usuarios específicos

        // ADMIN
        $adminRol = Rol::where('nombre_rol', 'admin')->first();
        $admin = User::firstOrCreate(
            ['correo_usuario' => 'admin@automai.com'],
            [
                'nombre_mostrado_usuario' => 'Administrador',
                'hash_contrasena_usuario' => Hash::make('admin123'),
                'estado_usuario' => 'activo',
                'fecha_creacion_usuario' => now(),
            ]
        );
        if (!$admin->roles()->where('roles.id_rol', $adminRol->id_rol)->exists()) {
            $admin->roles()->attach($adminRol->id_rol);
        }

        // USUARIO NORMAL (MARIO)
        $userRol = Rol::where('nombre_rol', 'usuario')->first();
        $mario = User::firstOrCreate(
            ['correo_usuario' => 'mario@gmail.com'],
            [
                'nombre_mostrado_usuario' => 'Mario User',
                'hash_contrasena_usuario' => Hash::make('1234'),
                'estado_usuario' => 'activo',
                'fecha_creacion_usuario' => now(),
            ]
        );
        if (!$mario->roles()->where('roles.id_rol', $userRol->id_rol)->exists()) {
            $mario->roles()->attach($userRol->id_rol);
        }

        // 3. Generar algunos usuarios extra (solo si hay menos de 5)
        if (User::count() < 5) {
            User::factory(3)->create()->each(function ($u) use ($userRol) {
                $u->roles()->attach($userRol->id_rol);
            });
        }

        // 4. Generar Clases (solo si hay pocas)
        if (ClaseGimnasio::count() < 5) {
            $tipos = TipoClase::all();
            ClaseGimnasio::factory(10)->create([
                'id_tipo_clase' => fn() => $tipos->random()->id_tipo_clase
            ]);
        }

        // 5. Generar Rutinas para Mario con Ejercicios (solo si no tiene)
        if ($mario->rutinas()->count() == 0) {
            $ejercicios = Ejercicio::all();
            RutinaUsuario::factory(2)->create([
                'id_usuario' => $mario->id_usuario
            ])->each(function ($rutina) use ($ejercicios) {
                $rutina->ejercicios()->attach(
                    $ejercicios->random(min(3, $ejercicios->count()))->pluck('id_ejercicio')->toArray(),
                    [
                        'orden_en_rutina' => 1,
                        'series_objetivo' => 3,
                        'repeticiones_objetivo' => '12',
                        'peso_objetivo_kg' => 20
                    ]
                );
            });
        }
    }
}
