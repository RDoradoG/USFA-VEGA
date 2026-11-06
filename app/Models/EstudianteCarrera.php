<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstudianteCarrera extends Model
{
    protected $table = 'estudiante_carreras';

    protected $fillable = [
        'estudiante_id',
        'sede_id',
        'carrera_id'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    //** Old tables */

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

}
