@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Visualizar E-mail - E-mails Enviados" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => $sequence->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => 'E-mails Enviados'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Visualizar E-mail da Sequência" :buttons="[
            [
                'label' => 'Sequências',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'permission' => 'index-email-machine-sequence',
                'class' => 'btn-info-md align-icon-btn',
                'icon' => 'lucide-list',
            ],
        ]" />

        <!-- Layout Principal com Menu Lateral e Conteúdo -->
        <div class="form-group-grid-container">

            <!-- Menu Lateral de Visualização -->
            <x-form-sidebar-menu :items="[
                [
                    'label' => 'Conteúdo',
                    'url' => route('email-sequence-emails.show', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-file-text',
                    'active' => request()->routeIs('email-sequence-emails.show'),
                ],
                [
                    'label' => 'Datas',
                    'url' => route('email-sequence-emails.show-dates', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-calendar-clock',
                    'active' => request()->routeIs('email-sequence-emails.show-dates'),
                ],
                [
                    'label' => 'Configuração',
                    'url' => route('email-sequence-emails.show-config', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-settings',
                    'active' => request()->routeIs('email-sequence-emails.show-config'),
                ],
                [
                    'label' => 'Usuários',
                    'url' => route('email-sequence-emails.show-users', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-users',
                    'active' => request()->routeIs('email-sequence-emails.show-users'),
                ],
                [
                    'label' => 'E-mails Enviados',
                    'url' => route('email-sequence-emails.show-sent', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-mail',
                    'active' => request()->routeIs('email-sequence-emails.show-sent'),
                ],
            ]" />

            <!-- Área de Conteúdo Principal -->
            <div class="profile-content-container">

                <!-- Seção E-mails Enviados -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">E-mails Enviados</h3>
                                    <p class="form-helper-text">
                                        Lista de e-mails enviados para este conteúdo
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            @forelse($sentEmails as $sentEmail)
                                <div class="mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="title-detail-content">ID: </span>
                                                <span class="detail-content">{{ $sentEmail->id }}</span>
                                            </div>
                                            <span class="badge badge-success">Enviado</span>
                                        </div>

                                        <div>
                                            <span class="title-detail-content">E-mail: </span>
                                            <span class="detail-content"><a href='{{ route('users.show', $sentEmail->user->id) }}'>{{ $sentEmail->to_email }}</a></span>
                                        </div>

                                        <div>
                                            <span class="title-detail-content">Data de Criação: </span>
                                            <span class="detail-content">
                                                {{ \Carbon\Carbon::parse($sentEmail->created_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                                            </span>
                                        </div>

                                        <!-- Remover botões de ação, pois é apenas visualização de enviados -->
                                    </div>
                                </div>
                            @empty
                                <div class="alert-warning">
                                    <x-lucide-alert-circle class="w-5 h-5 inline-block mr-2" />
                                    Nenhum e-mail enviado para este conteúdo.
                                </div>
                            @endforelse

                            <!-- Paginação -->
                            @if($sentEmails->hasPages())
                                <div class="mt-6">
                                    {{ $sentEmails->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection