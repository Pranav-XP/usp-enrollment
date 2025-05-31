<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Livewire\Admin\Courses;
use App\Livewire\Admin\CreateProgram;
use App\Livewire\Auth\Register;
use App\Http\Controllers\EnrolmentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransactionController;

/* Route::get('/', function () {
    return view('welcome');
})->name('home'); */

Route::get('/check-enrollment', [EnrolmentController::class, 'testEnrollment']);

Route::get('/dashboard', [EnrolmentController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route for enrolling in a course
Route::post('/enrol/{courseId}', [EnrolmentController::class, 'enrolStudent'])
    ->middleware(['auth', 'verified'])
    ->name('enrol.course');

Route::get('courses', [CourseController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('courses');

Route::get('grades', [CourseController::class, 'showGrades'])
    ->middleware(['auth', 'verified'])
    ->name('grades');

Route::middleware(['auth'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::group(['middleware' => ['can:manage app']], function () {
    Route::get('admin/register-student', Register::class)->name('admin.register-student');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/admin/grades', [AdminController::class, 'showStudentsList'])->name('admin.grades');
    Route::get('/admin/students/{studentId}', [AdminController::class, 'showGradeForm'])->name('admin.students.gradeForm');
    Route::put('/admin/students/{studentId}/grade', [AdminController::class, 'updateGrades'])->name('admin.students.updateGrade');
});

require __DIR__ . '/auth.php';
