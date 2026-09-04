<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\FuenteController;
use App\Http\Controllers\HorarioController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('leads', [LeadController::class, 'list']);
    Route::get('users', [UserController::class, 'list']);
    Route::get('promos', [PromocionController::class, 'list']);

    Route::put('promos/{id}/activate', [PromocionController::class, 'activate']);
    Route::put('promos/{id}/inactivate', [PromocionController::class, 'inactivate']);
});

Route::middleware('client')->group(function () {
    Route::post('pagos', [PagoController::class, 'store']);
    Route::post('leads/{id}', [LeadController::class, 'updateApi']);    
});

Route::middleware('superadmin.basic')->group(function () {
    Route::get('leadsPBI', [LeadController::class, 'getRecordsApi']);
    Route::get('sedesPBI', [SedeController::class, 'getRecordsApi']);
    Route::get('carrerasPBI', [CarreraController::class, 'getRecordsApi']);
    Route::get('estadosPBI', [EstadoController::class, 'getRecordsApi']);
    Route::get('fuentesPBI', [FuenteController::class, 'getRecordsApi']);
    Route::get('horariosPBI', [HorarioController::class, 'getRecordsApi']);
    Route::get('promocionesPBI', [PromocionController::class, 'getRecordsApi']);
    Route::get('usuariosPBI', [UserController::class, 'getRecordsApi']);
});