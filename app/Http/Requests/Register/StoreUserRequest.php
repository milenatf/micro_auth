<?php

namespace App\Http\Requests\Register;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
            ],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
        ];
    }

    /**
     * Sobrescreve o método failedValidation na classe StoreUser para capturar os erros e manipulá-los.
     * Dessa forma os erros de validação são enviados para o microserviço application para que sejam exibidos lá
     *
     * @param Validator $validator
     * @return void
     */
    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(
    //         response()->json(['errors' => $validator->errors()], 422)
    //     );
    // }
}