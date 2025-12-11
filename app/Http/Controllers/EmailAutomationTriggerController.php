<?php

namespace App\Http\Controllers;

use App\Models\EmailAutomationTrigger;
use App\Http\Requests\EmailAutomationTriggerRequest; // ✔️ Agora usando FormRequest
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class EmailAutomationTriggerController extends Controller
{
    /**
     * Exibe o formulário de edição de um gatilho de automação.
     * 
     * O Laravel realiza o Model Binding automaticamente, então
     * o $emailAutomationTrigger já chega como uma instância válida do modelo.
     */
    public function edit(EmailAutomationTrigger $emailAutomationTrigger)
    {
        // Log simples para auditoria: quem acessou e qual gatilho foi aberto
        Log::info('Formulário de edição de gatilho acessado.', [
            'id'             => $emailAutomationTrigger->id,
            'action_user_id' => Auth::id()
        ]);

        return view('email-automation-triggers.edit', [
            'emailAutomationTrigger' => $emailAutomationTrigger,
            'menu'                   => 'email-automation-actions',
        ]);
    }

    /**
     * Atualiza os dados do gatilho no banco de dados.
     * 
     * BENEFÍCIOS DO USO DE EmailAutomationTriggerRequest:
     * - Validação é feita automaticamente antes de chegar aqui
     * - O controller fica mais limpo
     * - O código segue padrão profissional
     */
    public function update(EmailAutomationTriggerRequest $request, EmailAutomationTrigger $emailAutomationTrigger)
    {
        try {

            // update() recebe apenas os campos validados pelo FormRequest
            $emailAutomationTrigger->update([
                'is_active'            => (int) $request->is_active, // Garantindo tipo inteiro
                'email_filter_type_id' => $request->email_filter_type_id,
                'email_action_type_id' => $request->email_action_type_id,
            ]);

            Log::info('Gatilho de automação atualizado com sucesso.', [
                'id'             => $emailAutomationTrigger->id,
                'action_user_id' => Auth::id(),
            ]);

            return redirect()
                ->route('email-automation-triggers.show', $emailAutomationTrigger)
                ->with('success', 'Gatilho atualizado com sucesso!');

        } catch (Exception $e) {

            // Log detalhado para investigação
            Log::notice('Falha ao atualizar gatilho de automação.', [
                'error'        => $e->getMessage(),
                'id'           => $emailAutomationTrigger->id,
                'action_user_id' => Auth::id(),
                'request_data' => $request->except('_token'), // Muito útil em debug
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar o gatilho. Tente novamente.');
        }
    }

    /**
     * Exibe os detalhes de um gatilho de automação.
     * 
     * Carrega vários relacionamentos importantes para a lógica de automações.
     * 
     * O uso de ->load() evita problema de N+1 queries.
     */
    public function show(EmailAutomationTrigger $emailAutomationTrigger)
    {
        $emailAutomationTrigger->load([
            'automationAction',
            'filterType',
            'actionType',
            'filterEmailMachine',
            'filterEmailMachineSequence',
            'filterEmailSequenceEmail',
            'actionAddEmailTag',
            'actionRemoveEmailTag',
            'actionAddEmailSequenceEmail',
            'actionRemoveEmailSequenceEmail',
            'actionRemoveEmailMachineSequence',
        ]);

        Log::info('Visualizar gatilho de automação.', [
            'id'             => $emailAutomationTrigger->id,
            'action_user_id' => Auth::id()
        ]);

        return view('email-automation-triggers.show', [
            'emailAutomationTrigger' => $emailAutomationTrigger,
            'menu'                   => 'email-automation-actions',
        ]);
    }
    
    /**
     * Remove um gatilho de automação.
     * 
     * Utiliza try/catch com logs detalhados para garantir segurança
     * e facilitar depuração em caso de falhas.
     */
    public function destroy(EmailAutomationTrigger $emailAutomationTrigger)
    {
        try {
            $id = $emailAutomationTrigger->id;

            $emailAutomationTrigger->delete();

            Log::info('Gatilho excluído com sucesso.', [
                'id'             => $id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->back()
                ->with('success', 'Gatilho apagado com sucesso!');

        } catch (Exception $e) {

            Log::notice('Erro ao excluir gatilho.', [
                'error'          => $e->getMessage(),
                'id'             => $emailAutomationTrigger->id,
                'action_user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Erro ao apagar gatilho.');
        }
    }
}