<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\StudentController;

Route::post('/auth/login', [AuthController::class, 'loginUser']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/student/program', [StudentController::class, 'getProgram']);
    Route::get('/course/{id}', [CourseController::class, 'show']);
});


Route::group(['middleware' => ['can:manage app']], function () {
    Route::post(
        '/auth/register',
        [AuthController::class, 'createUser']
    )
        ->middleware('auth:sanctum');
});
