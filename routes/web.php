<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\ProfileController;

Route::middleware([])->group(function () {
    Route::get('/', [ProfileController::class, 'redirectHome']);
});

require __DIR__.'/settings.php';
require __DIR__.'/lead.php';
require __DIR__.'/user.php';
require __DIR__.'/promo.php';