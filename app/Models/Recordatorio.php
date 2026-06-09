<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recordatorio extends Model
{
    use HasFactory;

    protected $table = 'recordatorios';

    protected $fillable = [
        'lead_id',
        'usuario_id',
        'fecha_recordatorio',
        'descripcion',
        'completado'
    ];

    protected $casts = [
        'fecha_recordatorio' => 'datetime',
        'completado' => 'boolean'
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