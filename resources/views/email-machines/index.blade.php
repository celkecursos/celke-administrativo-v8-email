@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Máquinas" :items="[['label' => 'Dashboard', 'url' => route('dashboard.index')], ['label' => 'Máquinas']]" />

    <div class="content-box">

        <x-content-box-header title="Máquinas" :buttons="[
            [
                'label' => 'Cadastrar',
                'url' => route('email-machines.create'),
                'permission' => 'create-email-machine',
                'class' => 'btn-success-md',
                'icon' => 'lucide-pencil',
            ],
        ]" />

        <x-alert />

        <!-- Início Formulário de Pesquisa -->
        <form class="form-search">

            <input type="text" name="name" class="form-input" placeholder="Digite o nome da máquina"
                value="{{ $name }}">

            <div class="flex gap-1">
                <button type="submit" class="btn-primary-md flex items-center space-x-1">
                    <!-- Ícone magnifying-glass (Heroicons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <span>Pesquisar</span>
                </button>
                <a href="{{ route('email-machines.index') }}" type="submit"
                    class="btn-warning-md flex items-center space-x-1">
                    <!-- Ícone trash (Heroicons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
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
                        <th class="table-header">Nome</th>
                        <th class="table-header">Situação</th>
                        <th class="table-header center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Imprimir os registros --}}
                    @forelse ($emailMachines as $emailMachine)
                        <tr class="table-row-body">
                            <td class="table-body">{{ $emailMachine->id }}</td>
                            <td class="table-body">{{ $emailMachine->name }}</td>
                            <td class="table-body">
                                <span class="{{ $emailMachine->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                    {{ $emailMachine->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="table-actions">
                                <x-table-actions :actions="[
                                    [
                                        'can' => 'index-email-machine-sequence',
                                        'type' => 'link',
                                        'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                                        'class' => 'btn-info-md table-md-hidden',
                                        'icon' => 'lucide-list',
                                        'label' => 'Sequência',
                                    ],
                                    [
                                        'can' => 'show-email-machine',
                                        'type' => 'link',
                                        'url' => route('email-machines.show', $emailMachine->id),
                                        'class' => 'btn-primary-md align-icon-btn',
                                        'icon' => 'lucide-eye',
                                        'label' => 'Visualizar',
                                    ],
                                    [
                                        'can' => 'edit-email-machine',
                                        'type' => 'link',
                                        'url' => route('email-machines.edit', $emailMachine->id),
                                        'class' => 'btn-warning-md table-md-hidden',
                                        'icon' => 'lucide-pencil',
                                        'label' => 'Editar',
                                    ],
                                    [
                                        'can' => 'destroy-email-machines',
                                        'type' => 'delete',
                                        'id' => $emailMachine->id,
                                        'url' => route('email-machines.destroy', $emailMachine->id),
                                        'class' => 'btn-danger-md table-md-hidden',
                                        'icon' => 'lucide-trash',
                                        'label' => 'Apagar',
                                    ],
                                ]" />


                            </td>
                        </tr>

                    @empty
                        <div class="alert-warning">
                            Nenhum registro encontrado!
                        </div>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2 p-3">
                {{ $emailMachines->onEachSide(1)->links() }}
            </div>
        </div>

    </div>
@endsection
