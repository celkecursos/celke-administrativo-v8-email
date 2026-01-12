<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailSendingConfigSenderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // A autorização é controlada via middleware da rota
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
            // Nome do remetente
            'from_name' => [
                'required',
                'string',
                'max:255',
            ],

            // E-mail do remetente
            'from_email' => [
                'required',
                'email',
                'max:255',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'from_name.required' => 'O campo nome do remetente é obrigatório.',
            'from_name.string' => 'O campo nome do remetente deve ser um texto.',
            'from_name.max' => 'O campo nome do remetente não pode ter mais de :max caracteres.',

            'from_email.required' => 'O campo e-mail do remetente é obrigatório.',
            'from_email.email' => 'Informe um e-mail válido para o remetente.',
            'from_email.max' => 'O campo e-mail do remetente não pode ter mais de :max caracteres.',
        ];
    }
}