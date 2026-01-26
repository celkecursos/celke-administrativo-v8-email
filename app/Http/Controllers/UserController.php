<?php

namespace App\Http\Controllers;

use App\Models\EmailTag;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\EmailUserSentEmail;
use App\Models\EmailFailedSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Listar os usuários
    public function index(Request $request)
    {
        // Recuperar os registros do banco dados
        $users = User::when(
            $request->filled('user_status_id'),
            fn($query) => $query->where('user_status_id', $request->user_status_id)
        )
            ->when(
                $request->filled('name'),
                fn($query) => $query->whereLike('name', '%' . $request->name . '%')
            )
            ->when(
                $request->filled('email'),
                fn($query) => $query->whereLike('email', '%' . $request->email . '%')
            )
            ->when(
                $request->filled('start_date'),
                fn($query) => $query->whereDate('created_at', '>=', $request->start_date)
            )
            ->when(
                $request->filled('end_date'),
                fn($query) => $query->whereDate('created_at', '<=', $request->end_date)
            )
            ->when(
                $request->filled('tagged_or_untagged'),
                function ($query) use ($request) {

                    // COM TAG
                    if ($request->tagged_or_untagged === '1') {
                        if ($request->filled('email_tag_id')) {
                            // Usuários que possuem a tag selecionada
                            $query->whereHas('emailTags', function ($q) use ($request) {
                                $q->whereIn('email_tag_id', $request->email_tag_id);
                            });
                        } else {
                            // Qualquer tag
                            $query->whereHas('emailTags');
                        }
                    }

                    // SEM TAG
                    if ($request->tagged_or_untagged === '0') {
                        if ($request->filled('email_tag_id')) {
                            // Usuários que **não possuem nenhuma das tags selecionadas**
                            $query->whereDoesntHave('emailTags', function ($q) use ($request) {
                                $q->whereIn('email_tag_id', $request->email_tag_id);
                            });
                        } else {
                            // Usuários sem **nenhuma tag**
                            $query->whereDoesntHave('emailTags');
                        }
                    }
                }
            )
            ->orderBy('id', 'DESC')
            ->paginate(10)
            ->withQueryString();

        // Recuperar as tags
        $emailTags = EmailTag::where('is_active', true)->orderBy('name', 'ASC')->get();

        // Recuperar as tags
        $userStatuses = UserStatus::orderBy('name', 'ASC')->get();

        // Salvar log
        Log::info('Listar os usuários.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('users.index', [
            'menu' => 'users',
            'name' => $request->name,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'tagged_or_untagged' => $request->tagged_or_untagged,
            'email_tag_id' => $request->email_tag_id,
            'user_status_id' => $request->user_status_id,
            'users' => $users,
            'emailTags' => $emailTags,
            'userStatuses' => $userStatuses,
        ]);
    }

    // Visualizar os detalhes do usuário
    public function show(User $user)
    {
        // Carregar os e-mails programados do usuário com o relacionamento
        $emailUsers = $user->emailUser()
            ->with([
                'emailSequenceEmail',
                'user.userStatus'
            ])
            ->orderBy('id', 'DESC')
            ->get();

        // Carregar as tags do usuário
        $user->load('emailTags');

        // Log para debug
        Log::info('Visualizar o usuário.', [
            'id' => $user->id,
            'action_user_id' => Auth::id()
        ]);

        // Carregar a view 
        return view('users.show', [
            'menu' => 'users',
            'user' => $user,
            'emailUsers' => $emailUsers
        ]);
    }

    // Página: Dados do Usuário
    public function details(User $user)
    {
        // Carregar as tags do usuário
        $user->load('emailTags');

        // Log
        Log::info('Visualizar detalhes do usuário.', [
            'id' => $user->id,
            'action_user_id' => Auth::id()
        ]);

        return view('users.details', [
            'menu' => 'users',
            'user' => $user,
        ]);
    }

    // Página: E-mails Programados
    public function scheduled(User $user)
    {
        // Carregar os e-mails programados do usuário
        $emailUsers = $user->emailUser()->with('emailSequenceEmail')->orderBy('id', 'DESC')->get();

        // Log
        Log::info('Visualizar e-mails programados do usuário.', [
            'id' => $user->id,
            'action_user_id' => Auth::id()
        ]);

        return view('users.scheduled', [
            'menu' => 'users',
            'user' => $user,
            'emailUsers' => $emailUsers,
        ]);
    }

    // Página: E-mails Enviados
    public function sent(User $user)
    {
        // Consultar os e-mails enviados para o usuário
        $sentEmails = EmailUserSentEmail::where('user_id', $user->id)
            ->with(['emailSequenceEmail', 'emailContentSnapshot'])
            ->orderBy('id', 'DESC')
            ->get();

        // Log
        Log::info('Visualizar e-mails enviados do usuário.', [
            'id' => $user->id,
            'action_user_id' => Auth::id()
        ]);

        return view('users.sent', [
            'menu' => 'users',
            'user' => $user,
            'sentEmails' => $sentEmails,
        ]);
    }

    // Página: E-mails Não Enviados
    public function failed(User $user)
    {
        // Consultar os e-mails não enviados para o usuário
        $failedEmails = EmailFailedSend::where('user_id', $user->id)
            ->with(['emailSequenceEmail'])
            ->orderBy('id', 'DESC')
            ->get();

        // Log
        Log::info('Visualizar e-mails não enviados do usuário.', [
            'id' => $user->id,
            'action_user_id' => Auth::id()
        ]);

        return view('users.failed', [
            'menu' => 'users',
            'user' => $user,
            'failedEmails' => $failedEmails,
        ]);
    }

    // Página: Status Atual
    public function status(User $user)
    {
        // Carregar o status do usuário
        $user->load('userStatus');

        // Log
        Log::info('Visualizar status do usuário.', [
            'id' => $user->id,
            'action_user_id' => Auth::id()
        ]);

        return view('users.status', [
            'menu' => 'users',
            'user' => $user,
        ]);
    }

    // Página: Descadastros (sem dados, tabela vazia)
    public function unsubscribed(User $user)
    {
        // Sem dados (tabela não existe no projeto)
        $unsubscribed = [];

        // Log
        Log::info('Visualizar descadastros do usuário.', [
            'id' => $user->id,
            'action_user_id' => Auth::id()
        ]);

        return view('users.unsubscribed', [
            'menu' => 'users',
            'user' => $user,
            'unsubscribed' => $unsubscribed,
        ]);
    }

    // Atualizar o status do usuário
    public function updateStatus(Request $request, User $user)
    {
        // Validar o input
        $request->validate([
            'user_status_id' => 'required|exists:user_statuses,id',
        ]);

        // Atualizar o status do usuário
        $user->update(['user_status_id' => $request->user_status_id]);

        // Recarregar o usuário do banco para refletir as mudanças
        $user->refresh();

        // Se o status for 4 (Spam) ou 5 (Descadastrado), excluir todos os registros de email_users para este usuário
        if (in_array($request->user_status_id, [4, 5])) {
            $user->emailUser()->delete();  // Usa o relacionamento para deletar
        }

        // Log da ação
        Log::info('Status do usuário atualizado.', [
            'user_id' => $user->id,
            'new_status_id' => $request->user_status_id,
            'action_user_id' => Auth::id(),
            'email_users_deleted' => in_array($request->user_status_id, [4, 5]) ? 'Sim' : 'Não',
        ]);

        // Redirecionar de volta com mensagem de sucesso
        return redirect()->route('users.status', $user->id)->with('success', 'Status do usuário atualizado com sucesso!');
    }
}
