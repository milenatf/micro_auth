<?php

namespace App\Services\Auth;

use App\Models\Auth\PasswordResetToken;
use Exception;
use Illuminate\Support\Facades\Log;

class PasswordResetService
{
    private $url_frontend;

    public function __construct(
        private PasswordResetToken $model
    )
    {
        $this->url_frontend = config('app.url_frontend');
    }

    public function getToken(string $email): null|string
    {
        $token = $this->model->where('email', $email)->first()->token;

        if(!$token) {
            return null;
        }

        return $token;
    }

    public function storePasswordResetTokens(string $email, string $token): bool
    {
        try {

            $affected = $this->model->updateOrCreate(
                ['email' => $email], // Verifica se o e-mail já existe (Condição de busca)
                ['token' => $token, 'created_at' => now()]
            );

            if (!$affected) {
                Log::warning('Nenhum token foi atualizado ou inserido para o email: ' . $email);

                return false;
            }

            return true;

        } catch (Exception $e) {
            Log::error('Erro ao salvar token de redefinição de senha para o email ' . $email . ': ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $email,
            ]);

            return false;
        }
    }

    public function delete(string $email): bool
    {
        try {

            $this->model->where('email', $email)->delete();

            return true;

        } catch(Exception $e) {
            Log::error('Erro ao excluir registro da tabela password_reset_tokens: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $email,
            ]);

            return false;
        }
    }
}