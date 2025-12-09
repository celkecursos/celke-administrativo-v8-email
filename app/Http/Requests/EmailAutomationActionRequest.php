<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailAutomationActionRequest extends FormRequest
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
        $emailAutomationAction = $this->route('emailAutomationAction'); // Obtém o modelo da rota, se existir (para edição)

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:email_automation_actions,name,' . ($emailAutomationAction ? $emailAutomationAction->id : 'null'),
            ],
            'is_recursive' => 'required|boolean',
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
            'name.string' => 'O campo nome deve ser texto.',
            'name.max' => 'O campo nome não pode ter mais de :max caracteres.',
            'name.unique' => 'Uma ação com esse nome já está cadastrada!',
            'is_recursive.required' => 'O campo recursivo é obrigatório.',
            'is_recursive.boolean' => 'O campo recursivo deve ser sim ou não.',
            'is_active.required' => 'O campo situação é obrigatório.',
            'is_active.boolean' => 'O campo situação deve ser ativo ou inativo.',
        ];
    }
}
