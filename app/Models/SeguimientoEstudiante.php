<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoEstudiante extends Model
{
    protected $table = 'seguimiento_estudiantes';

    protected $fillable = [
        'estudiante_id',
        'usuario_id',
        'motivo',
        'ultimo_contacto',
        'observaciones',
        'seguimiento_estado_id'
    ];

    protected $casts = [
        'ultimo_contacto' => 'datetime'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class);
    }

    public function abandonosDeserciones()
    {
        return $this->hasMany(AbandonoDesercion::class);
    }

    public function histories()
    {
        return $this->hasMany(HistoryLead::class);
    }

    public function seguimientoEstado()
    {
        return $this->belongsTo(SeguimientoEstado::class);
    }

    //** Old tables */

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
