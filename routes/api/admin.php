<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function() {
    Route::patch('{id}/as-supplier', [\App\Http\Controllers\admin\UserController::class, 'setUserAsSupplier']);
});
