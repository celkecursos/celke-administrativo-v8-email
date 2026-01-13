<?php

namespace App\Http\Controllers\GenerateCSV;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateCSVUsersJob;
use Illuminate\Http\Request;

class GenerateCSVUserController extends Controller
{
    // Gerar CSV dos usuários pesquisados
    public function generateCSVUsers(Request $request)
    {

        // Recuperar os valores do formulário
        $name = $request->name ?? '';
        $email = $request->email ?? '';
        $start_date = $request->start_date ?? '';
        $end_date = $request->end_date ?? '';
        $tagged_or_untagged = $request->tagged_or_untagged ?? '';
        $user_status_id = $request->user_status_id ?? '';
        $email_tag_id = $request->email_tag_id ?? [];

        // Nome do arquivo CSV
        $filePath = "";

        // Disparar o Job para processar os usuários em blocos
        GenerateCSVUsersJob::dispatch(
            $filePath,
            $name,
            $email,
            $start_date,
            $end_date,
            $tagged_or_untagged,
            $user_status_id,
            $email_tag_id,
            0 // offset inicial
        );

        // Retornar mensagem de sucesso para o usuário
        return redirect()->back()->with('success', 'O processamento do CSV foi iniciado.');
    }
}
