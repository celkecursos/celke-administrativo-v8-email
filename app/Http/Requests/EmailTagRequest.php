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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $emailTag = $this->route('emailTag'); // Obtém o modelo EmailTag da rota, se existir (para edição)

        return [
            'name' => [
                'required',
                'regex:/^[a-z0-9\-]+$/', // Apenas letras minúsculas, números e hífens, sem espaços ou caracteres especiais
                'max:255',
                'unique:email_tags,name,' . ($emailTag ? $emailTag->id : 'null'),
            ],
            'is_active' => 'required|boolean',
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
            'name.unique' => "A tag com esse nome já está cadastrada!",
            'is_active.required' => 'O campo situação é obrigatório.',
            'is_active.boolean' => 'O campo situação deve ser ativo ou inativo.',
        ];
    }
}
