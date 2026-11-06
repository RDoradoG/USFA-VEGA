<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;

class CarreraController extends Controller
{
    public function getRecordsApi(Request $request)
    {
        return response()->json(Carrera::get());
    }

    public static function getAllMap()
    {
        $carreras = Carrera::get();
        $map = [];

        foreach($carreras as $carrera) $map[$carrera->external_id] = $carrera->id;

        return $map;
    }
}
