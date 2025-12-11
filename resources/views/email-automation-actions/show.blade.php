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

    <!-- Seção: Gatilhos de Automação -->
    <div class="content-box mt-6">
        <div class="content-box-header">
            <h3 class="content-box-title">Gatilhos de Automação</h3>
        </div>

        <div class="table-container mt-4">
            <table class="table">
                <thead>
                    <tr class="table-row-header">
                        <th class="table-header">ID</th>
                        <th class="table-header">Tipo de Filtro</th>
                        <th class="table-header">Tipo de Ação</th>
                        <th class="table-header">Situação</th>
                        <th class="table-header center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($emailAutomationTriggers as $emailAutomationTrigger)
                        <tr class="table-row-body">
                            <td class="table-body">{{ $emailAutomationTrigger->id }}</td>
                            <td class="table-body">{{ $emailAutomationTrigger->filterType->name ?? 'N/A' }}</td>
                            <td class="table-body">{{ $emailAutomationTrigger->actionType->name ?? 'N/A' }}</td>
                            <td class="table-body">
                                <span class="{{ $emailAutomationTrigger->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                    {{ $emailAutomationTrigger->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="table-actions">
                                <div class="table-actions-align">
                                    <!-- Botão Visualizar -->
                                    @can('show-email-automation-trigger')
                                        <a href="{{ route('email-automation-triggers.show', $emailAutomationTrigger->id) }}" class="btn-primary-md align-icon-btn" title="Visualizar">
                                            <x-lucide-eye class="icon-btn" />
                                            <span>Visualizar</span>
                                        </a>
                                    @endcan

                                    <!-- Botão Editar -->
                                    @can('update-email-automation-trigger')
                                        <a href="{{ route('email-automation-triggers.edit', $emailAutomationTrigger) }}"
                                        class="btn-warning-md align-icon-btn" title="Editar">
                                            <x-lucide-pencil class="icon-btn" />
                                            <span>Editar</span>
                                        </a>
                                    @endcan

                                    <!-- Botão Apagar -->
                                    @can('destroy-email-automation-trigger')
                                        <form id="delete-form-{{ $emailAutomationTrigger->id }}" action="{{ route('email-automation-triggers.destroy', $emailAutomationTrigger->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $emailAutomationTrigger->id }})" class="btn-danger-md align-icon-btn" title="Apagar">
                                                <x-lucide-trash class="icon-btn" />
                                                <span>Apagar</span>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-body text-center">
                                <div class="alert-warning">
                                    Nenhum gatilho encontrado para esta ação automatizada.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Tem certeza que deseja apagar este gatilho?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endsection
