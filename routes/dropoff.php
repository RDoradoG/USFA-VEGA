<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeguimientoEstudianteController;
use App\Http\Controllers\SeguimientoController;

Route::middleware(['auth'])->group(function () {
    Route::resource('dropoffs', SeguimientoEstudianteController::class);
    Route::post('/dropoffs/{dropoff}/seguimientos', [SeguimientoController::class, 'storeDropoff']);
});