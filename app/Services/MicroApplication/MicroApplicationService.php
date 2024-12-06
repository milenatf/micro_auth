<?php

namespace App\Services\MicroApplication;

use App\Models\User;
use Milenatf\MicroservicesCommon\Services\Traits\ConsumerExternalService;

class MicroApplicationService
{
    use ConsumerExternalService;

    protected $url, $token;

    public function __construct()
    {
        $this->url = config('services.micro_application.url');
        $this->token = config('services.micro_application.token');
    }

    // public function syncLoginWithMicroApplication(string $userUuid, bool|null $role, object $personalAccessTokens)
    public function syncLoginWithMicroApplication(string $userUuid, bool|null $role, string $accessToken)
    {
        // dd($accessToken);
        $this->request('post', "/syncLogin", [
            'user_uuid' => $userUuid,
            'is_teacher' => $role,
            'access_token' => $accessToken
        ]);


        // $this->request('post', "/syncLogin", [
        //     'user_uuid' => $userUuid,
        //     'is_teacher' => $role,
        //     'token' => $personalAccessTokens
        //     // $user->currentAccessToken()->token
        //     // 'abilities' => $personalAccessTokens->abilities ?? ['*'], // Permissões do token (default: todas)
        //     // 'tokenable_id' => $personalAccessTokens->tokenable_id,
        //     // 'name' => $personalAccessTokens->device_name, // Nome do dispositivo, se fornecido
        //     // 'last_used_at' => $personalAccessTokens->last_used_at,
        //     // 'expires_at' => $personalAccessTokens->expires_at ?? now()->addHours(1), // Define expiração, se não existir
        // ]);
        // return $response->body();
    }
}