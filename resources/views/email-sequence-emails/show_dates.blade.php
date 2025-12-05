@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Visualizar E-mail - Datas" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => $sequence->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => 'Datas'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Visualizar E-mail da Sequência" :buttons="[
            [
                'label' => 'Sequências',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'permission' => 'index-email-machine-sequence',
                'class' => 'btn-info-md align-icon-btn',
                'icon' => 'lucide-arrow-left',
            ],
            [
                'label' => 'Editar',
                'url' => route('email-sequence-emails.edit-dates', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
                'permission' => 'edit-email-sequence-email',
                'class' => 'btn-warning-md align-icon-btn',
                'icon' => 'lucide-pencil',
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

                <!-- Seção Atraso de Envio -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Atraso de Envio</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tempo de espera antes de enviar este e-mail</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="detail-box space-y-4">
                                
                                <div>
                                    <span class="title-detail-content">Dias: </span>
                                    <span class="detail-content">{{ $email->delay_day ?? 0 }}</span>
                                </div>

                                <div>
                                    <span class="title-detail-content">Horas: </span>
                                    <span class="detail-content">{{ $email->delay_hour ?? 0 }}</span>
                                </div>

                                <div>
                                    <span class="title-detail-content">Minutos: </span>
                                    <span class="detail-content">{{ $email->delay_minute ?? 0 }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção Data Fixa de Envio -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Data Fixa de Envio</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Envio agendado para data e hora específica</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="detail-box space-y-4">
                                
                                <div>
                                    <span class="title-detail-content">Usar Data Fixa: </span>
                                    <span class="{{ $email->use_fixed_send_datetime ? 'badge badge-success' : 'badge badge-secondary' }}">
                                        {{ $email->use_fixed_send_datetime ? 'Sim' : 'Não' }}
                                    </span>
                                </div>

                                @if($email->fixed_send_datetime)
                                    <div>
                                        <span class="title-detail-content">Data e Hora: </span>
                                        <span class="detail-content">
                                            {{ \Carbon\Carbon::parse($email->fixed_send_datetime)->tz('America/Sao_Paulo')->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                @else
                                    <div>
                                        <span class="detail-content text-gray-500">Nenhuma data fixa definida</span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção Janela de Envio -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Janela de Envio</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Horário permitido para envio do e-mail</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="detail-box space-y-4">
                                
                                @if($email->send_window_start_hour !== null || $email->send_window_start_minute !== null)
                                    <div>
                                        <span class="title-detail-content">Horário Inicial: </span>
                                        <span class="detail-content">
                                            {{ str_pad($email->send_window_start_hour ?? 0, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($email->send_window_start_minute ?? 0, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                @endif

                                @if($email->send_window_end_hour !== null || $email->send_window_end_minute !== null)
                                    <div>
                                        <span class="title-detail-content">Horário Final: </span>
                                        <span class="detail-content">
                                            {{ str_pad($email->send_window_end_hour ?? 0, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($email->send_window_end_minute ?? 0, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                @endif

                                @if($email->send_window_start_hour === null && $email->send_window_end_hour === null)
                                    <div>
                                        <span class="detail-content text-gray-500">Nenhuma janela de envio definida</span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
