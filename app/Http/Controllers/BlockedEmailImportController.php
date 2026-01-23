<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\BlockedEmailImportJob;
use App\Models\EmailSequenceEmail;
use App\Models\UserStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BlockedEmailImportController extends Controller
{

    // Carregar o formulário cadastrar novo usuário
    public function create(Request $request)
    {

        // Recuperar os email_sequence_emails
        $emailSequenceEmails = EmailSequenceEmail::orderBy('title', 'ASC')->get();

        // Recuperar os email_sequence_emails
        $userStatuses = UserStatus::orderBy('name', 'ASC')->get();

        // Carregar a VIEW
        return view('users.import.blocked-emails-imports', [
            'menu' => 'import-csv-lead-lovers',
            'emailSequenceEmails' => $emailSequenceEmails,
            'userStatuses' => $userStatuses,
        ]);
    }

    // Importar os dados do Excel
    public function store(Request $request)
    {
        // Validar o arquivo
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:8192', // 8 MB
            'email_sequence_email_id'     => 'required|exists:email_sequence_emails,id',
            'user_status_id'              => 'required|exists:user_statuses,id',
            'is_active' => 'required|in:0,1',
        ], [
            'file.required' => 'O campo arquivo é obrigatório.',
            'file.mimes' => 'Arquivo inválido, necessário enviar arquivo CSV.',
            'file.max' => 'Tamanho do arquivo execede :max Mb.',

            'email_sequence_email_id.required'    => 'O conteúdo de e-mail é obrigatório.',
            'email_sequence_email_id.exists'      => 'O conteúdo de e-mail selecionado não é válido.',

            'user_status_id.required'             => 'O status do usuário é obrigatório.',
            'user_status_id.exists'               => 'O status do usuário selecionado não é válido.',

            'is_active.required' => 'O campo status é obrigatório!',
            'is_active.in'       => 'O campo status deve ser Ativo ou Inativo!',
        ]);

        // dd($request->file('file'));

        try {

            // Gerar um nome de arquivo baseado na data e hora atual
            $fileName = 'import-' . now()->format('Y-m-d-H-i-s') . '.csv';

            // Receber o arquivo e movê-lo para o servidor
            $path = $request->file('file')->storeAs(
                'uploads',
                $fileName,
                'local' // disk explícito
            );

            // Despachar o Job para processar o CSV
            BlockedEmailImportJob::dispatch($path, $request->email_sequence_email_id, $request->user_status_id, $request->is_active);

            // Salvar log
            Log::info('Agendado importar CSV com e-mails spam, inválidos e rejeitados no servidor de envio.', ['action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de sucesso
            return back()->withInput()->with('success', 'Dados estão sendo importados!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Não agendado importar CSV com e-mails spam, inválidos e rejeitados no servidor de envio.', ['error' => $e->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Não importar CSV com e-mails spam, inválidos e rejeitados no servidor de envio!');
        }
    }
}
