<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sede;

class SedeController extends Controller
{
    public function getRecordsApi(Request $request)
    {
        return response()->json(Sede::get());
    }

    public static function getAllMap()
    {
        $sedes = Sede::get();
        $map = [];

        foreach($sedes as $sede) $map[$sede->external_id] = $sede->id;

        return $map;
    }
}
