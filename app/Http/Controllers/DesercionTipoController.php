<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\DesercionTipo;

class DesercionTipoController extends Controller
{
    public static function getAllMap()
    {
        $attritionTypes = DesercionTipo::get();
        $map = [];

        foreach($attritionTypes as $attritionType) $map[$attritionType->nombre] = $attritionType->id;

        return $map;
    }
}
