<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\SiblingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::post('/admin/register', [AdministratorController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);




Route::get('/teachers', [TeacherController::class, 'index']);
Route::get('/teachers/{id}', [TeacherController::class, 'show']);

Route::get('/classrooms', [ClassroomController::class, 'index']);
Route::get('/classrooms/{id}', [ClassroomController::class, 'show']);

Route::get('/siblings', [SiblingController::class, 'index']);
Route::get('/siblings/{id}', [SiblingController::class, 'show']);

Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{id}', [StudentController::class, 'show']);




Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/teachers', [TeacherController::class, 'store']);
    Route::put('/teachers/{id}', [TeacherController::class, 'update']);
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']);

    Route::post('/classrooms', [ClassroomController::class, 'store']);
    Route::put('/classrooms/{id}', [ClassroomController::class, 'update']);
    Route::delete('/classrooms/{id}', [ClassroomController::class, 'destroy']);

    Route::post('/siblings', [SiblingController::class, 'store']);
    Route::put('/siblings/{id}', [SiblingController::class, 'update']);
    Route::delete('/siblings/{id}', [SiblingController::class, 'destroy']);

    Route::post('/students', [StudentController::class, 'store']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
});