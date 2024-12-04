<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUser;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function store(StoreUser $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);
        $user = $this->model->create($data);

        // Cria o token de acesso do usuário
        $token = $user->createToken($request->device_name)->plainTextToken;
        $user['token'] = $token;

        return response()->json([
            'data' => $user->makeHidden(['created_at', 'updated_at'])
        ]);
    }
}
