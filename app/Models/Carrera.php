<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
        'external_id'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}