@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Visualizar E-mail - Usuários" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => $sequence->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => 'Usuários'],
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
            ]" />

            <!-- Área de Conteúdo Principal -->
            <div class="profile-content-container">

                <!-- Seção Usuários Vinculados -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Usuários Programados</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Lista de usuários que receberão este e-mail
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            @forelse($email->emailUser as $emailUser)
                                <div class="mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="title-detail-content">Usuário: </span>
                                                <span class="detail-content">{{ $emailUser->user->name }}</span>
                                            </div>
                                            <span class="{{ $emailUser->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                                {{ $emailUser->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </div>

                                        <div>
                                            <span class="title-detail-content">E-mail: </span>
                                            <span class="detail-content">{{ $emailUser->user->email }}</span>
                                        </div>

                                        @if($emailUser->scheduled_send_date)
                                            <div>
                                                <span class="title-detail-content">Envio Agendado: </span>
                                                <span class="detail-content">
                                                    {{ \Carbon\Carbon::parse($emailUser->scheduled_send_date)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($emailUser->sent_date)
                                            <div>
                                                <span class="title-detail-content">Data de Envio: </span>
                                                <span class="detail-content">
                                                    {{ \Carbon\Carbon::parse($emailUser->sent_date)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                                                </span>
                                            </div>
                                        @endif

                                        <div>
                                            <span class="title-detail-content">Status de Envio: </span>
                                            @if($emailUser->sent_date)
                                                <span class="badge badge-success">Enviado</span>
                                            @else
                                                <span class="badge badge-warning">Pendente</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert-warning">
                                    <x-lucide-alert-circle class="w-5 h-5 inline-block mr-2" />
                                    Nenhum usuário programado para receber este e-mail.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
