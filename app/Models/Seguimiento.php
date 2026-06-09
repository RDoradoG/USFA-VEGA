<?php

Namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seguimiento extends Model
{
    use HasFactory;

    protected $table = 'seguimientos';

    protected $fillable = [
        'lead_id',
        'usuario_id',
        'tipo',
        'resultado',
        'observacion',
        'fecha_contacto'
    ];

    protected $casts = [
        'fecha_contacto' => 'datetime'
    ];

    // 🔗 Relaciones
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}