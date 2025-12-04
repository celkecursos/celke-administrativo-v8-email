<?php

namespace App\Http\Controllers;

use App\Models\EmailUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailUserController extends Controller
{
    // Alternar o status is_active (ativar/desativar)
    public function toggleStatus($id)
    {
        try {
            $emailUser = EmailUser::findOrFail($id);
            
            // Inverter o valor de is_active
            $emailUser->is_active = !$emailUser->is_active;
            $emailUser->save();

            // Salvar log
            Log::info('Status do e-mail do usuário alterado.', [
                'id' => $emailUser->id,
                'is_active' => $emailUser->is_active,
                'action_user_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Status alterado com sucesso!');
        } catch (Exception $e) {
            // Salvar log
            Log::notice('Status do e-mail do usuário não foi alterado.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Erro ao alterar status!');
        }
    }

    // Excluir o registro de email_users
    public function destroy($id)
    {
        try {
            $emailUser = EmailUser::findOrFail($id);
            $emailUser->delete();

            // Salvar log
            Log::info('E-mail do usuário apagado.', [
                'id' => $id,
                'action_user_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'E-mail apagado com sucesso!');
        } catch (Exception $e) {
            // Salvar log
            Log::notice('E-mail do usuário não foi apagado.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Erro ao apagar e-mail!');
        }
    }
}
