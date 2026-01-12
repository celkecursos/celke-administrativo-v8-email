@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb
        title="Servidores de E-mail"
        :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard.index')],
            ['label' => 'Servidor'],
        ]"
    />

    <div class="content-box">

        <x-content-box-header
            title="Servidores de E-mail"
            :buttons="[
                [
                    'label' => 'Cadastrar',
                    'url' => route('email-sending-configs.create'),
                    'permission' => 'create-email-sending-config',
                    'class' => 'btn-success-md',
                    'icon' => 'lucide-pencil',
                ],
            ]"
        />

        <x-alert />

        <!-- Início Formulário de Pesquisa -->
        <form class="form-search">

            <input
                type="text"
                name="provider"
                class="form-input"
                placeholder="Digite o provedor (ex: sendgrid, ses)"
                value="{{ $provider }}"
            >

            <div class="flex gap-1">
                <button type="submit" class="btn-primary-md flex items-center space-x-1">
                    <!-- Ícone magnifying-glass -->
                    <svg xmlns="http://www.w3.org2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <span>Pesquisar</span>
                </button>

                <a href="{{ route('email-sending-configs.index') }}"
                    class="btn-warning-md flex items-center space-x-1">
                    <!-- Ícone trash -->
                    <svg xmlns="http://www.w3.org2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165
                            L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084
                            a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79
                            m14.456 0a48.108 48.108 0 0 0-3.478-.397
                            m-12 .562c.34-.059.68-.114 1.022-.165
                            m0 0a48.11 48.11 0 0 1 3.478-.397
                            m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201
                            a51.964 51.964 0 0 0-3.32 0
                            c-1.18.037-2.09 1.022-2.09 2.201v.916
                            m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Limpar</span>
                </a>
            </div>
        </form>
        <!-- Fim Formulário de Pesquisa -->

        <div class="table-container mt-6">
            <table class="table">
                <thead>
                    <tr class="table-row-header">
                        <th class="table-header">ID</th>
                        <th class="table-header">Provedor</th>
                        <th class="table-header">Remetente</th>
                        <th class="table-header">Marketing</th>
                        <th class="table-header">Transacional</th>
                        <th class="table-header center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Imprimir os registros --}}
                    @forelse ($emailSendingConfigs as $emailSendingConfig)
                        <tr class="table-row-body">
                            <td class="table-body">{{ $emailSendingConfig->id }}</td>

                            <td class="table-body">
                                {{ $emailSendingConfig->provider }}
                            </td>

                            <td class="table-body">
                                <div class="flex flex-col">
                                    <span>{{ $emailSendingConfig->from_name }}</span>
                                    <small class="text-gray-500">{{ $emailSendingConfig->from_email }}</small>
                                </div>
                            </td>

                            <td class="table-body">
                                <span class="{{ $emailSendingConfig->is_active_marketing ? 'badge badge-success' : 'badge badge-danger' }}">
                                    {{ $emailSendingConfig->is_active_marketing ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>

                            <td class="table-body">
                                <span class="{{ $emailSendingConfig->is_active_transactional ? 'badge badge-success' : 'badge badge-danger' }}">
                                    {{ $emailSendingConfig->is_active_transactional ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>

                            <td class="table-actions">
                                <x-table-actions
                                    :actions="[
                                        [
                                            'can' => 'show-email-sending-config',
                                            'type' => 'link',
                                            'url' => route('email-sending-configs.show', $emailSendingConfig->id),
                                            'class' => 'btn-info-md align-icon-btn',
                                            'icon' => 'lucide-eye',
                                            'label' => 'Visualizar',
                                        ],
                                        [
                                            'can' => 'edit-email-sending-config',
                                            'type' => 'link',
                                            'url' => route('email-sending-configs.edit', $emailSendingConfig->id),
                                            'class' => 'btn-warning-md table-md-hidden',
                                            'icon' => 'lucide-pencil',
                                            'label' => 'Editar',
                                        ],
                                        [
                                            'can' => 'destroy-email-sending-config',
                                            'type' => 'delete',
                                            'id' => $emailSendingConfig->id,
                                            'url' => route('email-sending-configs.destroy', $emailSendingConfig->id),
                                            'class' => 'btn-danger-md table-md-hidden',
                                            'icon' => 'lucide-trash',
                                            'label' => 'Apagar',
                                        ],
                                    ]"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="table-body">
                                Nenhum servidor de e-mail encontrado!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Imprimir a paginação --}}
        {{ $emailSendingConfigs->appends(['provider' => $provider])->links() }}

    </div>
@endsection