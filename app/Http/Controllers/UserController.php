<?php

namespace App\Http\Controllers;

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
            fn($query) => $query->whereLike('name', '%' . $request->name .  '%')
        )
            ->when(
                $request->filled('email'),
                fn($query) => $query->whereLike('email', '%' . $request->email .  '%')
            )
            ->when(
                $request->filled('start_date'),
                fn($query) => $query->whereDate('created_at', '>=', $request->start_date)
            )
            ->when(
                $request->filled('end_date'),
                fn($query) => $query->whereDate('created_at', '<=', $request->end_date)
            )
            ->orderBy('id', 'DESC')
            ->paginate(10)
            ->withQueryString();

        // Salvar log
        Log::info('Listar os usuários.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('users.index', [
            'menu' => 'users',
            'name' => $request->name,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'users' => $users
        ]);
    }

    // Visualizar os detalhes do usuário
    public function show(User $user)
    {
        // Log para debug
        Log::info('Visualizar o usuário.', [
            'id' => $user->id,
            'action_user_id' => Auth::id()
        ]);

        // Carregar a view 
        return view('users.show', [
            'menu' => 'users',
            'user' => $user
        ]);
    }
}
