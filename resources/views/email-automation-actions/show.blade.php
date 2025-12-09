@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Visualizar Ação Automatizada" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Ações Automatizadas', 'url' => route('email-automation-actions.index')],
        ['label' => 'Visualizar Ação Automatizada'],
    ]" />

    <div class="content-box">

        <x-content-box-header title="Visualizar Ação Automatizada" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('email-automation-actions.index'),
                'permission' => 'index-email-automation-action',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
            [
                'label' => 'Editar',
                'url' => route('email-automation-actions.edit', ['emailAutomationAction' => $emailAutomationAction->id]),
                'permission' => 'edit-email-automation-action',
                'class' => 'btn-warning-md',
                'icon' => 'lucide-pencil',
            ],
            [
                'label' => 'Apagar',
                'url' => route('email-automation-actions.destroy', ['emailAutomationAction' => $emailAutomationAction->id]),
                'permission' => 'destroy-email-automation-action',
                'class' => 'btn-danger-md',
                'icon' => 'lucide-trash',
                'method' => 'delete',
                'confirm' => true,
                'id' => $emailAutomationAction->id,
            ],
        ]" />

        <x-alert />

        <div class="detail-box">

            <div class="mb-1">
                <span class="title-detail-content">ID: </span>
                <span class="detail-content">{{ $emailAutomationAction->id }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Nome: </span>
                <span class="detail-content">{{ $emailAutomationAction->name }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Recursivo: </span>
                <span class="detail-content">
                    <span class="{{ $emailAutomationAction->is_recursive ? 'badge badge-success' : 'badge badge-danger' }}">
                        {{ $emailAutomationAction->is_recursive ? 'Sim' : 'Não' }}
                    </span>
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Status: </span>
                <span class="detail-content">
                    <span class="{{ $emailAutomationAction->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                        {{ $emailAutomationAction->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Cadastrado: </span>
                <span class="detail-content">{{ \Carbon\Carbon::parse($emailAutomationAction->created_at)->format('d/m/Y H:i:s') }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Editado: </span>
                <span class="detail-content">{{ \Carbon\Carbon::parse($emailAutomationAction->updated_at)->format('d/m/Y H:i:s') }}</span>
            </div>

        </div>

    </div>
@endsection
