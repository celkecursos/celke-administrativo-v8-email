<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailAutomationActionRequest;
use App\Models\EmailAutomationAction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailAutomationActionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Recuperar os valores enviados pelo formulário
        $name = $request->input('name');

        // Query base
        $emailAutomationActions = EmailAutomationAction::query();

        // Aplicar filtros se existirem
        if (!empty($name)) {
            $emailAutomationActions->where('name', 'LIKE', "%{$name}%");
        }

        // Buscar os dados do banco de dados, com paginação
        $emailAutomationActions = $emailAutomationActions->orderBy('id', 'DESC')->paginate(10);

        // Salvar log
        Log::info('Listar as ações automatizadas.', ['action_user_id' => Auth::id()]);

        // Retornar a view com os dados
        return view('email-automation-actions.index', [
            'menu' => 'email-automation-actions',
            'emailAutomationActions' => $emailAutomationActions,
            'name' => $name,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retornar a view
        return view('email-automation-actions.create', [
            'menu' => 'email-automation-actions',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailAutomationActionRequest $request)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Cadastrar no banco de dados
            $emailAutomationAction = EmailAutomationAction::create([
                'name' => $request->name,
                'is_recursive' => $request->is_recursive,
                'is_active' => $request->is_active,
            ]);

            // Salvar log
            Log::info('Ação automatizada cadastrada.', ['id' => $emailAutomationAction->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário e enviar a mensagem de sucesso
            return redirect()->route('email-automation-actions.show', ['emailAutomationAction' => $emailAutomationAction->id])->with('success', 'Ação automatizada cadastrada com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('Ação automatizada não cadastrada.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Ação automatizada não cadastrada!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailAutomationAction $emailAutomationAction)
    {
        // Log para debug
        Log::info('Visualizar a ação automatizada.', [
            'id' => $emailAutomationAction->id,
            'action_user_id' => Auth::id()
        ]);

        // Retornar a view com os dados
        return view('email-automation-actions.show', [
            'menu' => 'email-automation-actions',
            'emailAutomationAction' => $emailAutomationAction,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailAutomationAction $emailAutomationAction)
    {
        // Retornar a view com os dados
        return view('email-automation-actions.edit', [
            'menu' => 'email-automation-actions',
            'emailAutomationAction' => $emailAutomationAction,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailAutomationActionRequest $request, EmailAutomationAction $emailAutomationAction)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Editar as informações no banco de dados
            $emailAutomationAction->update([
                'name' => $request->name,
                'is_recursive' => $request->is_recursive,
                'is_active' => $request->is_active,
            ]);

            // Salvar log
            Log::info('Ação automatizada editada.', ['id' => $emailAutomationAction->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário e enviar a mensagem de sucesso
            return redirect()->route('email-automation-actions.show', ['emailAutomationAction' => $emailAutomationAction->id])->with('success', 'Ação automatizada editada com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Ação automatizada não editada.', ['error' => $e->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Ação automatizada não editada!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailAutomationAction $emailAutomationAction)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Apagar o registro do banco de dados
            $emailAutomationAction->delete();

            // Salvar log
            Log::info('Ação automatizada apagada.', ['id' => $emailAutomationAction->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário e enviar a mensagem de sucesso
            return redirect()->route('email-automation-actions.index')->with('success', 'Ação automatizada apagada com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Ação automatizada não apagada.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Ação automatizada não apagada!');
        }
    }
}
