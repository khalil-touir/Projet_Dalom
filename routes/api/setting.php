<?php

use Illuminate\Support\Facades\Route;

Route::get('', [\App\Http\Controllers\SettingController::class, 'getData'])
    ->name('app.setting.get-data');

Route::post('', [\App\Http\Controllers\SettingController::class, 'create'])
    ->name('app.setting.create');

