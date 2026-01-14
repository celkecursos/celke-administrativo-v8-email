<?php

namespace App\Http\Controllers\EmailSequenceEmail;

use App\Http\Controllers\Controller;
use App\Models\EmailSequenceEmail;
use App\Models\EmailTag;
use App\Models\EmailUser;
use App\Models\User;
use App\Models\UserStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MoveEmailContentController extends Controller
{
    // Listar os usuários
    public function index(Request $request)
    {
        // Recuperar os registros do banco dados
        $users = User::query()

            ->when(
                $request->filled('user_status_id'),
                fn($query) =>
                $query->where('user_status_id', $request->user_status_id)
            )

            ->when(
                $request->filled('name'),
                fn($query) =>
                $query->whereLike('name', '%' . $request->name . '%')
            )

            ->when(
                $request->filled('email'),
                fn($query) =>
                $query->whereLike('email', '%' . $request->email . '%')
            )

            ->when(
                $request->filled('start_date'),
                fn($query) =>
                $query->whereDate('created_at', '>=', $request->start_date)
            )

            ->when(
                $request->filled('end_date'),
                fn($query) =>
                $query->whereDate('created_at', '<=', $request->end_date)
            )

            // E-mail de Origem
            ->when(
                $request->filled('email_origin_id'),
                fn($query) =>
                $query->whereHas('emailUser', function ($q) use ($request) {
                    $q->where('email_sequence_email_id', $request->email_origin_id);
                })
            )

            // E-mail COM a Tag
            ->when(
                $request->filled('email_tag_include'),
                fn($query) =>
                $query->whereHas('emailTags', function ($q) use ($request) {
                    $q->whereIn('email_tag_id', $request->email_tag_include);
                })
            )
            // E-mail SEM a Tag
            ->when(
                $request->filled('email_tag_exclude'),
                fn($query) =>
                $query->whereDoesntHave('emailTags', function ($q) use ($request) {
                    $q->whereIn('email_tag_id', $request->email_tag_exclude);
                })
            )

            ->orderBy('id', 'DESC')
            ->paginate(10)
            ->withQueryString();

        // Recuperar as tags
        $emailTags = EmailTag::where('is_active', true)->orderBy('name', 'ASC')->get();

        // Recuperar as tags
        $userStatuses = UserStatus::orderBy('name', 'ASC')->get();

        // Recuperar os conteúdos de e-mail
        $emailSequenceEmails = EmailSequenceEmail::orderBy('title', 'ASC')->get();

        // Salvar log
        Log::info('Listar os usuários.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('email-sequence-emails.move-email', [
            'menu' => 'move-email-content',
            'name' => $request->name,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'email_tag_include' => $request->email_tag_include,
            'email_tag_exclude' => $request->email_tag_exclude,
            'user_status_id' => $request->user_status_id,
            'email_origin_id' => $request->email_origin_id,
            'email_destination_id' => $request->email_destination_id,
            'users' => $users ?? collect(),
            'emailTags' => $emailTags,
            'userStatuses' => $userStatuses,
            'emailSequenceEmails' => $emailSequenceEmails,
        ]);
    }

    // Mover o e-mail do conteúdo para o conteúdo selecionado
    public function update(Request $request)
    {

        $request->validate(
            [
                'email_origin_id'      => 'required|exists:email_sequence_emails,id',
                'email_destination_id' => 'required|exists:email_sequence_emails,id',
            ],
            [
                'email_origin_id.required' => 'O e-mail de origem é obrigatório.',
                'email_origin_id.exists'   => 'O e-mail de origem selecionado não é válido.',

                'email_destination_id.required' => 'O e-mail de destino é obrigatório.',
                'email_destination_id.exists'   => 'O e-mail de destino selecionado não é válido.',
            ]
        );
        try {

            // 🔹 Recuperar IDs dos usuários filtrados
            $userIds = User::query()

                ->when(
                    $request->filled('user_status_id'),
                    fn($query) =>
                    $query->where('user_status_id', $request->user_status_id)
                )

                ->when(
                    $request->filled('name'),
                    fn($query) =>
                    $query->whereLike('name', '%' . $request->name . '%')
                )

                ->when(
                    $request->filled('email'),
                    fn($query) =>
                    $query->whereLike('email', '%' . $request->email . '%')
                )

                ->when(
                    $request->filled('start_date'),
                    fn($query) =>
                    $query->whereDate('created_at', '>=', $request->start_date)
                )

                ->when(
                    $request->filled('end_date'),
                    fn($query) =>
                    $query->whereDate('created_at', '<=', $request->end_date)
                )

                // E-mail de Origem
                ->when(
                    $request->filled('email_origin_id'),
                    fn($query) =>
                    $query->whereHas('emailUser', function ($q) use ($request) {
                        $q->where('email_sequence_email_id', $request->email_origin_id);
                    })
                )

                // E-mail COM a Tag
                ->when(
                    $request->filled('email_tag_include'),
                    fn($query) =>
                    $query->whereHas('emailTags', function ($q) use ($request) {
                        $q->whereIn('email_tag_id', $request->email_tag_include);
                    })
                )

                // E-mail SEM a Tag
                ->when(
                    $request->filled('email_tag_exclude'),
                    fn($query) =>
                    $query->whereDoesntHave('emailTags', function ($q) use ($request) {
                        $q->whereIn('email_tag_id', $request->email_tag_exclude);
                    })
                )

                ->pluck('id');

            // Atualizar email_users
            $updated = EmailUser::where('email_sequence_email_id', $request->email_origin_id)
                ->whereIn('user_id', $userIds)
                ->update([
                    'email_sequence_email_id' => $request->email_destination_id,
                    'updated_at' => now(),
                ]);

            Log::info('E-mails programados transferidos com sucesso.', [
                'total_updated' => $updated,
                'action_user_id' => Auth::id(),
            ]);

            return redirect()
                ->back()
                ->with('success', "Foram transferidos {$updated} e-mails programados.");
        } catch (\Throwable $e) {

            Log::error('Erro ao transferir e-mails programados.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erro ao transferir os e-mails programados.');
        }
    }
}
