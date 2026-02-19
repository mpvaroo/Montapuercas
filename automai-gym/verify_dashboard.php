<?php

use App\Models\User;
use App\Models\RutinaUsuario;
use App\Models\RegistroProgreso;
use Carbon\Carbon;

function verify()
{
    echo "Starting verification...\n";

    $user = User::first();
    if (!$user) {
        echo "Error: No user found in database.\n";
        return;
    }
    echo "User found: " . $user->email . "\n";

    // 1. Check relationships
    echo "Checking rutinas relationship...\n";
    $rutinaCount = $user->rutinas()->count();
    echo "Count of routines: " . $rutinaCount . "\n";

    echo "Checking registrosProgreso relationship...\n";
    $progressCount = $user->registrosProgreso()->count();
    echo "Count of progress records: " . $progressCount . "\n";

    // 2. Check today's routine logic
    $daysMap = [
        1 => 'lunes',
        2 => 'martes',
        3 => 'miercoles',
        4 => 'jueves',
        5 => 'viernes',
        6 => 'sabado',
        0 => 'domingo',
    ];
    $todayName = $daysMap[Carbon::now()->dayOfWeek];
    echo "Today is: " . $todayName . "\n";

    $routineToday = $user->rutinas()
        ->where('rutina_activa', true)
        ->where('dia_semana', $todayName)
        ->first();

    if ($routineToday) {
        echo "Routine for today found: " . $routineToday->nombre_rutina_usuario . "\n";
    } else {
        echo "No routine scheduled for today (Rest day).\n";
    }

    // 3. Check random progress logic
    $lastProgress = $user->registrosProgreso()
        ->orderBy('fecha_registro', 'desc')
        ->first();

    if ($lastProgress) {
        echo "Last progress record found for: " . $lastProgress->fecha_registro->format('Y-m-d') . "\n";
        $fields = [
            'Peso' => $lastProgress->peso_kg_registro,
            'Cintura' => $lastProgress->cintura_cm_registro,
            'Pecho' => $lastProgress->pecho_cm_registro,
            'Cadera' => $lastProgress->cadera_cm_registro,
        ];
        print_r(array_filter($fields));
    } else {
        echo "No progress records found.\n";
    }

    echo "Verification complete.\n";
}

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

verify();
