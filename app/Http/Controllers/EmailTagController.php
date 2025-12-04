<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailTagRequest;
use App\Models\EmailTag;
use Illuminate\Http\Request;

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

        // Retornar a view com os dados
        return view('email-tags.index', [
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
        return view('email-tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailTagRequest $request)
    {
        // Validar os dados do formulário através do EmailTagRequest
        $validated = $request->validated();

        // Definir o status padrão como ativo se não for fornecido
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Cadastrar no banco de dados
        EmailTag::create($validated);

        // Redirecionar o usuário e enviar a mensagem de sucesso
        return redirect()->route('email-tags.index')->with('success', 'Tag cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailTag $emailTag)
    {
        // Retornar a view com os dados
        return view('email-tags.show', [
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
            'emailTag' => $emailTag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailTagRequest $request, EmailTag $emailTag)
    {
        // Validar os dados do formulário através do EmailTagRequest
        $validated = $request->validated();

        // Atualizar o status
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Editar as informações no banco de dados
        $emailTag->update($validated);

        // Redirecionar o usuário e enviar a mensagem de sucesso
        return redirect()->route('email-tags.index')->with('success', 'Tag editada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTag $emailTag)
    {
        // Apagar o registro do banco de dados
        $emailTag->delete();

        // Redirecionar o usuário e enviar a mensagem de sucesso
        return redirect()->route('email-tags.index')->with('success', 'Tag apagada com sucesso!');
    }
}
