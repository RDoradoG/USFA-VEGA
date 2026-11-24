<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonoDesercion extends Model
{
    protected $table = 'abandonos_deserciones';

    protected $fillable = [
        'seguimiento_estudiante_id',
        'sede_id',
        'carrera_simbolo_id',
        'seguimiento_tipo_id'
    ];

    public function seguimientoTipo()
    {
        return $this->belongsTo(SeguimientoTipo::class);
    }

    public function seguimientoDeserciones()
    {
        return $this->hasMany(SeguimientoDesercion::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function carreraSimbolo()
    {
        return $this->belongsTo(CarreraSimbolo::class);
    }
}
