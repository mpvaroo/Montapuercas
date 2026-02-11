<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SqlDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. ROLES
        DB::table('roles')->insertOrIgnore([
            ['nombre_rol' => 'usuario', 'descripcion_rol' => 'Acceso a zona usuario'],
            ['nombre_rol' => 'admin', 'descripcion_rol' => 'Acceso a panel admin'],
        ]);

        // 2. TIPOS CLASE
        DB::table('tipos_clase')->insertOrIgnore([
            ['nombre_tipo_clase' => 'Yoga Flow', 'descripcion_tipo_clase' => 'Movilidad y respiración'],
            ['nombre_tipo_clase' => 'HIIT Express', 'descripcion_tipo_clase' => 'Alta intensidad 30-45 min'],
            ['nombre_tipo_clase' => 'Fuerza', 'descripcion_tipo_clase' => 'Fuerza guiada'],
            ['nombre_tipo_clase' => 'Spinning', 'descripcion_tipo_clase' => 'Ciclismo indoor'],
        ]);

        // 3. EJERCICIOS
        DB::table('ejercicios')->insertOrIgnore([
            ['nombre_ejercicio' => 'Press banca', 'grupo_muscular_principal' => 'pecho', 'descripcion_ejercicio' => 'Press en banco con barra'],
            ['nombre_ejercicio' => 'Dominadas', 'grupo_muscular_principal' => 'espalda', 'descripcion_ejercicio' => 'Tracción vertical con peso corporal'],
            ['nombre_ejercicio' => 'Sentadilla', 'grupo_muscular_principal' => 'pierna', 'descripcion_ejercicio' => 'Sentadilla con barra'],
            ['nombre_ejercicio' => 'Curl bíceps', 'grupo_muscular_principal' => 'biceps', 'descripcion_ejercicio' => 'Curl con mancuernas o barra'],
            ['nombre_ejercicio' => 'Plancha', 'grupo_muscular_principal' => 'core', 'descripcion_ejercicio' => 'Isométrico de core'],
        ]);
    }
}
