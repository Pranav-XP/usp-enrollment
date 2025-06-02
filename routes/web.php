<?php

use App\Http\Controllers\SemesterController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Livewire\Auth\Register;
use App\Http\Controllers\EnrolmentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminGraduationController;
use App\Http\Controllers\AdminPassController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradeRecheckAdminController;
use App\Http\Controllers\GradeRecheckStudentController;
use App\Http\Controllers\GraduationApplicationController;
use App\Http\Controllers\SpecialPassApplicationController;
use App\Http\Controllers\StudentHoldController;
use App\Http\Controllers\StudentHoldViewController;
use App\Http\Controllers\TransactionController;



Route::middleware(['auth', 'verified', 'role:student|admin', 'check-hold'])->group(function () {
    Route::get('/dashboard', [EnrolmentController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', 'verified', 'role:student', 'check-hold'])->group(function () {
    Route::post('/enrol/{courseId}', [EnrolmentController::class, 'enrolStudent'])->name('enrol.course');
    Route::get('/grades', [GradeController::class, 'index'])->name('grades');

    Route::get('/grades/{courseId}/recheck', [GradeRecheckStudentController::class, 'create'])->name('recheck.create');

    Route::post('/grades/recheck', [GradeRecheckStudentController::class, 'store'])
        ->name('recheck.store');

    Route::get('/grades/download', [GradeController::class, 'download'])->name('student.grades.download');

    Route::get('courses', [CourseController::class, 'index'])->name('courses');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');

    Route::get('/graduation', [GraduationApplicationController::class, 'create'])->name('graduation.create');
    Route::post('/graduation', [GraduationApplicationController::class, 'store'])->name('graduation.store');

    Route::get('/pass', [SpecialPassApplicationController::class, 'create'])->name('pass.create');
    Route::post('/pass', [SpecialPassApplicationController::class, 'store'])->name('pass.store');
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

    Route::get('/admin/recheck', [GradeRecheckAdminController::class, 'index'])->name('admin.recheck.index');
    Route::get('/admin/recheck/{id}', [GradeRecheckAdminController::class, 'show'])->name('admin.recheck.show');
    Route::put('/admin/recheck/{id}/update', [GradeRecheckAdminController::class, 'updateStatus'])->name('admin.recheck.update');
    Route::get('/admin/recheck/{applicationId}/download', [GradeRecheckAdminController::class, 'downloadPaymentConfirmation'])->name('admin.recheck.download');

    Route::get('/admin/graduation', [AdminGraduationController::class, 'index'])->name('admin.graduation.index');
    Route::get('/admin/graduation/{id}', [AdminGraduationController::class, 'show'])->name('admin.graduation.show');

    Route::get('/admin/pass', [AdminPassController::class, 'index'])->name('admin.pass.index');
    Route::get('/admin/pass/{id}', [AdminPassController::class, 'show'])->name('admin.pass.show');

    Route::get('admin/semesters', [SemesterController::class, 'index'])->name('admin.semesters.index');
    Route::get('admin/semesters/create', [SemesterController::class, 'create'])->name('admin.semesters.create');
    Route::post('admin/semesters', [SemesterController::class, 'store'])->name('admin.semesters.store');
    // Note: The resource route also includes a 'show' method (GET /semesters/{semester})
    // but your SemesterController currently doesn't implement it.
    // If you need a detailed view for a single semester, you would add a show() method to SemesterController.
    // Route::get('semesters/{semester}', [SemesterController::class, 'show'])->name('semesters.show');
    Route::get('semesters/{semester}/edit', [SemesterController::class, 'edit'])->name('admin.semesters.edit');
    Route::post('semesters/{semester}/set-active', [SemesterController::class, 'setActive'])->name('admin.semesters.set-active');
});

require __DIR__ . '/auth.php';
