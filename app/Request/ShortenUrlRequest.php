<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class ShortenUrlRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'long_url' => [
                'required',
                'string',
                'url',
                'max:2048'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'long_url.required' => 'A URL original é obrigatória.',
            'long_url.string' => 'A URL original deve ser uma string.',
            'long_url.url' => 'A URL original é inválida.',
            'long_url.max' => 'A URL original deve ter no máximo 2048 caracteres.',
        ];
    }
}
