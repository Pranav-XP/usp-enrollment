<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Livewire\Auth\Register;
use App\Http\Controllers\EnrolmentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentHoldController;
use App\Http\Controllers\StudentHoldViewController;
use App\Http\Controllers\TransactionController;
use App\Mail\MyTestEmail;
use Illuminate\Support\Facades\Mail;

/* Route::get('/', function () {
    return view('welcome');
})->name('home'); */

Route::get('/email', function () {
    Mail::to('pranavchand777@gmail.com')->send(new MyTestEmail);
});

Route::get('/check-enrollment', [EnrolmentController::class, 'testEnrollment']);

Route::middleware(['auth', 'verified', 'role:student|admin', 'check-hold'])->group(function () {
    Route::get('/dashboard', [EnrolmentController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', 'verified', 'role:student', 'check-hold'])->group(function () {
    Route::post('/enrol/{courseId}', [EnrolmentController::class, 'enrolStudent'])->name('enrol.course');
    Route::get('grades', [GradeController::class, 'index'])->name('grades');
    Route::get('courses', [CourseController::class, 'index'])->name('courses');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/hold', [StudentHoldViewController::class, 'index'])->name('student.holds');

    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::group(['middleware' => ['can:manage app']], function () {
    Route::get('admin/register-student', Register::class)->name('admin.register-student');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/admin/students', [AdminController::class, 'showStudentsList'])->name('admin.students');
    Route::get('/admin/grades/{studentId}', [AdminController::class, 'showGradeForm'])->name('admin.students.gradeForm');
    Route::put('/admin/grades/{studentId}', [AdminController::class, 'updateGrades'])->name('admin.students.updateGrade');

    Route::get('/admin/holds/{student}', [StudentHoldController::class, 'index'])->name('admin.holds.index');       // List holds for this student
    Route::get('/admin/holds/{student}/create', [StudentHoldController::class, 'create'])->name('admin.holds.create'); // Form to place a new hold
    Route::post('/admin/holds/{student}', [StudentHoldController::class, 'store'])->name('admin.holds.store');      // Store a new hold
    Route::put('holds/{hold}/release', [StudentHoldController::class, 'release'])->name('admin.holds.release'); // Release an active hold
});

require __DIR__ . '/auth.php';
