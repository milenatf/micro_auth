<?php

use App\Http\Controllers\Api\{
    UserController
};
use App\Http\Controllers\Api\Auth\{
    RegisterController,
    AuthController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Auth end register routes
 */
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/auth', [AuthController::class, 'auth']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/users', UserController::class);
});

Route::get('/', function () {
    return response()->json(['status' => 'succes', 'message' => 'Micro Auth running']);
});

