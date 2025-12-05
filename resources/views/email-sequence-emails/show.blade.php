@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Visualizar E-mail" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => $sequence->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => 'Visualizar E-mail'],
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
                'url' => route('email-sequence-emails.edit', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
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

                <!-- Seção Conteúdo do E-mail -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Conteúdo do E-mail</h3>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="detail-box space-y-4">
                                
                                <div>
                                    <span class="title-detail-content">ID: </span>
                                    <span class="detail-content">{{ $email->id }}</span>
                                </div>

                                <div>
                                    <span class="title-detail-content">Título: </span>
                                    <span class="detail-content">{{ $email->title }}</span>
                                </div>

                                <div>
                                    <span class="title-detail-content">Conteúdo: </span>
                                    <div class="detail-content mt-2 whitespace-pre-wrap">{{ $email->content }}</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
