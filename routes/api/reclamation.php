<?php

use Illuminate\Support\Facades\Route;

Route::get('', [\App\Http\Controllers\ReclamationController::class, 'getData'])
    ->name('app.reclamation.get-data');

Route::post('', [\App\Http\Controllers\ReclamationController::class, 'create'])
    ->name('app.reclamation.create');

