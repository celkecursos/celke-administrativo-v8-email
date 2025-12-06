@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Sequências e E-mails" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name],
    ]" />

    <div class="content-box">

        <x-content-box-header :title="'Sequências: ' . $emailMachine->name" :buttons="[
            [
            'label' => 'Máquinas',
            'url' => route('email-machines.index'),
            'permission' => 'index-email-machine',
            'class' => 'btn-info-md',
            'icon' => 'lucide-list',
            ],
            [
            'label' => 'Nova Sequência',
            'url' => route('email-machine-sequences.create', ['emailMachine' => $emailMachine->id]),
            'permission' => 'create-email-machine-sequence',
            'class' => 'btn-success-md',
            'icon' => 'lucide-plus',
            ],
        ]" />

        <x-alert />

        <!-- Container das Sequências -->
        <div class="sequences-container">
            @forelse ($sequences as $index => $sequence)
                <div class="sequence-card">
                    <!-- Cabeçalho da Sequência -->
                    <div class="sequence-header">
                        <div class="sequence-info">
                            <!-- Número da Sequência com cor -->
                            <div class="sequence-number sequence-color-{{ ($index % 8) + 1 }}">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h3 class="sequence-title">{{ $sequence->name }}</h3>
                                <span
                                    class="{{ $sequence->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                    {{ $sequence->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                        </div>

                        <!-- Botões de Ação da Sequência -->
                        <div class="sequence-actions">
                            @can('show-email-machine-sequence')
                                <a href="{{ route('email-machine-sequences.show', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id]) }}"
                                    class="btn-primary-md align-icon-btn" title="Visualizar">
                                    <x-lucide-eye class="icon-btn" />
                                    <span class="hidden md:inline">Visualizar</span>
                                </a>
                            @endcan

                            @can('edit-email-machine-sequence')
                                <a href="{{ route('email-machine-sequences.edit', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id]) }}"
                                    class="btn-warning-md align-icon-btn" title="Editar">
                                    <x-lucide-pencil class="icon-btn" />
                                    <span class="hidden md:inline">Editar</span>
                                </a>
                            @endcan

                            @can('destroy-email-machine-sequence')
                                <form id="delete-form-{{ $sequence->id }}"
                                    action="{{ route('email-machine-sequences.destroy', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id]) }}"
                                    method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $sequence->id }})" 
                                        class="btn-danger-md align-icon-btn" title="Apagar">
                                        <x-lucide-trash class="icon-btn" />
                                        <span class="hidden md:inline">Apagar</span>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <!-- Corpo da Sequência (Lista de E-mails) -->
                    <div class="sequence-body">
                        <!-- Botão Novo E-mail -->
                        <div class="sequence-new-email">
                            @can('create-email-sequence-email')
                                <a href="{{ route('email-sequence-emails.create', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id]) }}"
                                    class="btn-success-md align-icon-btn" title="Novo E-mail">
                                    <x-lucide-plus class="icon-btn" />
                                    <span>Novo E-mail</span>
                                </a>
                            @endcan
                        </div>

                        <!-- Lista de E-mails -->
                        <div class="emails-list">
                            @forelse ($sequence->emails as $emailIndex => $email)
                                <div class="email-item">
                                    <div class="email-info">
                                        <!-- Número da Ordem -->
                                        <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full font-bold text-sm">
                                            {{ $email->order }}
                                        </div>
                                        <x-lucide-mail class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                                        <div>
                                            <p class="email-title">{{ $email->title }}</p>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <!-- Botão Ativar/Desativar -->
                                                @can('edit-email-sequence-email')
                                                    <form action="{{ route('email-sequence-emails.toggle-status', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}" 
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                            class="btn-{{ $email->is_active ? 'success' : 'danger' }} align-icon-btn" 
                                                            title="{{ $email->is_active ? 'Desativar' : 'Ativar' }}">
                                                            @if ($email->is_active)
                                                                <x-lucide-toggle-right class="w-4 h-4" />
                                                            @else
                                                                <x-lucide-toggle-left class="w-4 h-4" />
                                                            @endif
                                                            <span>{{ $email->is_active ? 'Ativo' : 'Inativo' }}</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="{{ $email->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                                        {{ $email->is_active ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                @endcan
                                                @if ($email->skip_email)
                                                    <span class="badge badge-warning">Pular</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ações do E-mail -->
                                    <div class="email-actions">
                                        @can('edit-email-sequence-email')
                                            <!-- Botão Subir -->
                                            <form action="{{ route('email-sequence-emails.move-up', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                    class="btn-secondary align-icon-btn" 
                                                    title="Subir"
                                                    {{ $emailIndex === 0 ? 'disabled' : '' }}>
                                                    <x-lucide-arrow-up class="w-4 h-4" />
                                                </button>
                                            </form>
                                            <!-- Botão Descer -->
                                            <form action="{{ route('email-sequence-emails.move-down', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                    class="btn-secondary align-icon-btn" 
                                                    title="Descer"
                                                    {{ $emailIndex === count($sequence->emails) - 1 ? 'disabled' : '' }}>
                                                    <x-lucide-arrow-down class="w-4 h-4" />
                                                </button>
                                            </form>
                                        @endcan
                                        @can('show-email-sequence-email')
                                            <a href="{{ route('email-sequence-emails.show', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}"
                                                class="btn-primary align-icon-btn" title="Visualizar">
                                                <x-lucide-eye class="w-4 h-4" />
                                            </a>
                                        @endcan
                                        @can('edit-email-sequence-email')
                                            <a href="{{ route('email-sequence-emails.edit', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}"
                                                class="btn-warning align-icon-btn" title="Editar">
                                                <x-lucide-pencil class="w-4 h-4" />
                                            </a>
                                        @endcan
                                        @can('destroy-email-sequence-email')
                                            <form id="delete-email-form-{{ $email->id }}"
                                                action="{{ route('email-sequence-emails.destroy', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $email->id }}, 'email')" 
                                                    class="btn-danger align-icon-btn" title="Apagar">
                                                    <x-lucide-trash class="w-4 h-4" />
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <div class="no-emails-message">
                                    <x-lucide-mail-x class="w-8 h-8 mx-auto mb-2 text-gray-400" />
                                    <p>Nenhum e-mail cadastrado nesta sequência.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert-warning">
                    <x-lucide-alert-circle class="w-5 h-5 inline-block mr-2" />
                    Nenhuma sequência cadastrada para esta máquina.
                </div>
            @endforelse
        </div>

    </div>
@endsection
