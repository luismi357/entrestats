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
}
