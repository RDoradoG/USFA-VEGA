<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigLead extends Model
{
    protected $table = 'config_lead';

    protected $fillable = [
        'dias_inactivo'
    ];
}
