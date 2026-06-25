<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'monto',
        'numero_factura',
        'cuf_id',
        'lead_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
