<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailSendingConfigSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // A autorização é feita via middleware da rota
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
            // Quantidade de e-mails enviados por requisição
            'send_quantity_per_request' => [
                'required',
                'integer',
                'min:1',
            ],

            // Quantidade de e-mails enviados por hora
            'send_quantity_per_hour' => [
                'required',
                'integer',
                'min:1',
            ],

            // Ativo para envio de e-mails de marketing
            'is_active_marketing' => [
                'required',
                'boolean',
            ],

            // Ativo para envio de e-mails transacionais
            'is_active_transactional' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'send_quantity_per_request.required' => 'O campo quantidade por requisição é obrigatório.',
            'send_quantity_per_request.integer' => 'A quantidade por requisição deve ser um número inteiro.',
            'send_quantity_per_request.min' => 'A quantidade por requisição deve ser no mínimo :min.',

            'send_quantity_per_hour.required' => 'O campo quantidade por hora é obrigatório.',
            'send_quantity_per_hour.integer' => 'A quantidade por hora deve ser um número inteiro.',
            'send_quantity_per_hour.min' => 'A quantidade por hora deve ser no mínimo :min.',

            'is_active_marketing.required' => 'Informe se o servidor está ativo para e-mails de marketing.',
            'is_active_marketing.boolean' => 'O campo marketing deve ser ativo ou inativo.',

            'is_active_transactional.required' => 'Informe se o servidor está ativo para e-mails transacionais.',
            'is_active_transactional.boolean' => 'O campo transacional deve ser ativo ou inativo.',
        ];
    }
}