<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailAutomationTriggerRequest extends FormRequest
{
    /**
     * Define se o usuário está autorizado a enviar esta request.
     * Aqui deixamos como true para permitir o uso sem verificar permissões.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para criação/edição de gatilho de automação.
     */
    public function rules(): array
    {
        return [
            'is_active'            => 'required|in:0,1',
            'email_filter_type_id' => 'required|exists:email_filter_types,id',
            'email_action_type_id' => 'required|exists:email_action_types,id',
        ];
    }

    /**
     * Mensagens personalizadas para exibir erros amigáveis para o usuário.
     */
    public function messages(): array
    {
        return [
            'is_active.required' => 'O campo situação é obrigatório.',
            'is_active.in'       => 'O campo situação deve ser ativo ou inativo.',

            'email_filter_type_id.required' => 'O tipo de filtro é obrigatório.',
            'email_filter_type_id.exists'   => 'O tipo de filtro selecionado é inválido.',

            'email_action_type_id.required' => 'O tipo de ação é obrigatório.',
            'email_action_type_id.exists'   => 'O tipo de ação selecionado é inválido.',
        ];
    }
}