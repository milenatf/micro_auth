<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\MicroApplication\MicroApplicationService;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $model;
    private $microApplicationService;

    public function __construct(
        User $user,
        MicroApplicationService $microApplicationService
    ) {
        $this->model = $user;
        $this->microApplicationService = $microApplicationService;
    }

    public function login(AuthUser $request)
    {
        $user = $this->model::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Email e/ou senha estão incorretos.'
            ], 422);
        }

        $user->tokens()->delete(); // Exclui todos os tokens do usuário

        // Cria o token de acesso, adicionando 24 hora de expiração
        $user['token'] = $user->createToken($request->header('User-Agent'), ['*'], now()->addMinutes(1440))->plainTextToken;

        return response()->json([
            'data' => $user,
        ]);
    }

    public function me()
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        if(!$authUser) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Register not found'
            ], 404);
        }

        return response()->json($authUser->makeHidden(['created_at', 'updated_at']));
    }

    public function logout()
    {
        /** @var User $authUser */
        $authUser = auth()->user();

        if(!$authUser->tokens()->delete()) {
            return response()->json([
                'status'=> 'failed',
                'message' => 'Não foi possível realizar o logout.'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout realizado com sucesso.'
        ], 200);
    }

    public function validateToken()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Invalid token'], 401);
            }
            return response()->json(['user' => $user], 200);
        } catch (Exception $e) {
            Log::error('Erro ao validar o token:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
