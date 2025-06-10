<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route; // не забудь эту строку!

Route::middleware(['web'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth:sanctum'])->get('/user', [AuthController::class, 'user']);
Route::middleware(['auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);
