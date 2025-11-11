<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estadisticas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'id_user',
        'grupo_muscular_id',
        'ejercicio_id',
        'peso',
        'series',
        'reps',
        'dia',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function grupoMuscular()
    {
        return $this->belongsTo(GrupoMuscular::class, 'grupo_muscular_id');
    }
    // 🔹 Relación con EjercicioPorGrupoMuscular
    public function ejercicio()
    {
        return $this->belongsTo(EjercicioPorGrupoMuscular::class, 'ejercicio_id');
    }
}
