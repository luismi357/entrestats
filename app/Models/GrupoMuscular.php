<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoMuscular extends Model
{
    
    protected $table = 'grupos_musculares';

    protected $fillable = [
        'nombre_grupo',
    ];

    public function ejercicios()
{
    return $this->hasMany(EjercicioPorGrupoMuscular::class, 'grupo_muscular_id');
}
}
