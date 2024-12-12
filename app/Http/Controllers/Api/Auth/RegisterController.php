<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUser;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function store(StoreUser $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        try {
            // $user = $this->model->create($data);
            return $this->model->create($data)->makeHidden(['created_at', 'updated_at']);

        } catch(Exception $e) {
            return response()->json([
                'status'=> 'failed',
                'message' => 'Não foi possível realizar o cadastro.'
            ], 500);
        }

        // Cria o token de acesso do usuário
        // $token = $user->createToken($request->device_name)->plainTextToken;
        // $user['token'] = $token;

        // return response()->json([
        //     'user' => $user->makeHidden(['created_at', 'updated_at'])
        // ]);
    }
}
