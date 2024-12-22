<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Jobs\Auth\SendPasswordResetLinkJob;
use App\Services\Auth\AuthService;
use App\Services\Auth\PasswordResetService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class PasswordResetController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private PasswordResetService $passwordResetService,
        private UserService $userService
    ) { }

    public function sendPasswordResetLink(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $linkVerification = $this->authService->createLinkVerification('reset-password');

        $storePasswordResetTokens = $this->passwordResetService->storePasswordResetTokens($request->email, $linkVerification['hash']);

        if(!$storePasswordResetTokens) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Nenhum token foi atualizado ou inserido para o email: ' . $request->email
            ], 500);
        }

        SendPasswordResetLinkJob::dispatch($request->email, $linkVerification['link'])->onQueue('queue_notification');

        return response()->json([
            'status' => 'success',
            'message' => 'Link enviado com sucesso! Por favor, acesse seu e-mail para redefinir sua senha.'
        ], 201);
    }

    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {
        $user = $this->userService->getUserByEmail($request->email);

        if(!$user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Por favor, verifique seu e-mail para ativar sua conta.'
            ]);
        }

        $storedToken = $this->passwordResetService->getToken($request->email);

        if($storedToken !== $request->token) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token de redefinição de senha inválido ou expirado.'
            ]);
        }

        $updatePassword = $this->userService->updatePasswordUser($user, $request->password);

        if(!$updatePassword) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Não foi possível realizar a redefinição de senha.'
            ], 500);
        }

        $this->passwordResetService->delete($request->email);

        return response()->json([
            'status' => 'success',
            'message' => 'Redefinicção de senha realizada com sucesso!'
        ], 200);
    }
}
