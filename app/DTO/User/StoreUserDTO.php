<?php

namespace App\DTO\User;

use App\Http\Requests\Register\StoreUserRequest;

class StoreUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {

    }

    public static function makeFromRequest(StoreUserRequest $request): self
    {
        return new self(
            ucwords(strtolower($request->name)),
            strtolower($request->email),
            bcrypt($request->password)
        );
    }
}
