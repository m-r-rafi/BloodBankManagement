<?php


use App\Http\Controllers\ReceiveBloodController;
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


Route::prefix('receive-blood')->group(function ()
{
    Route::post('/create', [ReceiveBloodController::class, 'create']);
    Route::get('/', [ReceiveBloodController::class, 'allReceive']);
    Route::get('/{id}', [ReceiveBloodController::class, 'getReceiveById']);
    Route::post('/history', [ReceiveBloodController::class, 'getHistory']);
    Route::put('/update/{id}', [ReceiveBloodController::class, 'update']);
    Route::delete('/delete/{id}', [ReceiveBloodController::class, 'delete']);
    Route::get('/getallbystatus/{id}', [ReceiveBloodController::class, 'getAllByStatus']);
    Route::post('/requestblood', [ReceiveBloodController::class, 'requestBlood']);
    Route::get('/isallowed/{id}', [ReceiveBloodController::class, 'isAllowedRequest']);
    Route::post('/changestatus', [ReceiveBloodController::class, 'changeStatus']);
    Route::post('/view/changestatus', [ReceiveBloodController::class, 'viewChangeStatus']);
});
