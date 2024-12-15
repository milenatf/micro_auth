<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreUser extends FormRequest
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
            'password' => ['required', 'min:4', 'max:16'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            // 'device_name' => ['required', 'string', 'max:255']
        ];
    }

    /**
     * Sobrescreve o método failedValidation na classe StoreUser para capturar os erros e manipulá-los.
     * Dessa forma os erros de validação são enviados para o microserviço application para que sejam exibidos lá
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }
}
