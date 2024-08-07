<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PushNotificationsController;
use App\Http\Controllers\AddFundsController;

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

Route::post('device/register', [PushNotificationsController::class, 'registerDevice']);
Route::get('device/delete', [PushNotificationsController::class, 'deleteDevice']);

/* Route::post('confirmation', [MPESAResponsesController::class, 'confirmation']);
Route::post('stkpush', [MPESAResponsesController::class, 'stkPush']); */
Route::match(['GET', 'POST'], '/stk_callback', [AddFundsController::class, 'STKCallback']);