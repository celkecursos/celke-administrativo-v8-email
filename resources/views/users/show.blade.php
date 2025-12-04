@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Usuários" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Usuários', 'url' => route('users.index')],
        ['label' => 'Usuário'],
    ]" />

    <div class="content-box">

        <x-content-box-header title="Detalhes do Usuário" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('users.index'),
                'permission' => 'index-user',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
        ]" />

        <x-alert />

        <div class="content-box-body">
            <div class="detail-box space-y-4">

                <!-- Imagem do usuário -->
                <div class="flex justify-center mb-6">
                    <img src="{{ $user->image_url }}" alt="{{ $user->name }}"
                        class="w-32 h-32 rounded-full object-cover shadow-lg ring-4 ring-gray-200 dark:ring-gray-600">
                </div>

                <div>
                    <span class="title-detail-content">ID: </span>
                    <span class="detail-content">{{ $user->id }}</span>
                </div>

                <div>
                    <span class="title-detail-content">Nome: </span>
                    <span class="detail-content">{{ $user->name }}</span>
                </div>

                <div>
                    <span class="title-detail-content">E-mail: </span>
                    <span class="detail-content">{{ $user->email }}</span>
                </div>

                <div>
                    <span class="title-detail-content">Tags: </span>
                    <span class="detail-content">
                        @if($user->emailTags->count() > 0)
                            {{ $user->emailTags->pluck('name')->implode(', ') }}
                        @else
                            <span class="text-gray-500 dark:text-gray-400">Nenhuma tag atribuída</span>
                        @endif
                    </span>
                </div>

                @if ($user->cpf)
                    <div>
                        <span class="title-detail-content">CPF: </span>
                        <span class="detail-content">{{ $user->cpf_formatted }}</span>
                    </div>
                @endif

                @if ($user->alias)
                    <div>
                        <span class="title-detail-content">Apelido: </span>
                        <span class="detail-content">{{ $user->alias }}</span>
                    </div>
                @endif

                <div>
                    <span class="title-detail-content">Cadastrado: </span>
                    <span class="detail-content">
                        {{ \Carbon\Carbon::parse($user->created_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                    </span>
                </div>

                <div>
                    <span class="title-detail-content">Editado: </span>
                    <span class="detail-content">
                        {{ \Carbon\Carbon::parse($user->updated_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                    </span>
                </div>

            </div>
        </div>

    </div>

    <!-- Seção: E-mails Programados -->
    <div class="content-box mt-6">
        <div class="content-box-header">
            <h3 class="content-box-title">E-mails Programados</h3>
        </div>

        <div class="table-container mt-4">
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
@endsection
