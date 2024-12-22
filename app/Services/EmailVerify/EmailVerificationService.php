<?php

namespace App\Services\EmailVerify;

use App\Jobs\Auth\SendEmailVerificationJob;
use App\Models\EmailVerify\EmailVerification;
use App\Services\Auth\AuthService;

class EmailVerificationService
{
    public function __construct(
        private EmailVerification $model,
        private AuthService $authService
    ){ }

    public function getEmailVerificationByToken(string $token): null|object
    {
        $emailVerification = $this->model->where('token', $token)->first();

        if (!$emailVerification) return null;

        return $emailVerification;
    }

    public function getEmailVerificationByEmail(string $email): null|object
    {
        $emailVerification = $this->model->where('email', $email)->first();

        if (!$emailVerification) return null;

        return $emailVerification;
    }

    public function store(string $email, string $hash): null|object
    {
        $data['email'] = $email;
        $data['token'] = $hash;
        $data['expired_at'] = now()->addMinutes(60);

        if(!$newEmailVerifications = $this->model->create($data)) return null;

        return $newEmailVerifications;
    }

    public function deleteEmailVerification(string $email): bool
    {
        if(!$this->model->where('email', $email)->first()->delete())
            return false;

        return true;
    }

    public function sendEmailVerification(string $email): bool
    {
        $linkVerification = $this->authService->createLinkVerification('verify');

        if($this->store($email, $linkVerification['hash'])) {
            SendEmailVerificationJob::dispatch($email, $linkVerification['link'])->onQueue('queue_notification');

            return true;
        }

        return false;
    }
}