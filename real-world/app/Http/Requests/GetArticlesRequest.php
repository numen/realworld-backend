<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetArticlesRequest extends FormRequest
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
            'tag' => 'sometimes|string',
            'author' => 'sometimes|string',
            'favorited' => 'sometimes|string',
            'limit' => 'sometimes|integer|min:1',
            'offset' => 'sometimes|integer|min:0',
        ];
    }
}
