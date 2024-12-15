<?php

namespace App\Services\User;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;

class UserService
{
    public function __construct(
        private User $user
    ){ }

    public function createNewUser(array $request): User|null
    {
        try {
            return $this->user->create($request)->makeHidden(['created_at', 'updated_at']);

        } catch (Exception $e) {

            return null;
        }
    }
}