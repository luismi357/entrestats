<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ejercicios = App\Models\EjercicioPorGrupoMuscular::all();
foreach ($ejercicios as $ej) {
    echo $ej->id . ': ' . $ej->nombre_ejercicio . ' (grupo_id: ' . $ej->grupo_muscular_id . ')' . "\n";
}
