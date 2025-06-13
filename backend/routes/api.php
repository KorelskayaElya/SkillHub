<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;

Route::middleware(['web'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth:sanctum'])->get('/user', [AuthController::class, 'user']);
Route::middleware(['auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);


Route::get('/courses', [CourseController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/mycourses', [CourseController::class, 'myCourses']); 
});

Route::middleware('auth:sanctum')->post('/user/courses', [UserController::class, 'enroll']);

