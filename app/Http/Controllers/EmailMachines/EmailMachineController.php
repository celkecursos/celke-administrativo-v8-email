<?php

namespace App\Http\Controllers\EmailMachines;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailMachineRequest;
use App\Models\EmailMachine;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailMachineController extends Controller
{
    // Listar as máquinas
    public function index(Request $request)
    {
        // Recuperar os registros do banco dados
        $emailMachines = EmailMachine::when(
            $request->filled('name'),
            fn($query) =>
            $query->whereLike('name', '%' . $request->name .  '%')
        )
            ->orderBy('name', 'ASC')
            ->paginate(10)
            ->withQueryString();

        // Salvar log
        Log::info('Listar as máquinas.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('email-machines.index', [
            'menu' => 'email-machine',
            'name' => $request->name,
            'emailMachines' => $emailMachines
        ]);
    }

    // Visualizar os detalhes do máquina
    public function show(EmailMachine $emailMachine)
    {

        // Log para debug
        Log::info('Visualizar a máquina.', [
            'id' => $emailMachine->id, 
            'action_user_id' => Auth::id()
        ]);

        // Carregar a view 
        return view('email-machines.show', ['menu' => 'email-machine', 'emailMachine' => $emailMachine]);
    }

    // Carregar o formulário cadastrar nova máquina
    public function create()
    {
        // Carregar a view 
        return view('email-machines.create', [
            'menu' => 'email-machine',
        ]);
    }

    // Cadastrar no banco de dados o novo máquina
    public function store(EmailMachineRequest $request)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Log para debug
            Log::info('Iniciando cadastro de máquina.', ['action_user_id' => Auth::id()]);

            // Cadastrar no banco de dados na tabela máquina
            $emailMachine = EmailMachine::create([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);

            // Salvar log
            Log::info('Máquina cadastrada.', ['id' => $emailMachine->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('email-machines.show', ['emailMachine' => $emailMachine->id])->with('success', 'Máquina cadastrada com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('Máquina não cadastrada.', [
                'error' => $e->getMessage(), 
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Máquina não cadastrada!');
        }
    }

    // Carregar o formulário editar máquina
    public function edit(EmailMachine $emailMachine)
    {
        // Carregar a view 
        return view('email-machines.edit', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine
        ]);
    }

    // Editar no banco de dados o máquina
    public function update(EmailMachineRequest $request, EmailMachine $emailMachine)
    {
        // Capturar possíveis exceções durante a execução.
        try {

            // Editar as informações do registro no banco de dados
            $emailMachine->update([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);

            // Salvar log
            Log::info('Máquina editada.', ['id' => $emailMachine->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('email-machines.show', ['emailMachine' => $emailMachine->id])->with('success', 'Máquina editada com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Máquina não editada.', ['error' => $e->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Máquina não editada!');
        }
    }

    // Excluir a máquina do banco de dados
    public function destroy(EmailMachine $emailMachine)
    {
        // Capturar possíveis exceções durante a execução.
        try {

            // Excluir o registro do banco de dados
            $emailMachine->delete();

            // Salvar log
            Log::info('Máquina apagada.', ['id' => $emailMachine->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()->route('email-machines.index')->with('success', 'Máquina apagada com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Máquina não apagada.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Máquina não apagada!');
        }
    }
}
