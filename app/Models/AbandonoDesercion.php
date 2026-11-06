<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonoDesercion extends Model
{
    protected $table = 'abandonos_deserciones';

    protected $fillable = [
        'seguimiento_estudiante_id',
        'sede_id',
        'carrera_id',
        'seguimiento_tipo_id'//,
       //'seguimiento_estado_id'
    ];

    public function seguimientoTipo()
    {
        return $this->belongsTo(SeguimientoTipo::class);
    }

    /*public function seguimientoEstado()
    {
        return $this->belongsTo(SeguimientoEstado::class);
    }*/

    public function seguimientoDeserciones()
    {
        return $this->hasMany(SeguimientoDesercion::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }
}
