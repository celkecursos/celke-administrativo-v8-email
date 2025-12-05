<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe de requisição para validação de e-mail da sequência.
 *
 * Responsável por definir as regras de validação e mensagens de erro 
 * para operações relacionadas a e-mails de sequência, como criação e edição.
 *
 * @package App\Http\Requests
 */
class EmailSequenceEmailRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'skip_email' => 'nullable|boolean',
            'delay_day' => 'nullable|integer|min:0',
            'delay_hour' => 'nullable|integer|min:0|max:23',
            'delay_minute' => 'nullable|integer|min:0|max:59',
            'fixed_send_datetime' => 'nullable|date',
            'use_fixed_send_datetime' => 'nullable|boolean',
            'send_window_start_hour' => 'nullable|integer|min:0|max:23',
            'send_window_start_minute' => 'nullable|integer|min:0|max:59',
            'send_window_end_hour' => 'nullable|integer|min:0|max:23',
            'send_window_end_minute' => 'nullable|integer|min:0|max:59',
            'email_machine_sequence_id' => 'required|exists:email_machine_sequences,id',
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
            'title.required' => 'Campo título é obrigatório!',
            'title.string' => 'Campo título deve ser um texto válido!',
            'title.max' => 'Campo título não pode ter mais de 255 caracteres!',
            'content.required' => 'Campo conteúdo é obrigatório!',
            'content.string' => 'Campo conteúdo deve ser um texto válido!',
            'delay_day.integer' => 'Campo dias deve ser um número inteiro!',
            'delay_day.min' => 'Campo dias não pode ser negativo!',
            'delay_hour.integer' => 'Campo horas deve ser um número inteiro!',
            'delay_hour.min' => 'Campo horas não pode ser negativo!',
            'delay_hour.max' => 'Campo horas deve estar entre 0 e 23!',
            'delay_minute.integer' => 'Campo minutos deve ser um número inteiro!',
            'delay_minute.min' => 'Campo minutos não pode ser negativo!',
            'delay_minute.max' => 'Campo minutos deve estar entre 0 e 59!',
            'fixed_send_datetime.date' => 'Campo data de envio deve ser uma data válida!',
            'send_window_start_hour.integer' => 'Hora inicial da janela deve ser um número inteiro!',
            'send_window_start_hour.min' => 'Hora inicial da janela não pode ser negativa!',
            'send_window_start_hour.max' => 'Hora inicial da janela deve estar entre 0 e 23!',
            'send_window_start_minute.integer' => 'Minuto inicial da janela deve ser um número inteiro!',
            'send_window_start_minute.min' => 'Minuto inicial da janela não pode ser negativo!',
            'send_window_start_minute.max' => 'Minuto inicial da janela deve estar entre 0 e 59!',
            'send_window_end_hour.integer' => 'Hora final da janela deve ser um número inteiro!',
            'send_window_end_hour.min' => 'Hora final da janela não pode ser negativa!',
            'send_window_end_hour.max' => 'Hora final da janela deve estar entre 0 e 23!',
            'send_window_end_minute.integer' => 'Minuto final da janela deve ser um número inteiro!',
            'send_window_end_minute.min' => 'Minuto final da janela não pode ser negativo!',
            'send_window_end_minute.max' => 'Minuto final da janela deve estar entre 0 e 59!',
            'email_machine_sequence_id.required' => 'Campo sequência é obrigatório!',
            'email_machine_sequence_id.exists' => 'Sequência selecionada não existe!',
        ];
    }
}
