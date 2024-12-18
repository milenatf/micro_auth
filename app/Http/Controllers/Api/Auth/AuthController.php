<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthUser;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    public function __construct(
        private UserService $userService
    ) { }

    public function login(AuthUser $request)
    {
        $user = $this->userService->getUserByEmail($request->email);

        if(!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        if(!$user->email_verified_at)
        {
            return response()->json([
                'status' => 'failed',
                'message' => 'Por favor, verifique seu e-mail para ativar sua conta.'
            ], 403);
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'Email e/ou senha estão incorretos.'
            ], 422);
        }

        // Logout em todos os dispositivos caso venha na request o parâmetro logout_others_devices
        // if($request->has('logout_others_devices')) {
        //     $user->tokens()->delete();
        // }

        /**
         * Aqui é implementado o login único, onde todos os tokens do usuário serão deletados
         * antes de realizar login na aplicação
         */
        $user->tokens()->delete(); // Exclui todos os tokens do usuário

        // Cria o token de acesso, adicionando 24 hora de expiração
        $user['token'] = $user->createToken($request->header('User-Agent'), ['*'], now()->addMinutes(1440))->plainTextToken;

        return response()->json($user->only('token'), 200);
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

        return new UserResource($authUser);
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
            return response()->json(['error' => 'Erro ao validar o token.'], 500);
        }
    }
}
