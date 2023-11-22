<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BloodBankController;
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

Route::prefix('blood_bank')->group(function ()
{
    Route::get('/', [BloodBankController::class, 'allBlood']);
    Route::get('/getbyid/{id}', [BloodBankController::class, 'getBloodById']);
    Route::post('/create', [BloodBankController::class, 'create']);
    Route::put('/update/{id}', [BloodBankController::class, 'update']);
    Route::put('/updatebyadmin',[BloodBankController::class,'updateByAdmin']);
    Route::delete('/delete/{id}', [BloodBankController::class, 'delete']);
    Route::post('/available/blood',[BloodBankController::class,'availableBlood']);

});

