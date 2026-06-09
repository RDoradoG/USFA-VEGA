<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fuente extends Model
{
    use HasFactory;

    protected $table = 'fuentes';

    protected $fillable = [
        'nombre'
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}