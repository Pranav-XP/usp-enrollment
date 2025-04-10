<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\EnrolmentController;
use App\Http\Controllers\API\StudentController;

Route::post('/auth/login', [AuthController::class, 'loginUser']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/student/program', [StudentController::class, 'getProgram']);
    Route::post('/student/mark-all', [StudentController::class, 'markAllCourses']);
    Route::get('/student/completed-courses', [StudentController::class, 'completedCourses']);

    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/enrol', [EnrolmentController::class, 'enrol']);
});


Route::group(['middleware' => ['can:manage app']], function () {
    Route::post(
        '/auth/register',
        [AuthController::class, 'createUser']
    )
        ->middleware('auth:sanctum');
});
