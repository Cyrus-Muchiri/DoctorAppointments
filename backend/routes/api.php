<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AppointmentController;

Route::post('/register', [AuthController::class,'register'])->name('register');
Route::post('/login',  [AuthController::class,'login'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('appointments', AppointmentController::class)->only(['index','store','update']);
    Route::post('/logout',       [AuthController::class,'logout']);
});
