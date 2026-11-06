<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoEstado extends Model
{
    protected $table = 'seguimiento_estados';

    protected $fillable = [
        'nombre',
        'color',
        'orden'
    ];

    public function abandonosDeserciones()
    {
        return $this->hasMany(AbandonoDesercion::class);
    }
}
