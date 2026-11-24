<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class programa extends Model
{
    protected $table = 'programas';

    protected $fillable = [
        'nombre',
        'anio',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function carreraSimbolos()
    {
        return $this->hasMany(CarreraSimbolo::class);
    }
}
