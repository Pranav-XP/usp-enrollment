<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Livewire\Admin\Courses;
use App\Livewire\Admin\CreateProgram;
use App\Livewire\Auth\Register;

/* Route::get('/', function () {
    return view('welcome');
})->name('home'); */

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::get('courses', [CourseController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('courses');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::group(['middleware' => ['can:manage app']], function () {
    Route::get('admin/register-student', Register::class)->name('admin.register-student');
    Route::get('admin/program', CreateProgram::class)->name('admin.programmes');
    Route::get('admin/course', Courses::class)->name('admin.course');
});

require __DIR__.'/auth.php';
