<?php

namespace App\Http\Controllers\Api\Register;

use App\DTO\User\StoreUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Register\StoreUserRequest;
use App\Jobs\Auth\SendEmailVerificationJob;
use App\Services\Auth\AuthService;
use App\Services\EmailVerify\EmailVerificationService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private EmailVerificationService $emailVerificationService,
        private UserService $userService,
    ) { }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $newUser = $this->userService->createNewUser(StoreUserDTO::makeFromRequest($request));

        if(!$newUser){
            return response()->json([
                'status'=> 'failed',
                'message' => 'Não foi possível realizar o cadastro.'
            ], 500);
        }

        $linkVerification = $this->authService->createLinkVerification('verify');

        $this->verify($newUser->email, $linkVerification['hash']);

        SendEmailVerificationJob::dispatch($newUser->email, $linkVerification['link'])->onQueue('queue_notification');

        return response()->json([
            'status' => 'success',
            'message' => 'Cadastro realizado com sucesso! Por favor, acesse seu e-mail para ativar sua conta.'
        ], 201);


        /**
         * Esse trecho de código é para realizar login automatico após o registro do usuário
         */
        // Cria o token de acesso do usuário
        // $token = $user->createToken($request->device_name)->plainTextToken;
        // $user['token'] = $token;

        // return response()->json([
        //     'user' => $user->makeHidden(['created_at', 'updated_at'])
        // ]);
    }

    private function verify(string $email, string $hash): bool|JsonResponse
    {
        $emailVerify = $this->emailVerificationService->store($email, $hash);

        if(!$emailVerify) {
            return response()->json([
                'status' => 'failed',
                'message' => "Não foi possível enviar o link de verificação para {$email}. Por favor, tente novamente."
            ], 422);
        }

        return true;
    }
}
