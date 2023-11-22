<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;

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
Route::post('user/register', [UserController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'logIn']);
Route::group(['middleware' => 'logged'], function () {
    Route::get('/logout', [AuthController::class, 'logOut']);

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'allUsers']);
        Route::get('/getbyid/{id}', [UserController::class, 'getUserById']);
        Route::put('/update/{id}', [UserController::class, 'updateUser']);
        Route::delete('/delete/{id}', [UserController::class, 'deleteUser']);
        Route::get('/iseligible/{id}', [UserController::class, 'isEligible']);
        Route::post('/iseligibleupdate', [UserController::class, 'isEligibleUpdate']);
    });
});
