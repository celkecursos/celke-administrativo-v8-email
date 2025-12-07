<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe de requisição para validação configuração do conteúdo do e-mail.
 *
 * Responsável por definir as regras de validação e mensagens de erro 
 * para operações relacionadas configuração do conteúdo do e-mail, como criação e edição.
 *
 * @package App\Http\Requests
 */
class EmailSequenceEmailConfigRequest extends FormRequest
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
            'is_active' => 'required|boolean',
            'skip_email' => 'required|boolean',
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
            'is_active.required' => 'O campo situação é obrigatório.',
            'is_active.boolean' => 'O campo situação deve ser sim ou não.',
            'skip_email.required' => 'O campo pular este e-mail é obrigatório.',
            'skip_email.boolean' => 'O campo pular este e-mail deve ser ativo ou inativo.',
        ];
    }
}
