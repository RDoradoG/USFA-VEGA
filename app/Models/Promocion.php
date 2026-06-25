<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'beca',
        'activo'
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
