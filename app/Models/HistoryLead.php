<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryLead extends Model
{
    protected $table = 'history_leads';

    protected $fillable = [
        'mensaje',
        'lead_id',
        'usuario_id'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
