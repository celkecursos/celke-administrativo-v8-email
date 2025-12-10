<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailAutomationActionRequest;
use App\Models\EmailAutomationAction;
use App\Models\EmailAutomationTrigger;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailAutomationActionController extends Controller
{
    /**
     * Exibe a listagem paginada de todas as ações automatizadas com filtro por nome
     */
    public function index(Request $request)
    {
        // Captura o termo de busca enviado pelo formulário (campo nome)
        $name = $request->input('name');

        // Inicia a query base para ações automatizadas
        $emailAutomationActions = EmailAutomationAction::query();

        // Aplica filtro por nome (busca parcial, case-insensitive)
        if (!empty($name)) {
            $emailAutomationActions->where('name', 'LIKE', "%{$name}%");
        }

        // Ordena do mais recente para o mais antigo e aplica paginação (10 por página)
        $emailAutomationActions = $emailAutomationActions->orderBy('id', 'DESC')->paginate(10);

        // Registra no log a visualização da listagem
        Log::info('Listagem de ações automatizadas acessada.', ['action_user_id' => Auth::id()]);

        // Retorna a view com os dados e mantém o termo de busca para o campo do formulário
        return view('email-automation-actions.index', [
            'menu' => 'email-automation-actions',
            'emailAutomationActions' => $emailAutomationActions,
            'name' => $name, // mantém o filtro ativo após paginação
        ]);
    }

    /**
     * Exibe o formulário para criação de uma nova ação automatizada
     */
    public function create()
    {
        // Registra acesso ao formulário (opcional, mas recomendado para auditoria)
        Log::info('Formulário de criação de ação automatizada acessado.', ['action_user_id' => Auth::id()]);

        return view('email-automation-actions.create', [
            'menu' => 'email-automation-actions',
        ]);
    }

    /**
     * Salva uma nova ação automatizada no banco de dados
     */
    public function store(EmailAutomationActionRequest $request)
    {
        try {
            // Cria o registro no banco com os dados validados pelo Form Request
            $emailAutomationAction = EmailAutomationAction::create([
                'name'         => $request->name,
                'is_recursive' => $request->is_recursive ? 1 : 0,
                'is_active'    => $request->is_active ? 1 : 0,
            ]);

            // Log de sucesso com ID do registro criado
            Log::info('Ação automatizada cadastrada com sucesso.', [
                'id'             => $emailAutomationAction->id,
                'action_user_id' => Auth::id()
            ]);

            // Redireciona para a tela de visualização com mensagem de sucesso
            return redirect()
                ->route('email-automation-actions.show', $emailAutomationAction)
                ->with('success', 'Ação automatizada cadastrada com sucesso!');
        } catch (Exception $e) {
            // Log detalhado do erro para análise posterior
            Log::notice('Falha ao cadastrar ação automatizada.', [
                'error'          => $e->getMessage(),
                'action_user_id' => Auth::id(),
                'request_data'   => $request->except('_token')
            ]);

            // Volta ao formulário com os dados preenchidos e mensagem de erro
            return back()->withInput()->with('error', 'Erro ao cadastrar ação automatizada. Tente novamente.');
        }
    }

    /**
     * Exibe os detalhes de uma ação automatizada específica, incluindo seus gatilhos vinculados
     */
    public function show(EmailAutomationAction $emailAutomationAction)
    {
        // Carrega os gatilhos relacionados à ação automatizada
        $emailAutomationTriggers = EmailAutomationTrigger::with([
            'automationAction',
            'filterType',
            'actionType'
        ])
            ->where('email_automation_action_id', $emailAutomationAction->id)
            ->orderBy('id', 'asc')
            ->get();

        // Registra visualização detalhada da ação
        Log::info('Visualização detalhada da ação automatizada.', [
            'id'             => $emailAutomationAction->id,
            'action_user_id' => Auth::id()
        ]);

        return view('email-automation-actions.show', [
            'menu'                  => 'email-automation-actions',
            'emailAutomationAction' => $emailAutomationAction,
            // 'triggers'              => $emailAutomationAction->triggers,
            'emailAutomationTriggers'              => $emailAutomationTriggers,
        ]);
    }

    /**
     * Exibe o formulário para edição de uma ação automatizada existente
     */
    public function edit(EmailAutomationAction $emailAutomationAction)
    {
        Log::info('Formulário de edição de ação automatizada acessado.', [
            'id'             => $emailAutomationAction->id,
            'action_user_id' => Auth::id()
        ]);

        return view('email-automation-actions.edit', [
            'menu'                  => 'email-automation-actions',
            'emailAutomationAction' => $emailAutomationAction,
        ]);
    }

    /**
     * Atualiza os dados de uma ação automatizada no banco
     */
    public function update(EmailAutomationActionRequest $request, EmailAutomationAction $emailAutomationAction)
    {
        try {
            // Atualiza apenas os campos permitidos
            $emailAutomationAction->update([
                'name'         => $request->name,
                'is_recursive' => $request->is_recursive ? 1 : 0,
                'is_active'    => $request->is_active ? 1 : 0,
            ]);

            Log::info('Ação automatizada atualizada com sucesso.', [
                'id'             => $emailAutomationAction->id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('email-automation-actions.show', $emailAutomationAction)
                ->with('success', 'Ação automatizada editada com sucesso!');
        } catch (Exception $e) {
            Log::notice('Falha ao editar ação automatizada.', [
                'error'          => $e->getMessage(),
                'id'             => $emailAutomationAction->id,
                'action_user_id' => Auth::id()
            ]);

            return back()->withInput()->with('error', 'Erro ao editar ação automatizada. Tente novamente.');
        }
    }

    /**
     * Remove permanentemente uma ação automatizada do sistema
     */
    public function destroy(EmailAutomationAction $emailAutomationAction)
    {
        try {
            $actionId = $emailAutomationAction->id;

            // Exclui o registro (cuidado: gatilhos relacionados podem precisar de cascade ou soft delete)
            $emailAutomationAction->delete();

            Log::info('Ação automatizada excluída com sucesso.', [
                'id'             => $actionId,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('email-automation-actions.index')
                ->with('success', 'Ação automatizada apagada com sucesso!');
        } catch (Exception $e) {
            Log::notice('Falha ao excluir ação automatizada.', [
                'error'          => $e->getMessage(),
                'id'             => $emailAutomationAction->id ?? 'indefinido',
                'action_user_id' => Auth::id()
            ]);

            return back()->with('error', 'Erro ao excluir ação automatizada. Pode haver gatilhos vinculados.');
        }
    }
}
