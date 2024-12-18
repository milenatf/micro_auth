<?php

namespace App\Services\EmailVerify;

use App\Models\EmailVerify\EmailVerification;
use Illuminate\Http\JsonResponse;

class EmailVerificationService
{
    public function __construct(
        private EmailVerification $model,
    ){ }

    public function getEmailVerificationByToken(string $token): null|object
    {
        $emailVerification = $this->model->where('token', $token)->first();

        if (!$emailVerification) return null;

        return $emailVerification;
    }

    public function store(string $email, string $hash): null|object
    {
        $data['email'] = $email;
        $data['token'] = $hash;
        $data['expired_at'] = now()->addMinutes(60);

        if(!$create = $this->model->create($data)) return null;

        return $create;
    }

    public function deleteEmailVerification(string $email): bool
    {
        if(!$this->model->where('email', $email)->first()->delete())
            return false;

        return true;
    }
}