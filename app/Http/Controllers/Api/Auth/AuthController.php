<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function login(AuthUser $request)
    {
        $user = $this->model::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Cria o token de acesso do usuário
        $token = $user->createToken($request->device_name)->plainTextToken;
        $user['token'] = $token;

        return response()->json([
            'data' => $user
        ]);
    }

    public function me()
    {
        $authUser = auth()->user();

        if(!$authUser) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Register not found'
            ], 404);
        }

        return response()->json($authUser);
    }

    public function logout()
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        if(!$authUser->tokens()->delete()) {
            return response()->json([
                'status'=> 'failed',
                'message' => 'Unable to log out!'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout completed successfully'
        ], 200);
    }
}
