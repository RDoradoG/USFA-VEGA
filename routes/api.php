<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PromocionController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('leads', [LeadController::class, 'list']);
    Route::get('users', [UserController::class, 'list']);
    Route::get('promos', [PromocionController::class, 'list']);

    Route::put('promos/{id}/activate', [PromocionController::class, 'activate']);
    Route::put('promos/{id}/inactivate', [PromocionController::class, 'inactivate']);
});