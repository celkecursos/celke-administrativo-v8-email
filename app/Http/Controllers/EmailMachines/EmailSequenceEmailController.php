<?php

namespace App\Http\Controllers\EmailMachines;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailDeliveryDelayRequest;
use App\Http\Requests\EmailFixedShippingDateRequest;
use App\Http\Requests\EmailSequenceEmailConfigRequest;
use App\Http\Requests\EmailSequenceEmailRequest;
use App\Http\Requests\EmailSubmissionWindowRequest;
use App\Models\EmailMachine;
use App\Models\EmailMachineSequence;
use App\Models\EmailSequenceEmail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailSequenceEmailController extends Controller
{
    // Carregar o formulário cadastrar novo e-mail
    public function create($emailMachineId, $sequenceId)
    {
        // Buscar a máquina e a sequência
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($sequenceId);

        // Carregar a view 
        return view('email-sequence-emails.create', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence
        ]);
    }

    // Cadastrar no banco de dados o novo e-mail
    public function store(EmailSequenceEmailRequest $request, $emailMachineId, $sequenceId)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Log para debug
            Log::info('Iniciando cadastro de e-mail da sequência.', [
                'email_machine_sequence_id' => $sequenceId,
                'action_user_id' => Auth::id()
            ]);

            // Buscar a última ordem da sequência
            $lastOrder = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->max('order');

            // Definir a próxima ordem
            $nextOrder = $lastOrder ? $lastOrder + 1 : 1;

            // Cadastrar no banco de dados
            $email = EmailSequenceEmail::create([
                'email_machine_sequence_id' => $sequenceId,
                'title' => $request->title,
                'content' => 'Conteúdo padrão. Edite este texto.',
                'order' => $nextOrder,
                'is_active' => 1,
                'skip_email' => 0,
            ]);

            // Salvar log
            Log::info('E-mail da sequência cadastrado.', [
                'id' => $email->id,
                'order' => $nextOrder,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar para página de edição de conteúdo
            return redirect()
                ->route('email-sequence-emails.edit', [
                    'emailMachine' => $emailMachineId,
                    'sequence' => $sequenceId,
                    'email' => $email->id
                ])
                ->with('success', 'E-mail cadastrado com sucesso! Agora edite o conteúdo.');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('E-mail da sequência não cadastrado.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'E-mail não cadastrado!');
        }
    }

    // Alternar o status is_active (ativar/desativar)
    public function toggleStatus($emailMachineId, $sequenceId, $id)
    {
        try {
            // Buscar o e-mail
            $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->findOrFail($id);
            
            // Inverter o valor de is_active
            $email->is_active = !$email->is_active;
            $email->save();

            // Salvar log
            Log::info('Status do e-mail da sequência alterado.', [
                'id' => $email->id,
                'is_active' => $email->is_active,
                'action_user_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Status alterado com sucesso!');
        } catch (Exception $e) {
            // Salvar log
            Log::notice('Status do e-mail da sequência não foi alterado.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Erro ao alterar status!');
        }
    }

    // Carregar a página para ordenar e-mails da sequência
    public function order($emailMachineId, $sequenceId)
    {
        // Buscar a máquina e a sequência
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($sequenceId);
        
        // Buscar os e-mails ordenados
        $emails = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
            ->orderBy('order', 'ASC')
            ->get();

        // Carregar a view 
        return view('email-sequence-emails.order', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence,
            'emails' => $emails
        ]);
    }

    // Atualizar a ordem dos e-mails
    public function updateOrder($emailMachineId, $sequenceId)
    {
        try {
            $orders = request()->input('orders', []);

            // Log para debug
            Log::info('Atualizando ordem dos e-mails.', [
                'email_machine_sequence_id' => $sequenceId,
                'orders' => $orders,
                'action_user_id' => Auth::id()
            ]);

            // Atualizar a ordem de cada e-mail
            foreach ($orders as $emailId => $order) {
                EmailSequenceEmail::where('id', $emailId)
                    ->where('email_machine_sequence_id', $sequenceId)
                    ->update(['order' => $order]);
            }

            // Salvar log
            Log::info('Ordem dos e-mails atualizada.', [
                'email_machine_sequence_id' => $sequenceId,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('email-machine-sequences.index', ['emailMachine' => $emailMachineId])
                ->with('success', 'Ordem atualizada com sucesso!');
        } catch (Exception $e) {
            // Salvar log detalhado do erro
            Log::notice('Ordem dos e-mails não foi atualizada.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Erro ao atualizar ordem!');
        }
    }

    // Mover e-mail para cima
    public function moveUp($emailMachineId, $sequenceId, $id)
    {
        try {
            // Buscar o e-mail atual
            $currentEmail = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->findOrFail($id);

            // Buscar o e-mail anterior (que está imediatamente acima)
            $previousEmail = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->where('order', '<', $currentEmail->order)
                ->orderBy('order', 'DESC')
                ->first();

            // Se não houver e-mail anterior, não fazer nada
            if (!$previousEmail) {
                return redirect()
                    ->route('email-machine-sequences.index', ['emailMachine' => $emailMachineId])
                    ->with('info', 'O e-mail já está no topo!');
            }

            // Trocar as ordens
            $currentOrder = $currentEmail->order;
            $previousOrder = $previousEmail->order;

            $currentEmail->update(['order' => $previousOrder]);
            $previousEmail->update(['order' => $currentOrder]);

            // Salvar log
            Log::info('E-mail movido para cima.', [
                'email_id' => $currentEmail->id,
                'from_order' => $currentOrder,
                'to_order' => $previousOrder,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('email-machine-sequences.index', ['emailMachine' => $emailMachineId])
                ->with('success', 'E-mail movido para cima com sucesso!');
        } catch (Exception $e) {
            Log::notice('Erro ao mover e-mail para cima.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Erro ao mover e-mail!');
        }
    }

    // Mover e-mail para baixo
    public function moveDown($emailMachineId, $sequenceId, $id)
    {
        try {
            // Buscar o e-mail atual
            $currentEmail = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->findOrFail($id);

            // Buscar o e-mail seguinte (que está imediatamente abaixo)
            $nextEmail = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->where('order', '>', $currentEmail->order)
                ->orderBy('order', 'ASC')
                ->first();

            // Se não houver e-mail seguinte, não fazer nada
            if (!$nextEmail) {
                return redirect()
                    ->route('email-machine-sequences.index', ['emailMachine' => $emailMachineId])
                    ->with('info', 'O e-mail já está no final!');
            }

            // Trocar as ordens
            $currentOrder = $currentEmail->order;
            $nextOrder = $nextEmail->order;

            $currentEmail->update(['order' => $nextOrder]);
            $nextEmail->update(['order' => $currentOrder]);

            // Salvar log
            Log::info('E-mail movido para baixo.', [
                'email_id' => $currentEmail->id,
                'from_order' => $currentOrder,
                'to_order' => $nextOrder,
                'action_user_id' => Auth::id()
            ]);

            return redirect()
                ->route('email-machine-sequences.index', ['emailMachine' => $emailMachineId])
                ->with('success', 'E-mail movido para baixo com sucesso!');
        } catch (Exception $e) {
            Log::notice('Erro ao mover e-mail para baixo.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Erro ao mover e-mail!');
        }
    }

    // Carregar o formulário editar e-mail da sequência
    public function edit($emailMachineId, $sequenceId, $id)
    {
        // Buscar a máquina, sequência e o e-mail
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($sequenceId);
        $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
            ->findOrFail($id);

        // Carregar a view 
        return view('email-sequence-emails.edit', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence,
            'email' => $email
        ]);
    }

    // Editar no banco de dados o e-mail da sequência (Conteúdo)
    public function update(EmailSequenceEmailRequest $request, $emailMachineId, $sequenceId, $id)
    {
        // Validação adicional para garantir que o conteúdo seja obrigatório na edição
        $request->validate([
            'content' => 'required|string',
        ], [
            'content.required' => 'Campo conteúdo é obrigatório!',
        ]);

        // Capturar possíveis exceções durante a execução.
        try {
            // Buscar o e-mail
            $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->findOrFail($id);

            // Editar as informações do registro no banco de dados
            $email->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            // Salvar log
            Log::info('Conteúdo do e-mail editado.', [
                'id' => $email->id,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()
                ->route('email-sequence-emails.edit', [
                    'emailMachine' => $emailMachineId,
                    'sequence' => $sequenceId,
                    'email' => $id
                ])
                ->with('success', 'Conteúdo editado com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('Conteúdo do e-mail não editado.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Conteúdo não editado!');
        }
    }

    // Carregar o formulário editar datas do e-mail
    public function editDates($emailMachineId, $sequenceId, $id)
    {
        // Buscar a máquina, sequência e o e-mail
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($sequenceId);
        $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
            ->findOrFail($id);

        // Carregar a view 
        return view('email-sequence-emails.edit_dates', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence,
            'email' => $email
        ]);
    }  

    // Editar no banco de dados as datas do e-mail
    public function updateDeliveryDelay($emailMachineId, $sequenceId, $id, EmailDeliveryDelayRequest $request)
    {

        // Capturar possíveis exceções durante a execução.
        try {
            // Buscar o e-mail
            $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->findOrFail($id);

            // Editar as informações do registro no banco de dados
            $email->update([
                'delay_day' => $request->delay_day ?? 0,
                'delay_hour' => $request->delay_hour ?? 0,
                'delay_minute' => $request->delay_minute ?? 0,
            ]);

            // Salvar log
            Log::info('Data de atraso de envio editado.', [
                'id' => $email->id,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()
                ->route('email-sequence-emails.edit-dates', [
                    'emailMachine' => $emailMachineId,
                    'sequence' => $sequenceId,
                    'email' => $id
                ])
                ->with('success', 'Data de atraso de envio editado com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('Data de atraso de envio não editado.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Data de atraso de envio não editado!');
        }
    }

    // Editar no banco de dados as datas do e-mail
    public function updateFixedShippingDate($emailMachineId, $sequenceId, $id, EmailFixedShippingDateRequest $request)
    {

        // Capturar possíveis exceções durante a execução.
        try {
            // Buscar o e-mail
            $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->findOrFail($id);

            // Editar as informações do registro no banco de dados
            $email->update([
                'use_fixed_send_datetime' => $request->use_fixed_send_datetime ?? 0,
                'fixed_send_datetime' => $request->fixed_send_datetime ?? null,
            ]);

            // Salvar log
            Log::info('Data fixa de envio do e-mail editada.', [
                'id' => $email->id,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()
                ->route('email-sequence-emails.edit-dates', [
                    'emailMachine' => $emailMachineId,
                    'sequence' => $sequenceId,
                    'email' => $id
                ])
                ->with('success', 'Data fixa de envio do e-mail editada com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('Data fixa de envio do e-mail não editada.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Data fixa de envio do e-mail não editada!');
        }
    }

    // Editar no banco de dados as datas do e-mail
    public function updateSubmissionWindow($emailMachineId, $sequenceId, $id, EmailSubmissionWindowRequest $request)
    {

        // Capturar possíveis exceções durante a execução.
        try {
            // Buscar o e-mail
            $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->findOrFail($id);

            // Editar as informações do registro no banco de dados
            $email->update([
                'use_send_window' => $request->use_send_window ?? 0,
                'send_window_start_hour' => $request->send_window_start_hour ?? null,
                'send_window_start_minute' => $request->send_window_start_minute ?? null,
                'send_window_end_hour' => $request->send_window_end_hour ?? null,
                'send_window_end_minute' => $request->send_window_end_minute ?? null,
            ]);

            // Salvar log
            Log::info('Janela de envio de envio do e-mail editada.', [
                'id' => $email->id,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()
                ->route('email-sequence-emails.edit-dates', [
                    'emailMachine' => $emailMachineId,
                    'sequence' => $sequenceId,
                    'email' => $id
                ])
                ->with('success', 'Janela de envio do e-mail editada com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('Janela de envio do e-mail não editada.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Janela de envio do e-mail não editada!');
        }
    }

    // Carregar o formulário editar configuração do e-mail
    public function editConfig($emailMachineId, $sequenceId, $id)
    {
        // Buscar a máquina, sequência e o e-mail
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($sequenceId);
        $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
            ->findOrFail($id);

        // Carregar a view 
        return view('email-sequence-emails.edit_config', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence,
            'email' => $email
        ]);
    }

    // Editar no banco de dados a configuração do e-mail
    public function updateConfig($emailMachineId, $sequenceId, $id, EmailSequenceEmailConfigRequest $request)
    {

        // Capturar possíveis exceções durante a execução.
        try {
            // Buscar o e-mail
            $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->findOrFail($id);

            // Editar as informações do registro no banco de dados
            $email->update([
                'is_active' => $request->is_active,
                'skip_email' => $request->skip_email,
            ]);

            // Salvar log
            Log::info('Configuração do e-mail editada.', [
                'id' => $email->id,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()
                ->route('email-sequence-emails.edit-config', [
                    'emailMachine' => $emailMachineId,
                    'sequence' => $sequenceId,
                    'email' => $id
                ])
                ->with('success', 'Configuração editada com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('Configuração do e-mail não editada.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Configuração não editada!');
        }
    }

    // Apagar o e-mail da sequência do banco de dados
    public function destroy($emailMachineId, $sequenceId, $id)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Buscar o e-mail
            $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
                ->findOrFail($id);

            // Apagar o registro do banco de dados
            $email->delete();

            // Salvar log
            Log::info('E-mail da sequência apagado.', [
                'id' => $id,
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return redirect()
                ->route('email-machine-sequences.index', ['emailMachine' => $emailMachineId])
                ->with('success', 'E-mail apagado com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('E-mail da sequência não apagado.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->with('error', 'E-mail não apagado!');
        }
    }

    // Visualizar o conteúdo do e-mail
    public function show($emailMachineId, $sequenceId, $id)
    {
        // Buscar a máquina, sequência e o e-mail
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($sequenceId);
        $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
            ->findOrFail($id);

        // Carregar a view 
        return view('email-sequence-emails.show', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence,
            'email' => $email
        ]);
    }

    // Visualizar as datas do e-mail
    public function showDates($emailMachineId, $sequenceId, $id)
    {
        // Buscar a máquina, sequência e o e-mail
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($sequenceId);
        $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
            ->findOrFail($id);

        // Carregar a view 
        return view('email-sequence-emails.show_dates', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence,
            'email' => $email
        ]);
    }

    // Visualizar a configuração do e-mail
    public function showConfig($emailMachineId, $sequenceId, $id)
    {
        // Buscar a máquina, sequência e o e-mail
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($sequenceId);
        $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
            ->findOrFail($id);

        // Carregar a view 
        return view('email-sequence-emails.show_config', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence,
            'email' => $email
        ]);
    }

    // Visualizar os usuários vinculados ao e-mail
    public function showUsers($emailMachineId, $sequenceId, $id)
    {
        // Buscar a máquina, sequência e o e-mail
        $emailMachine = EmailMachine::findOrFail($emailMachineId);
        $sequence = EmailMachineSequence::where('email_machine_id', $emailMachineId)
            ->findOrFail($sequenceId);
        $email = EmailSequenceEmail::where('email_machine_sequence_id', $sequenceId)
            ->with('emailUser.user') // Carregar relacionamento com usuários
            ->findOrFail($id);

        // Carregar a view 
        return view('email-sequence-emails.show_users', [
            'menu' => 'email-machine',
            'emailMachine' => $emailMachine,
            'sequence' => $sequence,
            'email' => $email
        ]);
    }
}
