<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;

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

        Route::group(['prefix' => 'vehicle'], function () {
            Route::get('list', [VehicleController::class, 'listVehicle']);
            Route::post('add', [VehicleController::class, 'addVehicle']);
            Route::put('edit/{id}', [VehicleController::class, 'editVehicle']);
            Route::post('sell/{id}', [VehicleController::class, 'sellVehicle']);
            Route::delete('delete/{id}', [VehicleController::class, 'deleteVehicle']);
        });

    });
});