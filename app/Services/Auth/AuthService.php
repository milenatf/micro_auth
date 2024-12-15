<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    private $url_frontend;

    public function __construct()
    {
        $this->url_frontend = config('microApplication.url_frontend');
    }
    public function createHashForEmailVerification(): string
    {
        return str_replace('/', '.', Hash::make(Str::random(256)));
    }

    public function createLinkVerification(): string
    {
        $hash = $this->createHashForEmailVerification();

        return "{$this->url_frontend}/verify/{$hash}";
    }
}