<?php

use Illuminate\Support\Facades\Route;

Route::get('', [\App\Http\Controllers\CategoryController::class, 'getData'])
    ->name('app.category.get-data');

Route::get('{id}', [\App\Http\Controllers\CategoryController::class, 'getItem'])
    ->name('app.category.get-item');

