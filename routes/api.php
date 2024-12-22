<?php

use App\Http\Controllers\Api\ {
    Auth\AuthController,
    Auth\PasswordResetController,
    EmailVerify\EmailVerificationController,
    Register\RegisterController
};
use Illuminate\Support\Facades\Route;


/** Auth Routes */
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetLink'])->middleware('guest');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->middleware('guest');

/** Register Routes */
Route::post('/register', [RegisterController::class, 'store']);

/**
 * Email Verification Routes
 */
Route::get('/verify/{hash}', [EmailVerificationController::class, 'verify']);
Route::post('/resend-email-verification', [EmailVerificationController::class, 'resendEmailVerification']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/validate-token', [AuthController::class, 'validateToken']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::get('/', function() {
    return response()->json(['message' => 'Rota raiz micro auth']);
});
