<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('leads', [LeadController::class, 'list']);
    Route::get('users', [UserController::class, 'list']);
});