<?php


use App\Http\Controllers\DonateBloodController;
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

Route::prefix('donate-blood')->group(function () {
    Route::post('/create', [DonateBloodController::class, 'create']);
    Route::get('/', [DonateBloodController::class, 'allDonate']);
    Route::get('/{id}', [DonateBloodController::class, 'getDonateById']);
    Route::post('/history', [DonateBloodController::class, 'getHistory']);
    Route::put('/update/{id}', [DonateBloodController::class, 'update']);
    Route::delete('/delete/{id}', [DonateBloodController::class, 'delete']);
    Route::get('/getallbystatus/{id}', [DonateBloodController::class, 'getAllByStatus']);
    Route::get('/donate/{id}', [DonateBloodController::class, 'donate']);
    Route::get('/isallowed/{id}', [DonateBloodController::class, 'isAllowedRequest']);
    Route::post('/changestatus', [DonateBloodController::class, 'changeStatus']);
    Route::post('/view/changestatus', [DonateBloodController::class, 'viewChangeStatus']);
});
