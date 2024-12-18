<?php

namespace App\Services\User;

use App\Models\User;
use Exception;
use App\DTO\User\StoreUserDTO;

class UserService
{
    public function __construct(
        private User $user
    ){ }

    public function getUserbyEmail(string $email)
    {
        $user = $this->user->where('email', $email)->first();

        if ($user->email_verified_at) {
            return response()->json([
                'status' => 'failed',
                'message' => "This email {$user->email} has already been verified."
            ]);
        }
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