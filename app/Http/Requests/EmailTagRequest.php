<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class EmailTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Remove espaços em branco e converte para minúsculas
        if ($this->has('name')) {
            $this->merge([
                'name' => Str::lower(str_replace(' ', '', $this->name)),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'regex:/^[a-z0-9\-]+$/', // Apenas letras minúsculas, números e hífens, sem espaços ou caracteres especiais
                'max:255',
            ],
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.regex' => 'O campo nome deve conter apenas letras minúsculas, números e hífens, sem espaços ou caracteres especiais.',
            'name.max' => 'O campo nome não pode ter mais de :max caracteres.',
            'is_active.boolean' => 'O campo status deve ser verdadeiro ou falso.',
        ];
    }
}
