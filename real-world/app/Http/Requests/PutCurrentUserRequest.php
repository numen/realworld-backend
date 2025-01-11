<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PutCurrentUserRequest extends FormRequest
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
            'email' => 'sometimes|email|unique:users',
            'username' => 'sometimes|string|unique:users|max:255',
            'password' => 'sometimes|min:6',
            'image' => 'sometimes|nullable|url',
            'bio' => 'sometimes|nullable|string',
        ];
    }
}
