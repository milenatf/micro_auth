<?php

namespace App\Services\User;

use App\Models\User;
use Exception;
use App\DTO\User\StoreUserDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(
        private User $model
    ){ }

    public function getUserByEmail(string $email): null|object
    {
        $user = $this->model->where('email', $email)->first();

        if(!$user) return null;

        return $user->makeHidden(['created_at', 'updated_at']);
    }

    public function createNewUser(StoreUserDTO $request): User|null
    {
        try {
            return $this->model->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ])->makeHidden(['created_at', 'updated_at']);

        } catch (Exception $e) {
            Log::error('Erro ao criar novo usuário' . $e->getMessage(), [
                'exception' => $e
            ]);

            return null;
        }
    }

    public function updatePasswordUser(User $user, string $password): bool
    {
        try {
            $user->forcefill([
                'password' => Hash::make($password),
            ])->save();

            return true;
        } catch (Exception $e) {
            Log::error('Erro ao atualizar a senha ' . $e->getMessage(), [
                'exception' => $e,
                'user' => $user->id,
            ]);

            return false;
        }
    }
}