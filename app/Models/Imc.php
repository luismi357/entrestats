<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imc extends Model
{
    use HasFactory;
    protected $table = 'imc';

    protected $fillable = [
        'id',
        'user_id',
        'cms',
        'kgs',
        'resultado',
        'created_at',
        'updated_at',
        'sexo',
    ];
    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
