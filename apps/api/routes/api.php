<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BotController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('session', [AuthController::class, 'session']);
        Route::post('bots', [BotController::class, 'store']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
