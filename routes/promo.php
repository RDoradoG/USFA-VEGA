<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PromocionController;

Route::middleware(['auth'])->group(function () {
    Route::resource('promos', PromocionController::class);

    Route::put('promos/{id}/activate', [PromocionController::class, 'activate']);
    Route::put('promos/{id}/inactivate', [PromocionController::class, 'inactivate']);
});