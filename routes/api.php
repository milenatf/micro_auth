<?php

use App\Http\Controllers\Api\ {
    Auth\AuthController,
    EmailVerify\EmailVerificationController,
    Register\RegisterController
};
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'store']);

/**
 * Email Verification Routes
 */
Route::get('/verify/{hash}', [EmailVerificationController::class, 'verify']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/validate-token', [AuthController::class, 'validateToken']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::get('/', function() {
    return response()->json(['message' => 'Rota raiz micro auth']);
});
