<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
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

// Route api v1
Route::prefix('/v1')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->name('user.register');
    Route::post('/login', [UserController::class, 'login'])->name('user.login');

    // Route for user authentication
    Route::middleware('auth:sanctum')->group(function () {
        // fungsi untuk user logut
        Route::get('/logout', [UserController::class, 'logout']);

        // Route for users
        Route::get('/user', [UserController::class, 'profile']);
        Route::post('/user', [UserController::class, 'updateProfile']);
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
