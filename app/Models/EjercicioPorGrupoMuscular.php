<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EjercicioPorGrupoMuscular extends Model
{
    protected $table = 'ejercicios_por_grupo_muscular';

    protected $fillable = [
        'grupo_muscular_id',
        'nombre_ejercicio',
    ];

    public function grupoMuscular()
    {
        return $this->belongsTo(GrupoMuscular::class, 'grupo_muscular_id');
    }
}
