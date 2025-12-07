<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe de requisição para validação configuração da janela de envio do e-mail.
 *
 * Responsável por definir as regras de validação e mensagens de erro 
 * para operações relacionadas configuração da janela de envio do e-mail, como criação e edição.
 *
 * @package App\Http\Requests
 */
class EmailSubmissionWindowRequest extends FormRequest
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
            'use_send_window' => 'required|boolean',
            'send_window_start_hour' => 'required_if:use_send_window,true|nullable|integer|min:0|max:23',
            'send_window_start_minute' => 'required_if:use_send_window,true|nullable|integer|min:0|max:59',
            'send_window_end_hour' => 'required_if:use_send_window,true|nullable|integer|min:0|max:23',
            'send_window_end_minute' => 'required_if:use_send_window,true|nullable|integer|min:0|max:59',
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
            'use_send_window.required' => 'O campo usar janela de envio é obrigatório.',
            'use_send_window.boolean' => 'O campo usar janela de envio deve ser sim ou não.',

            'send_window_start_hour.required_if' => 'O campo hora inicial é obrigatória.',
            'send_window_start_hour.integer' => 'O valor do campo hora inicial deve ser um número inteiro.',
            'send_window_start_hour.min' => 'O valor do campo hora inicial deve ser um número inteiro e positivo.',
            'send_window_start_hour.max' => 'O valor do campo hora inicial deve ser um número no máximo :max.',

            'send_window_start_minute.required_if' => 'O campo minuto inicial é obrigatório.',
            'send_window_start_minute.integer' => 'O valor do campo minuto inicial deve ser um número inteiro.',
            'send_window_start_minute.min' => 'O valor do campo minuto inicial deve ser um número inteiro e positivo.',
            'send_window_start_minute.max' => 'O valor do campo minuto inicial deve ser um número no máximo :max.',

            'send_window_end_hour.required_if' => 'O campo hora final é obrigatória.',
            'send_window_end_hour.integer' => 'O valor do campo hora final deve ser um número inteiro.',
            'send_window_end_hour.min' => 'O valor do campo hora final deve ser um número inteiro e positivo.',
            'send_window_end_hour.max' => 'O valor do campo hora final deve ser um número no máximo :max.',

            'send_window_end_minute.required_if' => 'O campo minuto final é obrigatório.',
            'send_window_end_minute.integer' => 'O valor do campo minuto final deve ser um número inteiro.',
            'send_window_end_minute.min' => 'O valor do campo minuto final deve ser um número inteiro e positivo.',
            'send_window_end_minute.max' => 'O valor do campo minuto final deve ser um número no máximo :max.',
        ];
    }
}
