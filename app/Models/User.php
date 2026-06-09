<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Notifications\Notifiable;
//use Laravel\Fortify\TwoFactorAuthenticatable;

//#[Fillable(['nombre', 'email', 'password', 'activo'])]
//#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /*protected function casts(): array
    {
        return [
            //'email_verified_at' => 'datetime',
            'password' => 'hashed',
            //'two_factor_confirmed_at' => 'datetime',
        ];
    }*/

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'activo'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'password' => 'hashed'
    ];

    // 🔗 Relaciones
    public function leads()
    {
        return $this->hasMany(Lead::class, 'usuario_id');
    }

    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class, 'usuario_id');
    }

    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class, 'usuario_id');
    }

    // 🧠 Helpers
    public function isJefe()
    {
        return $this->rol === 'JEFE';
    }

    public function isAsesor()
    {
        return $this->rol === 'ASESOR';
    }
}
