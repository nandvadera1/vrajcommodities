<?php

use App\Http\Controllers\Api\v1\User\AuthController;
use App\Http\Controllers\Api\v1\User\CategoryController;
use App\Http\Controllers\Api\v1\User\ItemController;
use App\Http\Controllers\Api\v1\User\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'middleware' => ['XssSanitization']], function () {

    Route::group(['prefix' => 'user'], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/login', 'login');
            Route::post('/refresh-token', 'refreshToken');
        });

        Route::group(['middleware' => ['UserAuthentication']], function () {
            Route::group(['prefix' => 'item'], function () {
                Route::controller(ItemController::class)->group(function () {
                    Route::post('/list', 'list');
                });
            });

            Route::group(['prefix' => 'category'], function () {
                Route::controller(CategoryController::class)->group(function () {
                    Route::post('/list', 'list');
                });
            });

            Route::group(['prefix' => 'profile'], function () {
                Route::controller(ProfileController::class)->group(function () {
                    Route::post('/update', 'profileUpdate');
                });
            });
        });
    });
});
