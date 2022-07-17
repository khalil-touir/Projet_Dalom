<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('', function () {
        return 'aaa';
    });
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);


    Route::middleware('auth:api')->group(function () {
        Route::name('app.admin')
            ->prefix('admin')
            ->middleware('admin')
            ->group(__DIR__ . '/api/admin.php');

        Route::name('app.user')
            ->prefix('user')
            ->group(__DIR__ . '/api/user.php');

        Route::name('app.category')
            ->prefix('category')
            ->group(__DIR__ . '/api/category.php');

        Route::name('app.reclamation')
            ->prefix('reclamation')
            ->group(__DIR__ . '/api/reclamation.php');

        Route::name('app.setting')
            ->prefix('setting')
            ->group(__DIR__ . '/api/setting.php');

    });
});
