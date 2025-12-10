<?php

namespace App\Http\Controllers;

use App\Models\EmailAutomationTrigger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class EmailAutomationTriggerController extends Controller
{
    public function show(EmailAutomationTrigger $emailAutomationTrigger)
    {
        $emailAutomationTrigger->load(['action', 'filterType', 'actionType']);

        Log::info('Visualizar gatilho de automação.', [
            'id' => $emailAutomationTrigger->id,
            'action_user_id' => Auth::id()
        ]);
        return view('email-automation-triggers.show', [
            'emailAutomationTrigger' => $emailAutomationTrigger,
            'menu'                   => 'email-automation-actions',
        ]);
    }

    public function destroy(EmailAutomationTrigger $emailAutomationTrigger)
    {
        try {
            $emailAutomationTrigger->delete();
            Log::info('Gatilho apagado.', ['id' => $emailAutomationTrigger->id, 'action_user_id' => Auth::id()]);
            return redirect()->back()->with('success', 'Gatilho apagado com sucesso!');
        } catch (\Exception $e) {
            Log::notice('Erro ao apagar gatilho.', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao apagar gatilho.');
        }
    }
}
