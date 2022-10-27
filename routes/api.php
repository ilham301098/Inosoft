<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\AuthController;

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


Route::post('login', [AuthController::class, 'login']);
// user baru


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logoutall', [AuthController::class, 'logoutall']);
    });

    Route::group(['prefix' => 'v1'], function () {

        Route::group(['prefix' => 'auth'], function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('logoutall', [AuthController::class, 'logoutall']);
        });

    });
});


//without auth sanctum
Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('login-sms', [AuthController::class, 'loginSMS']);
    });

});

