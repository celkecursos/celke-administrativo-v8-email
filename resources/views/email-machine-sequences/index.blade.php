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
                'icon' => 'lucide-arrow-left',
            ],
        ]" />

        <x-alert />

        <!-- Botão Nova Sequência -->
        <div class="add-sequence-button">
            @can('create-email-machine-sequence')
                <a href="{{ route('email-machine-sequences.create', ['emailMachine' => $emailMachine->id]) }}"
                    class="btn-success-md align-icon-btn">
                    <x-lucide-plus class="icon-btn" />
                    <span>Nova Sequência</span>
                </a>
            @endcan
        </div>

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
                                <form
                                    action="{{ route('email-machine-sequences.destroy', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id]) }}"
                                    method="POST" class="inline-block"
                                    onsubmit="return confirm('Tem certeza que deseja apagar esta sequência?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-md align-icon-btn" title="Apagar">
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
                                <button class="btn-success-md align-icon-btn" disabled title="Em breve">
                                    <x-lucide-plus class="icon-btn" />
                                    <span>Novo E-mail</span>
                                </button>
                            @endcan
                        </div>

                        <!-- Lista de E-mails -->
                        <div class="emails-list">
                            @forelse ($sequence->emails as $email)
                                <div class="email-item">
                                    <div class="email-info">
                                        <x-lucide-mail class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                                        <div>
                                            <p class="email-title">{{ $email->title }}</p>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span
                                                    class="{{ $email->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                                    {{ $email->is_active ? 'Ativo' : 'Inativo' }}
                                                </span>
                                                @if ($email->skip_email)
                                                    <span class="badge badge-warning">Pular</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ações do E-mail (Desabilitadas por enquanto) -->
                                    <div class="email-actions">
                                        <button class="btn-primary align-icon-btn" disabled title="Em breve">
                                            <x-lucide-eye class="w-4 h-4" />
                                        </button>
                                        <button class="btn-warning align-icon-btn" disabled title="Em breve">
                                            <x-lucide-pencil class="w-4 h-4" />
                                        </button>
                                        <button class="btn-danger align-icon-btn" disabled title="Em breve">
                                            <x-lucide-trash class="w-4 h-4" />
                                        </button>
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
