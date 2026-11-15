<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class carreraSimbolo extends Model
{
    protected $table = 'carrera_simbolos';

    protected $fillable = [
        'simbolo',
        'carrera_id',
        'programa_id',
        'activo',
        'external_id'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];


    public function carrera()
    {
        return $this->belongsTo(carrera::class, 'carrera_id');
    }

    public function programa()
    {
        return $this->belongsTo(programa::class, 'programa_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function abandonoDeserciones()
    {
        return $this->hasMany(AbandonoDesercion::class);
    }

    public function estudianteCarreras()
    {
        return $this->hasMany(EstudianteCarrera::class);
    }
}
