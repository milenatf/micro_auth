<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUser;
use App\Jobs\Auth\SendEmailVerificationJob;
use App\Jobs\UserRegisteredJob;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private UserService $userService
    ) { }

    public function store(StoreUser $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $newUser = $this->userService->createNewUser($data);

        if(!$newUser){
            return response()->json([
                'status'=> 'failed',
                'message' => 'Não foi possível realizar o cadastro.'
            ], 500);
        }

        $linkVerification = $this->authService->createLinkVerification();
        SendEmailVerificationJob::dispatch($newUser->email, $linkVerification)->onQueue('queue_notification');

        // UserRegisteredJob::dispatch($newUser->email)->onQueue('queue_notification');


        return $newUser;

        // Cria o token de acesso do usuário
        // $token = $user->createToken($request->device_name)->plainTextToken;
        // $user['token'] = $token;

        // return response()->json([
        //     'user' => $user->makeHidden(['created_at', 'updated_at'])
        // ]);
    }
}
