@extends('layouts.admin')

@section('content')
    <!-- Título e Breadcrumb -->
    <x-breadcrumb title="Editar Servidor de E-mail" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Servidores de E-mail', 'url' => route('email-sending-configs.index')],
        ['label' => 'Editar'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Editar Servidor de E-mail" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('email-sending-configs.index'),
                'permission' => 'index-email-sending-config',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
            [
                'label' => 'Visualizar',
                'url' => route('email-sending-configs.show', $emailSendingConfig->id),
                'permission' => 'show-email-sending-config',
                'class' => 'btn-primary-md',
                'icon' => 'lucide-eye',
            ],
        ]" />

        <!-- Layout com Menu Lateral + Conteúdo -->
        <div class="form-group-grid-container">

            <!-- Menu Lateral -->
            <x-form-sidebar-menu :items="[
                [
                    'label' => 'Credenciais SMTP',
                    'url' => route('email-sending-configs.edit', $emailSendingConfig->id),
                    'icon' => 'lucide-key',
                    'active' => request()->routeIs('email-sending-configs.edit'),
                ],
                [
                    'label' => 'Credenciais API',
                    'url' => route('email-sending-configs.edit-api', $emailSendingConfig->id),
                    'permission' => 'edit-email-sending-config',
                    'icon' => 'lucide-server-cog',
                    'active' => request()->routeIs('email-sending-configs.edit-api'),
                ],
                [
                    'label' => 'Senha',
                    'url' => route('email-sending-configs.edit-password', $emailSendingConfig->id),
                    'icon' => 'lucide-lock',
                    'active' => request()->routeIs('email-sending-configs.edit-password'),
                ],
                [
                    'label' => 'Remetente',
                    'url' => route('email-sending-configs.edit-sender', $emailSendingConfig->id),
                    'icon' => 'lucide-mail',
                    'active' => request()->routeIs('email-sending-configs.edit-sender'),
                ],
                [
                    'label' => 'Configurações',
                    'url' => route('email-sending-configs.edit-settings', $emailSendingConfig->id),
                    'icon' => 'lucide-settings',
                    'active' => request()->routeIs('email-sending-configs.edit-settings'),
                ],
            ]" />

            <!-- Conteúdo Principal -->
            <div class="profile-content-container">

                <div class="profile-section">
                    <div class="sidebar-card">

                        <div class="sidebar-card-header">
                            <h3 class="sidebar-card-title">Credenciais API</h3>
                        </div>

                        <div class="p-4">
                            <form action="{{ route('email-sending-configs.update-api', $emailSendingConfig) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label class="form-label">Provedor *</label>
                                    <input type="text" name="provider" class="form-input" value="{{ old('provider', $emailSendingConfig->provider) }}" placeholder="Nome do servidor SMTP" required>
                                    @error('provider')
                                        <p class="form-input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Usuário da API</label>
                                    <input type="text" name="api_user" class="form-input" value="{{ old('api_user', $emailSendingConfig->api_user) }}" placeholder="Usuário da API">
                                    @error('api_user')
                                        <p class="form-input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Chave da API</label>
                                    <input type="text" name="api_key" class="form-input" value="{{ old('api_key', $emailSendingConfig->api_key) }}" placeholder="Chave da API">
                                    @error('api_key')
                                        <p class="form-input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="btn-warning-md align-icon-btn">
                                    <x-lucide-save class="icon-btn" /> Salvar Credenciais
                                </button>
                            </form>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection