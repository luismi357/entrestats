<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'respuestas',
    ];

    protected $casts = [
        'respuestas' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
