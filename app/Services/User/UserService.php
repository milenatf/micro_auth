<?php

namespace App\Services\User;

use App\Models\User;
use Exception;
use App\DTO\User\StoreUserDTO;
use Illuminate\Http\JsonResponse;

class UserService
{
    public function __construct(
        private User $user
    ){ }

    public function getUserByEmail(string $email): null|object
    {
        $user = $this->user->where('email', $email)->first();

        if(!$user) return null;

        return $user->makeHidden(['created_at', 'updated_at']);
    }

    public function createNewUser(StoreUserDTO $request): User|null
    {
        try {
            return $this->user
            ->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ])
            ->makeHidden(['created_at', 'updated_at']);
        } catch (Exception $e) {

            return null;
        }
    }
}