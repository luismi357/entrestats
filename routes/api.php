<?php
use App\Models\EjercicioPorGrupoMuscular;

Route::get('/ejercicios/{grupo}', function ($grupo) {
    return EjercicioPorGrupoMuscular::where('grupo_muscular_id', $grupo)
        ->orderBy('nombre_ejercicio')
        ->get(['id', 'nombre_ejercicio']);
})
?>