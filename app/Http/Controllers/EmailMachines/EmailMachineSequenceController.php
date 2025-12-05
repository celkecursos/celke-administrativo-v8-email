<?php

namespace App\Http\Controllers\EmailMachines;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailMachineSequenceRequest;
use App\Models\EmailMachine;
use App\Models\EmailMachineSequence;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailMachineSequenceController extends Controller
{
    // Listar as sequências de uma máquina
    public function index($emailMachineId)
    {
        // Buscar a máquina
        $emailMachine = EmailMachine::findOrFail($emailMachineId);

        // Recuperar as sequências desta máquina com seus e-mails ordenados
        $sequences = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->with(['emails' => function($query) {
                $query->orderBy('order', 'ASC');
            }])
            ->orderBy('id', 'ASC')
            ->get();

        // Salvar log
        Log::info('Listar as sequências da máquina.', [
            'email_machine_id' => $emailMachineId,
            'action_user_id' => Auth::id()
        ]);

        // Carregar a view 
        return view('email-machine-sequences.index', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequences' => $sequences
        ]);
    }

    // Visualizar os detalhes da sequência
    public function show($emailMachineId, $id)
    {
        // Buscar a máquina e a sequência
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($id);

        // Log para debug
        Log::info('Visualizar a sequência.', [
            'id' => $sequence->id,
            'email_machine_id' => $emailMachineId,
            'action_user_id' => Auth::id()
        ]);

        // Carregar a view 
        return view('email-machine-sequences.show', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence
        ]);
    }

    // Carregar o formulário cadastrar nova sequência
    public function create($emailMachineId)
    {
        // Buscar a máquina
        $emailMachine = EmailMachine::findOrFail($emailMachineId);

        // Carregar a view 
        return view('email-machine-sequences.create', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine
        ]);
    }

    // Cadastrar no banco de dados a nova sequência
    public function store(EmailMachineSequenceRequest $request, $emailMachineId)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Log para debug
            Log::info('Iniciando cadastro de sequência.', [
                'email_machine_id' => $emailMachineId,
                'action_user_id' => Auth::id()
            ]);

            // Cadastrar no banco de dados
            $sequence = EmailMachineSequence::create([
                'email_machine_id' => $emailMachineId,
                'name' => $request->name,
                'is_active' => $request->is_active ?? 0,
            ]);

            // Salvar log
            Log::info('Sequência cadastrada.', [
                'id' => $sequence->id,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()
                ->route('email-machine-sequences.index', ['emailMachine' => $emailMachineId])
                ->with('success', 'Sequência cadastrada com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('Sequência não cadastrada.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Sequência não cadastrada!');
        }
    }

    // Carregar o formulário editar sequência
    public function edit($emailMachineId, $id)
    {
        // Buscar a máquina e a sequência
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($id);

        // Carregar a view 
        return view('email-machine-sequences.edit', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence
        ]);
    }

    // Editar no banco de dados a sequência
    public function update(EmailMachineSequenceRequest $request, $emailMachineId, $id)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Buscar a sequência
            $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
                ->findOrFail($id);

            // Editar as informações do registro no banco de dados
            $sequence->update([
                'name' => $request->name,
                'is_active' => $request->is_active ?? 0,
            ]);

            // Salvar log
            Log::info('Sequência editada.', [
                'id' => $sequence->id,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()
                ->route('email-machine-sequences.show', [
                    'emailMachine' => $emailMachineId,
                    'sequence' => $sequence->id
                ])
                ->with('success', 'Sequência editada com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Sequência não editada.', ['error' => $e->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Sequência não editada!');
        }
    }

    // Excluir a sequência do banco de dados
    public function destroy($emailMachineId, $id)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Buscar a sequência
            $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
                ->findOrFail($id);

            // Excluir o registro do banco de dados
            $sequence->delete();

            // Salvar log
            Log::info('Sequência apagada.', [
                'id' => $sequence->id,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()
                ->route('email-machine-sequences.index', ['emailMachine' => $emailMachineId])
                ->with('success', 'Sequência apagada com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Sequência não apagada.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Sequência não apagada!');
        }
    }
}
