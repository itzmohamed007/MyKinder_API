<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\SiblingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/global', [AdministratorController::class, 'global']);

    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/siblings', [SiblingController::class, 'index']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/classrooms', [ClassroomController::class, 'index']);

    
    Route::middleware(['role:admin'])->group(function() {
        
        Route::get('/teachers/available', [TeacherController::class, 'availableTeachers']);
        Route::get('/teachers/{id}', [TeacherController::class, 'show']);
        Route::post('/teachers', [TeacherController::class, 'store']);
        Route::put('/teachers/{id}', [TeacherController::class, 'update']);
        Route::delete('/teachers/{id}', [TeacherController::class, 'delete']);

        Route::get('/classrooms/{id}', [ClassroomController::class, 'show']);
        Route::post('/classrooms', [ClassroomController::class, 'store']);
        Route::put('/classrooms/{id}', [ClassroomController::class, 'update']);
        Route::delete('/classrooms/{id}', [ClassroomController::class, 'delete']);

        Route::get('/siblings/{id}', [SiblingController::class, 'show']);
        Route::post('/siblings', [SiblingController::class, 'store']);
        Route::put('/siblings/{id}', [SiblingController::class, 'update']);
        Route::delete('/siblings/{id}', [SiblingController::class, 'delete']);

        Route::get('/students/data', [StudentController::class, 'creationData']);
        Route::get('/students/{id}', [StudentController::class, 'show']);
        Route::post('/students', [StudentController::class, 'store']);
        Route::put('/students/{id}', [StudentController::class, 'update']);
        Route::delete('/students/{id}', [StudentController::class, 'delete']);
    });

    Route::middleware(['role:teacher'])->group(function() {
        Route::get('/reservedStudents', [StudentController::class, 'teacherStudents']);
        Route::get('/status', [StudentController::class, 'index']);
        Route::put('/status/{id}', [StudentController::class, 'storeStatus']);
    });

    Route::middleware(['role:sibling'])->group(function() {
        Route::get('/siblings/{id}', [SiblingController::class, 'show']);
    });
});