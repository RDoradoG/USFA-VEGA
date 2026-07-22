<?php

Namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sede extends Model
{
    use HasFactory;

    protected $table = 'sedes';

    protected $fillable = [
        'nombre',
        'ciudad',
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