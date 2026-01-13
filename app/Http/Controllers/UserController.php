<?php

namespace App\Http\Controllers;

use App\Models\EmailTag;
use App\Models\User;
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
            'users' => $users,
            'emailTags' => $emailTags,
        ]);
    }

    // Visualizar os detalhes do usuário
    public function show(User $user)
    {
        // Carregar os e-mails programados do usuário com o relacionamento
        $emailUsers = $user->emailUser()->with('emailSequenceEmail')->orderBy('id', 'DESC')->get();

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
}
