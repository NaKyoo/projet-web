<?php

use App\Http\Controllers\CohortController;
use App\Http\Controllers\CommonLifeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RetroController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Redirect the root path to /dashboard
Route::redirect('/', 'dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('verified')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Cohorts
        Route::get('/cohorts', [CohortController::class, 'index'])->name('cohort.index');
        Route::get('/cohorts/{cohort}', [CohortController::class, 'show'])->name('cohort.show');
        Route::post('/cohorts', [CohortController::class, 'store'])->name('cohort.store');
        Route::delete('/cohorts/{id}', [CohortController::class, 'destroy'])->name('cohort.destroy');
        Route::put('/cohorts/{cohort}', [CohortController::class, 'update'])->name('cohort.update');

        // Cohort
        Route::post('/cohorts/{cohort}/students', [CohortController::class, 'addStudent'])->name('cohorts.addStudent');
        Route::delete('/cohorts/{cohort}/students/{userId}', [CohortController::class, 'deleteStudent'])->name('cohorts.deleteStudent');
        Route::post('cohorts/{cohort}/addTeacher', [CohortController::class, 'addTeacher'])->name('cohorts.addTeacher');
        Route::delete('cohorts/{cohort}/deleteTeacher/{userId}', [CohortController::class, 'deleteTeacher'])->name('cohorts.deleteTeacher');
        Route::get('/cohort/{cohort}/form', [CohortController::class, 'getForm'])->name('cohort.form');


        // Teachers
        Route::post('/teachers', [TeacherController::class, 'store'])->name('teacher.store');
        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teacher.destroy');
        Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teacher.update');
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teacher.index');
        Route::get('teacher/form/{teacher}', [TeacherController::class, 'getForm'])->name('teacher.form');

        // Students
        Route::post('/students', [StudentController::class, 'store'])->name('student.store');
        Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
        Route::put('/students/{id}', [StudentController::class, 'update'])->name('student.update');
        Route::get('/students', [StudentController::class, 'index'])->name('student.index');
        Route::get('student/form/{student}', [StudentController::class, 'getForm'])->name('student.form');

        // Knowledge
        Route::get('knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');

        // Groups
        Route::get('groups', [GroupController::class, 'index'])->name('group.index');

        // Retro
        route::get('retros', [RetroController::class, 'index'])->name('retro.index');

        // Common life
        Route::get('common-life', [CommonLifeController::class, 'index'])->name('common-life.index');
    });

});

require __DIR__.'/auth.php';
