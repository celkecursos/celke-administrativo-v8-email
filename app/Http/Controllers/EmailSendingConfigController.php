<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailSendingConfigRequest;
use App\Http\Requests\EmailSendingConfigPasswordRequest;
use App\Http\Requests\EmailSendingConfigSenderRequest;
use App\Http\Requests\EmailSendingConfigSettingsRequest;
use App\Models\EmailSendingConfig;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailSendingConfigController extends Controller
{
    /**
     * Lista os servidores de e-mail
     */
    public function index(Request $request)
    {
        // Recuperar filtros do formulário
        $provider = $request->input('provider');

        // Query base
        $emailSendingConfigs = EmailSendingConfig::query();

        // Aplicar filtro por provider (se existir)
        if (!empty($provider)) {
            $emailSendingConfigs->where('provider', 'LIKE', "%{$provider}%");
        }

        // Buscar dados com paginação
        $emailSendingConfigs = $emailSendingConfigs
            ->orderBy('id', 'DESC')
            ->paginate(10);

        // Salvar log
        Log::info('Listar servidores de e-mail.', [
            'action_user_id' => Auth::id(),
        ]);

        // Retornar view
        return view('email-sending-configs.index', [
            'menu' => 'email-sending-configs',
            'emailSendingConfigs' => $emailSendingConfigs,
            'provider' => $provider,
        ]);
    }

    /**
     * Exibe o formulário de cadastro
     */
    public function create()
    {
        return view('email-sending-configs.create', [
            'menu' => 'email-sending-configs',
        ]);
    }

    /**
     * Salva um novo servidor de e-mail
     * Utiliza o request principal (cadastro completo)
     */
    public function store(EmailSendingConfigRequest $request)
    {
        try {
            // Criar registro no banco
            $emailSendingConfig = EmailSendingConfig::create([
                'provider' => $request->provider,
                'host' => $request->host,
                'username' => $request->username,
                'password' => $request->password ?? null,
                'from_name' => $request->from_name,
                'from_email' => $request->from_email,
                'send_quantity_per_request' => $request->send_quantity_per_request,
                'send_quantity_per_hour' => $request->send_quantity_per_hour,
                'is_active_marketing' => $request->is_active_marketing,
                'is_active_transactional' => $request->is_active_transactional,
            ]);

            // Log de sucesso
            Log::info('Servidor de e-mail cadastrado.', [
                'id' => $emailSendingConfig->id,
                'action_user_id' => Auth::id(),
            ]);

            return redirect()
                ->route('email-sending-configs.index')
                ->with('success', 'Servidor de e-mail cadastrado com sucesso!');
        } catch (Exception $e) {
            // Log de erro
            Log::notice('Servidor de e-mail não cadastrado.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Servidor de e-mail não cadastrado!');
        }
    }

    /**
     * Visualiza os dados do servidor de e-mail
     */
    public function show(EmailSendingConfig $emailSendingConfig)
    {
        Log::info('Visualizar servidor de e-mail.', [
            'id' => $emailSendingConfig->id,
            'action_user_id' => Auth::id(),
        ]);

        return view('email-sending-configs.show', [
            'menu' => 'email-sending-configs',
            'emailSendingConfig' => $emailSendingConfig,
        ]);
    }

    /**
     * Editar - Credenciais
     * Campos: provider, host, username
     */
    public function edit(EmailSendingConfig $emailSendingConfig)
    {
        return view('email-sending-configs.edit', [
            'menu' => 'email-sending-configs',
            'emailSendingConfig' => $emailSendingConfig,
        ]);
    }

    /**
     * Editar - Senha
     * Campo: password
     */
    public function editPassword(EmailSendingConfig $emailSendingConfig)
    {
        Log::info('Editar senha do servidor de e-mail.', [
            'id' => $emailSendingConfig->id,
            'action_user_id' => Auth::id(),
        ]);

        return view('email-sending-configs.edit-password', [
            'menu' => 'email-sending-configs',
            'emailSendingConfig' => $emailSendingConfig,
        ]);
    }

    /**
     * Editar - Remetente
     * Campos: from_name, from_email
     */
    public function editSender(EmailSendingConfig $emailSendingConfig)
    {
        Log::info('Editar remetente do servidor de e-mail.', [
            'id' => $emailSendingConfig->id,
            'action_user_id' => Auth::id(),
        ]);

        return view('email-sending-configs.edit-sender', [
            'menu' => 'email-sending-configs',
            'emailSendingConfig' => $emailSendingConfig,
        ]);
    }

    /**
     * Editar - Configurações
     * Campos: send_quantity_per_request, send_quantity_per_hour, is_active_marketing, is_active_transactional
     */
    public function editSettings(EmailSendingConfig $emailSendingConfig)
    {
        Log::info('Editar configurações do servidor de e-mail.', [
            'id' => $emailSendingConfig->id,
            'action_user_id' => Auth::id(),
        ]);

        return view('email-sending-configs.edit-settings', [
            'menu' => 'email-sending-configs',
            'emailSendingConfig' => $emailSendingConfig,
        ]);
    }

    /**
     * Atualiza as credenciais
     * Usa apenas EmailSendingConfigRequest (credenciais)
     */
    public function update(
        EmailSendingConfigRequest $request,
        EmailSendingConfig $emailSendingConfig
    ) {
        try {
            // Atualizar apenas campos de credenciais
            $emailSendingConfig->update([
                'provider' => $request->provider,
                'host' => $request->host,
                'username' => $request->username,
            ]);

            Log::info('Credenciais do servidor de e-mail editadas.', [
                'id' => $emailSendingConfig->id,
                'action_user_id' => Auth::id(),
            ]);

            return redirect()
                ->route('email-sending-configs.index')
                ->with('success', 'Credenciais editadas com sucesso!');
        } catch (Exception $e) {
            Log::notice('Credenciais do servidor de e-mail não editadas.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Credenciais não editadas!');
        }
    }

    /**
     * Atualiza a senha
     * Usa EmailSendingConfigPasswordRequest
     */
    public function updatePassword(
        EmailSendingConfigPasswordRequest $request,
        EmailSendingConfig $emailSendingConfig
    ) {
        try {
            $emailSendingConfig->update([
                'password' => $request->password,
            ]);

            Log::info('Senha do servidor de e-mail atualizada.', [
                'id' => $emailSendingConfig->id,
                'action_user_id' => Auth::id(),
            ]);

            return back()->with('success', 'Senha atualizada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar senha.', [
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Erro ao atualizar a senha.');
        }
    }

    /**
     * Atualiza o remetente
     * Usa EmailSendingConfigSenderRequest
     */
    public function updateSender(
        EmailSendingConfigSenderRequest $request,
        EmailSendingConfig $emailSendingConfig
    ) {
        try {
            $emailSendingConfig->update([
                'from_name' => $request->from_name,
                'from_email' => $request->from_email,
            ]);

            Log::info('Remetente atualizado.', [
                'id' => $emailSendingConfig->id,
                'action_user_id' => Auth::id(),
            ]);

            return back()->with('success', 'Remetente atualizado com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar remetente.', [
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Erro ao atualizar remetente.');
        }
    }

    /**
     * Atualiza as configurações
     * Usa EmailSendingConfigSettingsRequest
     */
    public function updateSettings(
        EmailSendingConfigSettingsRequest $request,
        EmailSendingConfig $emailSendingConfig
    ) {
        try {
            $emailSendingConfig->update([
                'send_quantity_per_request' => $request->send_quantity_per_request,
                'send_quantity_per_hour' => $request->send_quantity_per_hour,
                'is_active_marketing' => $request->is_active_marketing,
                'is_active_transactional' => $request->is_active_transactional,
            ]);

            Log::info('Configurações do servidor de e-mail atualizadas.', [
                'id' => $emailSendingConfig->id,
                'action_user_id' => Auth::id(),
            ]);

            return back()->with('success', 'Configurações atualizadas com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar configurações.', [
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Erro ao atualizar configurações.');
        }
    }

    /**
     * Remove o servidor de e-mail
     */
    public function destroy(EmailSendingConfig $emailSendingConfig)
    {
        try {
            $emailSendingConfig->delete();

            Log::info('Servidor de e-mail apagado.', [
                'id' => $emailSendingConfig->id,
                'action_user_id' => Auth::id(),
            ]);

            return redirect()
                ->route('email-sending-configs.index')
                ->with('success', 'Servidor de e-mail apagado com sucesso!');
        } catch (Exception $e) {
            Log::notice('Servidor de e-mail não apagado.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id(),
            ]);

            return back()
                ->with('error', 'Servidor de e-mail não apagado!');
        }
    }
}