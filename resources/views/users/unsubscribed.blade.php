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
                    'url' => route('users.failed', $user->id),
                    'icon' => 'lucide-alert-triangle',
                ],
                [
                    'label' => 'Editar Status Atual',
                    'url' => route('users.status', $user->id),
                    'icon' => 'lucide-settings',
                ],
                [
                    'label' => 'Descadastros',
                    'url' => '#unsubscribed',
                    'icon' => 'lucide-user-x',
                    'active' => true,  // Item ativo nesta página
                ],
            ]" />

            <!-- Área de Conteúdo Principal -->
            <div class="profile-content-container">

                <!-- Seção: Descadastros (única visível nesta página) -->
                <div id="unsubscribed" class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Descadastros</h3>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr class="table-row-header">
                                            <th class="table-header">ID</th>
                                            <th class="table-header">Data do Descadastro</th>
                                            <th class="table-header">Motivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($unsubscribed as $unsub)
                                            <tr class="table-row-body">
                                                <td class="table-body">{{ $unsub->id }}</td>
                                                <td class="table-body">{{ $unsub->unsubscribed_at ? \Carbon\Carbon::parse($unsub->unsubscribed_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                                <td class="table-body">{{ $unsub->reason ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="table-body text-center">
                                                    <div class="alert-warning">
                                                        Nenhum descadastro encontrado para este usuário.
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