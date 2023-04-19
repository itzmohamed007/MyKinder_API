<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\SiblingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AuthController;

// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Gloval Select
    Route::get('/global', [AdministratorController::class, 'global']);
    // Teachers Read
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/teachers/{id}', [TeacherController::class, 'show']);

    // Classrooms Read
    Route::get('/classrooms', [ClassroomController::class, 'index']);

    // Siblings Read
    Route::get('/siblings', [SiblingController::class, 'index']);

    // Students Read
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'show']);




    Route::middleware(['role:admin'])->group(function() {

        Route::post('/teachers', [AdministratorController::class, 'TeacherCreate']);
        Route::put('/teachers/{id}', [AdministratorController::class, 'TeacherUpdate']);
        Route::delete('/teachers/{id}', [AdministratorController::class, 'TeacherDelete']);


        Route::post('/classrooms', [AdministratorController::class, 'ClassroomCreate']);
        Route::put('/classrooms/{id}', [AdministratorController::class, 'ClassroomUpdate']);
        Route::delete('/classrooms/{id}', [AdministratorController::class, 'ClassroomDelete']);

        Route::post('/siblings', [AdministratorController::class, 'SiblingCreate']);
        Route::put('/siblings/{id}', [AdministratorController::class, 'SiblingUpdate']);
        Route::delete('/siblings/{id}', [AdministratorController::class, 'SiblingDelete']);

        Route::post('/students', [AdministratorController::class, 'StudentCreate']);
        Route::put('/students/{id}', [AdministratorController::class, 'StudentUpdate']);
        Route::delete('/students/{id}', [AdministratorController::class, 'StudentDelete']);
    });

    Route::middleware(['role:teacher'])->group(function() {
        // Select students from given classroom
        Route::get('/classrooms/{id}', [ClassroomController::class, 'show']);
    });

    Route::middleware(['role:sibling'])->group(function() {
        // Select own student
        Route::get('/siblings/{id}', [SiblingController::class, 'show']);
    });
});