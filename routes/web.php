<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {

    if (auth()->check()) {

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (auth()->user()->role === 'lecturer') {
            return redirect()->route('lecturer.dashboard');
        }

        if (auth()->user()->role === 'student') {
            return redirect()->route('student.dashboard');
        }
    }

    return view('welcome');
});
Route::post('/admin/courses', [AdminController::class,'storeCourse'])
    ->name('admin.courses.store');

/*
|------------------------------------------------------------------
| Admin Dashboard
|------------------------------------------------------------------
*/

Route::middleware(['auth','role:admin'])->group(function(){

    Route::get('/admin/dashboard', [AdminController::class,'dashboard'])
        ->name('admin.dashboard');

    Route::post('/admin/assign-lecturer', [AdminController::class,'assignCourseToLecturer'])
        ->name('admin.assign.lecturer');

    Route::post('/admin/revoke-lecturer', [AdminController::class,'revokeCourseFromLecturer'])
        ->name('admin.revoke.lecturer');

    Route::post('/admin/assign-student', [AdminController::class,'assignCourseToStudent'])
        ->name('admin.assign.student');

    Route::post('/admin/students', [AdminController::class, 'storeStudents'])
        ->name('admin.students.store');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('lecturers', App\Http\Controllers\AdminLecturerController::class);
});
// routes/web.php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('courses/{course}', [App\Http\Controllers\AdminCourseController::class, 'show'])
        ->name('courses.show');
});

/*
|------------------------------------------------------------------
| Lecturer Dashboard
|------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:lecturer'])
    ->get('/lecturer/dashboard', [LecturerController::class, 'dashboard'])
    ->name('lecturer.dashboard');
Route::post('/lecturer/grade', 
    [LecturerController::class, 'saveGrade']
)->name('lecturer.grade')->middleware(['auth','role:lecturer']);
/*
|------------------------------------------------------------------
| Student Dashboard
|------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])
    ->get('/student/dashboard', [StudentController::class, 'dashboard'])
    ->name('student.dashboard');

/*
|------------------------------------------------------------------
| Profile Routes
|------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::post('/admin/assign-student-course',
    [AdminController::class, 'assignCourseToStudent']
)->name('admin.assign.student.course');


Route::middleware('auth')->group(function () {

    Route::get('/change-password', function () {
        return view('auth.change-password');
    })->name('password.change');

    Route::post('/change-password', function (Request $request) {

    $request->validate([
        'password' => 'required|confirmed|min:6'
    ]);

    $user = auth()->user();

    $user->update([
        'password' => Hash::make($request->password),
        'must_change_password' => false
    ]);

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');

        case 'lecturer':
            return redirect()->route('lecturer.dashboard');

        case 'student':
            return redirect()->route('student.dashboard');

        default:
            return redirect('/');
    }

})->middleware('auth')->name('password.update');
});

require __DIR__.'/auth.php';