<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Lead extends Model
{
    use HasFactory;

    protected $table = 'leads';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'celular',
        'genero',
        'ciudad',
        'sede_id',
        'carrera_id',
        'horario_id',
        'estado_id',
        'usuario_id',
        'fuente_id',
        'interes_nivel',
        'fecha_registro',
        'ultimo_contacto',
        'observaciones'
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'ultimo_contacto' => 'datetime'
    ];

    protected $with = ['estado', 'sede', 'carrera', 'asesor'];

    protected $appends = ['full_name'];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim(implode(' ', array_filter([
                $this->nombre,
                $this->apellido_paterno,
                $this->apellido_materno
            ])))
        );
    }

    // 🔗 Relaciones
    public function asesor()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function fuente()
    {
        return $this->belongsTo(Fuente::class);
    }

    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class);
    }

    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class);
    }

    // 🧠 Scopes útiles
    public function scopeSinContacto($query)
    {
        return $query->whereNull('ultimo_contacto');
    }

    public function scopeAsignados($query, $userId)
    {
        return $query->where('usuario_id', $userId);
    }

    // 🧠 Accesor
    public function getTieneContactoAttribute()
    {
        return !is_null($this->ultimo_contacto);
    }
}