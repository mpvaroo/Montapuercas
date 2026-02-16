<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EjerciciosSeeder extends Seeder
{
    public function run()
    {
        $ejercicios = [
            // Pecho
            ['nombre_ejercicio' => 'Press inclinado', 'grupo_muscular_principal' => 'pecho', 'descripcion_ejercicio' => 'Press superior con mancuernas'],
            ['nombre_ejercicio' => 'Aperturas', 'grupo_muscular_principal' => 'pecho', 'descripcion_ejercicio' => 'Aislamiento en banco plano'],
            ['nombre_ejercicio' => 'Fondos', 'grupo_muscular_principal' => 'pecho', 'descripcion_ejercicio' => 'Fondos en paralelas'],
            
            // Espalda
            ['nombre_ejercicio' => 'Remo con barra', 'grupo_muscular_principal' => 'espalda', 'descripcion_ejercicio' => 'Remo inclinado con barra'],
            ['nombre_ejercicio' => 'Jalón al pecho', 'grupo_muscular_principal' => 'espalda', 'descripcion_ejercicio' => 'Polea alta al pecho'],
            ['nombre_ejercicio' => 'Peso muerto', 'grupo_muscular_principal' => 'espalda', 'descripcion_ejercicio' => 'Levantamiento desde el suelo'],
            
            // Pierna
            ['nombre_ejercicio' => 'Prensa', 'grupo_muscular_principal' => 'pierna', 'descripcion_ejercicio' => 'Prensa inclinada 45 grados'],
            ['nombre_ejercicio' => 'Zancadas', 'grupo_muscular_principal' => 'pierna', 'descripcion_ejercicio' => 'Caminata con peso'],
            ['nombre_ejercicio' => 'Hip Thrust', 'grupo_muscular_principal' => 'pierna', 'descripcion_ejercicio' => 'Empuje de cadera para glúteo'],
            ['nombre_ejercicio' => 'Extensión cuádriceps', 'grupo_muscular_principal' => 'pierna', 'descripcion_ejercicio' => 'Máquina de extensiones'],
            
            // Hombro
            ['nombre_ejercicio' => 'Press Militar', 'grupo_muscular_principal' => 'hombro', 'descripcion_ejercicio' => 'Press vertical con barra'],
            ['nombre_ejercicio' => 'Elevaciones laterales', 'grupo_muscular_principal' => 'hombro', 'descripcion_ejercicio' => 'Vuelos con mancuernas'],
            
            // Brazos
            ['nombre_ejercicio' => 'Curl Martillo', 'grupo_muscular_principal' => 'biceps', 'descripcion_ejercicio' => 'Agarre neutro'],
            ['nombre_ejercicio' => 'Press Francés', 'grupo_muscular_principal' => 'triceps', 'descripcion_ejercicio' => 'Barra Z a la frente'],
            ['nombre_ejercicio' => 'Polea Tríceps', 'grupo_muscular_principal' => 'triceps', 'descripcion_ejercicio' => 'Extensiones con cuerda'],
            
            // Core
            ['nombre_ejercicio' => 'Elevación de piernas', 'grupo_muscular_principal' => 'core', 'descripcion_ejercicio' => 'Colgado o en suelo'],
            ['nombre_ejercicio' => 'Russian Twist', 'grupo_muscular_principal' => 'core', 'descripcion_ejercicio' => 'Giros para oblicuos'],
        ];

        DB::table('ejercicios')->insert($ejercicios); // Asegúrate de que tu tabla se llame 'ejercicios'
    }
}
