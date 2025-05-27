<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Freelancers\ClientController;
use App\Http\Controllers\Freelancers\ProjectController;
use App\Http\Controllers\Freelancers\TimeLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
Route::middleware(['auth:sanctum', 'freelancer'])->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('time-logs', TimeLogController::class);
});

