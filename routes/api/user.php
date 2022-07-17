<?php

use Illuminate\Support\Facades\Route;

Route::prefix('address')->group(function() {
    Route::get('', [\App\Http\Controllers\user\AddressController::class, 'getData'])
        ->name('app.user.address.get-data');

    Route::post('', [\App\Http\Controllers\user\AddressController::class, 'create'])
        ->name('app.user.address.create');

    Route::match(['put', 'patch'],'{id}', [\App\Http\Controllers\user\AddressController::class, 'update'])
        ->name('app.user.address.update');

    Route::delete('{id}', [\App\Http\Controllers\user\AddressController::class, 'delete']);
});

Route::post('certification', [\App\Http\Controllers\user\UserController::class, 'demandSupplier']);

Route::prefix('deed')->group(function() {
    Route::patch('{id}/confirm', [\App\Http\Controllers\user\UserController::class, 'confirmDeed']);
    Route::get('remaining', [\App\Http\Controllers\user\UserController::class, 'getRemainingDeeds']);
});

Route::prefix('payment-card')->group(function() {
    Route::get('', [\App\Http\Controllers\user\UserController::class, 'getPaymentCards']);
    Route::post('', [\App\Http\Controllers\user\UserController::class, 'storePaymentCard']);
});

Route::post('pay', [\App\Http\Controllers\user\UserController::class, 'payDeed']);

Route::get('notification', [\App\Http\Controllers\user\UserController::class, 'getNotifications']);


Route::prefix('supplier')->group(function() {
    Route::get('', [\App\Http\Controllers\user\SupplierController::class, 'getData']);
    Route::post('{id}/reserve', [\App\Http\Controllers\user\UserController::class, 'reserve']);
    Route::get('{id}/rating', [\App\Http\Controllers\RatingController::class, 'getSupplierRating']);
    Route::post('{id}/rating', [\App\Http\Controllers\RatingController::class, 'rateSupplier']);
    Route::middleware('supplier')->group(function() {
        Route::patch('available', [\App\Http\Controllers\user\SupplierController::class, 'setAvailable']);
        Route::patch('unavailable', [\App\Http\Controllers\user\SupplierController::class, 'setUnavailable']);
        Route::post('deed', [\App\Http\Controllers\user\SupplierController::class, 'createDeed']);
        Route::prefix('bank-account')->group(function() {
            Route::get('', [\App\Http\Controllers\user\SupplierController::class, 'getBankAccount']);
            Route::post('', [\App\Http\Controllers\user\SupplierController::class, 'storeBankAccount']);
        });
    });
});
