<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesercionTipo extends Model
{
    protected $table = 'desercion_tipos';

    protected $fillable = [
        'nombre'
    ];

    public function seguimientoDeserciones()
    {
        return $this->hasMany(SeguimientoDesercion::class);
    }
}
