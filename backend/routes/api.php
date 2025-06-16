<?php

use Illuminate\Support\Facades\Route;

Route::post('/login',            AuthController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('appointments', AppointmentController::class)->only(['index','store','update']);
    Route::post('/logout',       LogoutController::class);
});
