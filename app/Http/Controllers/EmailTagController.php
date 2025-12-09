<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailTagRequest;
use App\Models\EmailTag;
use App\Services\SlugService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmailTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Recuperar os valores enviados pelo formulário
        $name = $request->input('name');

        // Query base
        $emailTags = EmailTag::query();

        // Aplicar filtros se existirem
        if (!empty($name)) {
            $emailTags->where('name', 'LIKE', "%{$name}%");
        }

        // Buscar os dados do banco de dados, com paginação
        $emailTags = $emailTags->orderBy('id', 'DESC')->paginate(10);

        // Salvar log
        Log::info('Listar as tag.', ['action_user_id' => Auth::id()]);

        // Retornar a view com os dados
        return view('email-tags.index', [
            'menu' => 'email-tags',
            'emailTags' => $emailTags,
            'name' => $name,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retornar a view
        return view('email-tags.create', [
            'menu' => 'email-tags',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailTagRequest $request)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Gerar o slug utilizando o SlugService
            $slug = app(SlugService::class)->generate($request->name);

            // Cadastrar no banco de dados
            $emailTag = EmailTag::create([
                'name' => $slug,
                'is_active' => $request->is_active,
            ]);

            // Salvar log
            Log::info('Tag cadastrada.', ['id' => $emailTag->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário e enviar a mensagem de sucesso
            return redirect()->route('email-tags.index')->with('success', 'Tag cadastrada com sucesso!');
        } catch (Exception $e) {

            // Salvar log detalhado do erro
            Log::notice('Tag não cadastrada.', [
                'error' => $e->getMessage(),
                'action_user_id' => Auth::id()
            ]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Tag não cadastrada!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailTag $emailTag)
    {

        // Log para debug
        Log::info('Visualizar a tag.', [
            'id' => $emailTag->id,
            'action_user_id' => Auth::id()
        ]);

        // Retornar a view com os dados
        return view('email-tags.show', [
            'menu' => 'email-tags',
            'emailTag' => $emailTag,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTag $emailTag)
    {
        // Retornar a view com os dados
        return view('email-tags.edit', [
            'menu' => 'email-tags',
            'emailTag' => $emailTag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailTagRequest $request, EmailTag $emailTag)
    {
        // Capturar possíveis exceções durante a execução.
        try {

            // Gerar o slug utilizando o SlugService
            $slug = app(SlugService::class)->generate($request->name);

            // Editar as informações no banco de dados
            $emailTag->update([
                'name' => $slug,
                'is_active' => $request->is_active,
            ]);

            // Salvar log
            Log::info('tag editada.', ['id' => $emailTag->id, 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário e enviar a mensagem de sucesso
            return redirect()->route('email-tags.index')->with('success', 'Tag editada com sucesso!');
        } catch (Exception $e) {

            // Salvar log
            Log::notice('Tag não editada.', ['error' => $e->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Tag não editada!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTag $emailTag)
    {
        // Capturar possíveis exceções durante a execução.
        try {
            // Apagar o registro do banco de dados
            $emailTag->delete();

            // Salvar log
            Log::info('Tag apagada.', ['id' => $emailTag->id, 'action_user_id' => Auth::id()]);


            // Redirecionar o usuário e enviar a mensagem de sucesso

        } catch (Exception $e) {

            // Salvar log
            Log::notice('Tag não apagada.', ['error' => $e->getMessage(), 'action_user_id' => Auth::id()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'Tag não apagado!');
        }
    }
}
