<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'sexo',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return url('storage/profile_photos/' . $this->photo);
        }
        return null;
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function imc()
    {
        return $this->hasMany(Imc::class, 'user_id');
    }

    public function adminlte_image()
{
    if ($this->photo) {
         return Storage::url('profile_photos/' . $this->photo);
    }

    return 'https://i.pravatar.cc/300';
}

public function adminlte_desc()
{
    return 'Usuario';
}

public function adminlte_profile_url()
{
    return 'profile';
}
}
