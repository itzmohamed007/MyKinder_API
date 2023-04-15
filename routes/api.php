<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\SiblingController;
use App\Http\Controllers\StudentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/teachers', [TeacherController::class, 'index']);
Route::get('/teachers/{teacher}', [TeacherController::class, 'show']);
Route::post('/teachers', [TeacherController::class, 'store']);
Route::put('/teachers/{teacher}', [TeacherController::class, 'update']);
Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy']);


Route::get('/classrooms', [ClassroomController::class, 'index']);
Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show']);
Route::post('/classrooms', [ClassroomController::class, 'store']);
Route::put('/classrooms/{classroom}', [ClassroomController::class, 'update']);
Route::delete('/classrooms/{classroom}', [ClassroomController::class, 'destroy']);


Route::get('/siblings', [SiblingController::class, 'index']);
Route::get('/siblings/{sibling}', [SiblingController::class, 'show']);
Route::post('/siblings', [SiblingController::class, 'store']);
Route::put('/siblings/{sibling}', [SiblingController::class, 'update']);
Route::delete('/siblings/{sibling}', [SiblingController::class, 'destroy']);


Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{student}', [StudentController::class, 'show']);
Route::post('/students', [StudentController::class, 'store']);
Route::put('/students/{student}', [StudentController::class, 'update']);
Route::delete('/students/{student}', [StudentController::class, 'destroy']);