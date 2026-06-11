<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LeadController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('leads', [LeadController::class, 'list']);
});