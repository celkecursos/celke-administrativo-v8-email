@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Usuários" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Usuários', 'url' => route('users.index')],
        ['label' => 'Usuário'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Detalhes do Usuário" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('users.index'),
                'permission' => 'index-user',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
        ]" />

        <!-- Layout Principal com Menu Lateral e Conteúdo -->
        <div class="form-group-grid-container">

            <!-- Menu Lateral Interno -->
            <x-form-sidebar-menu :items="[
                [
                    'label' => 'Dados do Usuário',
                    'url' => route('users.show', $user->id),
                    'icon' => 'lucide-user',
                ],
                [
                    'label' => 'E-mails Programados',
                    'url' => '#scheduled',
                    'icon' => 'lucide-mail',
                    'active' => true,  // Item ativo nesta página
                ],
                [
                    'label' => 'E-mails Enviados',
                    'url' => route('users.sent', $user->id),
                    'icon' => 'lucide-send',
                ],
                [
                    'label' => 'E-mails Não Enviados',
                    'url' => route('users.failed', $user->id),
                    'icon' => 'lucide-alert-triangle',
                ],
                [
                    'label' => 'Status Atual',
                    'url' => route('users.status', $user->id),
                    'icon' => 'lucide-settings',
                ],
                [
                    'label' => 'Descadastros',
                    'url' => route('users.unsubscribed', $user->id),
                    'icon' => 'lucide-user-x',
                ],
            ]" />

            <!-- Área de Conteúdo Principal -->
            <div class="profile-content-container">

                <!-- Seção: E-mails Programados (única visível nesta página) -->
                <div id="scheduled" class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">E-mails Programados</h3>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr class="table-row-header">
                                            <th class="table-header">ID</th>
                                            <th class="table-header">Título do E-mail</th>
                                            <th class="table-header">Situação</th>
                                            <th class="table-header center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($emailUsers as $emailUser)
                                            <tr class="table-row-body">
                                                <td class="table-body">{{ $emailUser->id }}</td>
                                                <td class="table-body">{{ $emailUser->emailSequenceEmail->title ?? 'N/A' }}</td>
                                                <td class="table-body">
                                                    <span class="{{ $emailUser->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                                        {{ $emailUser->is_active ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                </td>
                                                <td class="table-actions">
                                                    <div class="table-actions-align">
                                                        <!-- Botão Ativar/Desativar -->
                                                        @can('edit-email-user')
                                                            <form action="{{ route('email-users.toggle-status', $emailUser->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn-warning-md align-icon-btn" title="{{ $emailUser->is_active ? 'Desativar' : 'Ativar' }}">
                                                                    @if ($emailUser->is_active)
                                                                        <x-lucide-toggle-right class="icon-btn" />
                                                                    @else
                                                                        <x-lucide-toggle-left class="icon-btn" />
                                                                    @endif
                                                                    <span>{{ $emailUser->is_active ? 'Desativar' : 'Ativar' }}</span>
                                                                </button>
                                                            </form>
                                                        @endcan

                                                        <!-- Botão Apagar -->
                                                        @can('destroy-email-user')
                                                            <form id="delete-form-{{ $emailUser->id }}" action="{{ route('email-users.destroy', $emailUser->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" onclick="confirmDelete({{ $emailUser->id }})" class="btn-danger-md align-icon-btn" title="Apagar">
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
                                                <td colspan="4" class="table-body text-center">
                                                    <div class="alert-warning">
                                                        Nenhum e-mail programado encontrado para este usuário.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection