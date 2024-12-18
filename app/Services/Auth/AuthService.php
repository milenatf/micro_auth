<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    private $url_frontend;

    public function __construct()
    {
        $this->url_frontend = config('app.url_frontend');
    }

    private function createHashForEmailVerification(): string
    {
        return str_replace('/', '.', Hash::make(Str::random(256)));
    }

    public function createLinkVerification(): array
    {
        $hash = $this->createHashForEmailVerification();

        return [
            'link' => "{$this->url_frontend}/verify/{$hash}",
            'hash' => $hash
        ];
    }
}