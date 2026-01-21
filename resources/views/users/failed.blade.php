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
                    'url' => route('users.scheduled', $user->id),
                    'icon' => 'lucide-mail',
                ],
                [
                    'label' => 'E-mails Enviados',
                    'url' => route('users.sent', $user->id),
                    'icon' => 'lucide-send',
                ],
                [
                    'label' => 'E-mails Não Enviados',
                    'url' => '#failed',
                    'icon' => 'lucide-alert-triangle',
                    'active' => true,  // Item ativo nesta página
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

                <!-- Seção: E-mails Não Enviados (única visível nesta página) -->
                <div id="failed" class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">E-mails Não Enviados</h3>
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
                                            <th class="table-header">Erro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($failedEmails as $failedEmail)
                                            <tr class="table-row-body">
                                                <td class="table-body">{{ $failedEmail->id }}</td>
                                                <td class="table-body">{{ $failedEmail->email_title ?? 'N/A' }}</td>
                                                <td class="table-body">{{ $failedEmail->error_message ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="table-body text-center">
                                                    <div class="alert-warning">
                                                        Nenhum e-mail não enviado encontrado para este usuário.
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