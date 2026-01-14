<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailSendingConfigApiRequest extends FormRequest
{
    /**
     * Define se o usuário está autorizado
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação
     * Usada para o formulário Credenciais:
     * - provider
     * - host
     * - username
     */
    public function rules(): array
    {
        // Recupera o modelo da rota (edição)
        $emailSendingConfig = $this->route('emailSendingConfig');

        return [
            // Provedor
            'provider' => [
                'required',
                'string',
                'max:255',
                'unique:email_sending_configs,provider,' . ($emailSendingConfig?->id),
            ],
        ];
    }

    /**
     * Mensagens de erro personalizadas
     */
    public function messages(): array
    {
        return [
            'provider.required' => 'O campo provedor é obrigatório.',
            'provider.string' => 'O campo provedor deve ser um texto.',
            'provider.max' => 'O campo provedor não pode ter mais de :max caracteres.',
            'provider.unique' => 'Já existe um servidor cadastrado com este provedor.',
        ];
    }
}