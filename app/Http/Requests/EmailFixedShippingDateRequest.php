<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe de requisição para validação configuração da data fixa de envio do e-mail.
 *
 * Responsável por definir as regras de validação e mensagens de erro 
 * para operações relacionadas configuração da data fixa de envio do e-mail, como criação e edição.
 *
 * @package App\Http\Requests
 */
class EmailFixedShippingDateRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     *
     * @return bool Retorna true para permitir a requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retorna as regras de validação aplicáveis à requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> 
     * Regras de validação.
     */
    public function rules(): array
    {
        return [
            'use_fixed_send_datetime' => 'required|boolean',
            'fixed_send_datetime' => 'required_if:use_fixed_send_datetime,true|nullable|date',
        ];
    }

    /**
     * Define mensagens personalizadas para as regras de validação.
     *
     * @return array<string, string> Mensagens de erro personalizadas.
     */
    public function messages(): array
    {
        return [
            'use_fixed_send_datetime.required' => 'O campo usar data fixa de envio é obrigatória.',
            'use_fixed_send_datetime.boolean' => 'O campo usar data fixa de envio deve ser sim ou não.',
            'fixed_send_datetime.required_if' => 'O campo data e hora é obrigatória.',
        ];
    }
}
