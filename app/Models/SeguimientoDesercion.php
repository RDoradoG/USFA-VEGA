<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoDesercion extends Model
{
    protected $table = 'seguimiento_deserciones';

    protected $fillable = [
        'abandono_desercion_id',
        'desercion_tipo_id',
        'materia',
        'nota'
    ];

    public function abandonoDesercion()
    {
        return $this->belongsTo(AbandonoDesercion::class);
    }

    public function desercionTipo()
    {
        return $this->belongsTo(DesercionTipo::class);
    }
}
