<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\SeguimientoController;

Route::middleware(['auth'])->group(function () {
    Route::resource('leads', LeadController::class);
    Route::post('/leads/{lead}/seguimientos', [SeguimientoController::class, 'store']);
    Route::delete('/seguimientos/{seguimiento}', [SeguimientoController::class, 'destroy']);
});