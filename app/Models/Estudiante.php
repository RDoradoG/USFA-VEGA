<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Estudiante extends Model
{
    protected $table = 'estudiantes';

    protected $fillable = [
        'id_persona',
        'nombres',
        'apellidos',
        'telefonos',
        'email',
        'genero',
        'codigo_pais',
        'ciudad'
    ];

    protected $appends = ['full_name'];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim(implode(' ', array_filter([
                $this->nombres,
                $this->apellidos
            ])))
        );
    }

    public function seguimientoEstudiantes()
    {
        return $this->hasMany(SeguimientoEstudiante::class);
    }
}
