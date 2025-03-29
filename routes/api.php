<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/auth/login', [AuthController::class, 'loginUser']);


Route::group(['middleware' => ['can:manage app']], function () {
    Route::post('/auth/register', 
    [AuthController::class, 'createUser'])
    ->middleware('auth:sanctum');
    
});