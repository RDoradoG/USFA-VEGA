<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fuente;

class FuenteController extends Controller
{
    public function getRecordsApi(Request $request)
    {
        return response()->json(Fuente::get());
    }
}
