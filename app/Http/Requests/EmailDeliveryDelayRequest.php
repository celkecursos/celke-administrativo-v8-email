<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe de requisição para validação configuração do atraso do envio do e-mail.
 *
 * Responsável por definir as regras de validação e mensagens de erro 
 * para operações relacionadas configuração do atraso do envio do e-mail, como criação e edição.
 *
 * @package App\Http\Requests
 */
class EmailDeliveryDelayRequest extends FormRequest
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
            'delay_day' => 'nullable|integer|min:0',
            'delay_hour' => 'nullable|integer|min:0|max:23',
            'delay_minute' => 'nullable|integer|min:0|max:59',
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
            'delay_day.integer' => 'O valor do campo dia deve ser um número inteiro.',
            'delay_day.min' => 'O valor do campo dia deve ser um número inteiro e positivo.',

            'delay_hour.integer' => 'O valor do campo hora deve ser um número inteiro.',
            'delay_hour.min' => 'O valor do campo hora deve ser um número inteiro e positivo.',
            'delay_hour.max' => 'O valor do campo hora deve ser um número no máximo :max.',

            'delay_minute.integer' => 'O valor do campo minuto deve ser um número inteiro.',
            'delay_minute.min' => 'O valor do campo minuto deve ser um número inteiro e positivo.',
            'delay_minute.max' => 'O valor do campo minuto deve ser um número no máximo :max.',
        ];
    }
}
