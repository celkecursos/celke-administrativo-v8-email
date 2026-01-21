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
                    'url' => '#details',
                    'icon' => 'lucide-user',
                    'active' => true,  // Primeiro item ativo por padrão
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

                <!-- Seção: Dados do Usuário (única visível na show) -->
                <div id="details" class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Dados do Usuário</h3>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="detail-box space-y-4">
                                <!-- Imagem do usuário -->
                                <div class="flex justify-center mb-6">
                                    <img src="{{ $user->image_url }}" alt="{{ $user->name }}"
                                        class="w-32 h-32 rounded-full object-cover shadow-lg ring-4 ring-gray-200 dark:ring-gray-600">
                                </div>

                                <div>
                                    <span class="title-detail-content">ID: </span>
                                    <span class="detail-content">{{ $user->id }}</span>
                                </div>

                                <div>
                                    <span class="title-detail-content">Nome: </span>
                                    <span class="detail-content">{{ $user->name }}</span>
                                </div>

                                <div>
                                    <span class="title-detail-content">E-mail: </span>
                                    <span class="detail-content">{{ $user->email }}</span>
                                </div>

                                <div>
                                    <span class="title-detail-content">Tags: </span>
                                    <span class="detail-content">
                                        @if($user->emailTags->count() > 0)
                                            {{ $user->emailTags->pluck('name')->implode(', ') }}
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">Nenhuma tag atribuída</span>
                                        @endif
                                    </span>
                                </div>

                                @if ($user->cpf)
                                    <div>
                                        <span class="title-detail-content">CPF: </span>
                                        <span class="detail-content">{{ $user->cpf_formatted }}</span>
                                    </div>
                                @endif

                                @if ($user->alias)
                                    <div>
                                        <span class="title-detail-content">Apelido: </span>
                                        <span class="detail-content">{{ $user->alias }}</span>
                                    </div>
                                @endif

                                <div>
                                    <span class="title-detail-content">Cadastrado: </span>
                                    <span class="detail-content">
                                        {{ \Carbon\Carbon::parse($user->created_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                                    </span>
                                </div>

                                <div>
                                    <span class="title-detail-content">Editado: </span>
                                    <span class="detail-content">
                                        {{ \Carbon\Carbon::parse($user->updated_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection