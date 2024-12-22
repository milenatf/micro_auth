<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;

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

    public function createLinkVerification(string $action): null|array
    {
        if($action == null) {
            return null;
        }

        $routes = $this->routes();

        if(!array_key_exists($action, $routes)) {
            throw new InvalidArgumentException("Rota não encontrada: {$action}");
        }

        $hash = $this->createHashForEmailVerification();

        return [
            'link' => "{$this->url_frontend}/{$action}/{$hash}",
            'hash' => $hash
        ];
    }

    /**
     * Returns an array of available routes for email verification and password reset.
     *
     * @return array List of route actions.
     */

    private function routes(): array
    {
        return [
            'verify' => 'verify',
            'reset-password' => 'reset-password'
        ];
    }
}