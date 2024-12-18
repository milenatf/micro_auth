<?php

namespace App\Http\Controllers\Api\EmailVerify;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailVerify\EmailVerificationService;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __construct(
        private EmailVerificationService $service,
        private User $userModel
    ) { }
    public function verify(Request $request)
    {
        $token = $request->segment(2);

        $emailVerification = $this->service->getEmailVerificationByToken($token);

        if(!$emailVerification) {
            return response()->json([
                'status' => 'failed',
                'message' => 'O link é invalido.'
            ], 400);
        }

        $user = $this->userModel->where('email', $emailVerification->email)->first();

        if($user->email_verified_at) {
            return response()->json([
                'status' => 'failed',
                'message' => "Este email já foi verificado."
            ], 409);
        }

        if(now()->greaterThan($emailVerification->expired_at)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'O link expirou.'
            ], 410);
        }

        $user->email_verified_at = now();

        if( !$user->save() ) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Não foi possível realizar a verificação de e-mail.',
                'userEmail' => $user->email
            ], 500);
        }

        if(!$this->service->deleteEmailVerification($user->email)) {
            return response()->json([
                'status' => 'failed',
                'message' => "Não foi possível excluir o registro da tabela email_verification.",
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => "O email {$user->email} foi verificado."
        ], 200);
    }
}