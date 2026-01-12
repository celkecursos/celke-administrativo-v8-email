@extends('layouts.admin')

@section('content')

    {{-- Breadcrumb --}}
    <x-breadcrumb title="Editar Servidor de E-mail" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Servidores', 'url' => route('email-sending-configs.index')],
        ['label' => 'Editar'],
    ]" />

    <div class="content-box">

        <x-alert />

        {{-- Cabeçalho --}}
        <x-content-box-header title="Editar Servidor de E-mail" :buttons="[
            [
                'label' => 'Voltar',
                'url' => route('email-sending-configs.index'),
                'permission' => 'index-email-sending-config',
                'class' => 'btn-info-md align-icon-btn',
                'icon' => 'lucide-arrow-left',
            ],
        ]" />

        {{-- Layout com menu lateral --}}
        <div class="form-group-grid-container">

            {{-- Menu lateral --}}
            <x-form-sidebar-menu :items="[
                [
                    'label' => 'Credenciais',
                    'url' => route('email-sending-configs.edit', $emailSendingConfig->id),
                    'permission' => 'edit-email-sending-config',
                    'icon' => 'lucide-key',
                    'active' => request()->routeIs('email-sending-configs.edit'),
                ],
                [
                    'label' => 'Senha',
                    'url' => route('email-sending-configs.edit-password', $emailSendingConfig->id),
                    'permission' => 'edit-email-sending-config',
                    'icon' => 'lucide-lock',
                    'active' => request()->routeIs('email-sending-configs.edit-password'),
                ],
                [
                    'label' => 'Remetente',
                    'url' => route('email-sending-configs.edit-sender', $emailSendingConfig->id),
                    'permission' => 'edit-email-sending-config',
                    'icon' => 'lucide-mail',
                    'active' => request()->routeIs('email-sending-configs.edit-sender'),
                ],
                [
                    'label' => 'Configurações',
                    'url' => route('email-sending-configs.edit-settings', $emailSendingConfig->id),
                    'permission' => 'edit-email-sending-config',
                    'icon' => 'lucide-settings',
                    'active' => request()->routeIs('email-sending-configs.edit-settings'),
                ],
            ]" />

            {{-- Conteúdo --}}
            <div class="profile-content-container">

                <div class="profile-section">
                    <div class="sidebar-card">

                        <div class="sidebar-card-header">
                            <h3 class="sidebar-card-title">Senha</h3>
                            <p class="form-helper-text">
                                Atualize a senha utilizada pelo servidor de e-mail
                            </p>
                        </div>

                        <div class="p-4">
                            <form
                                action="{{ route('email-sending-configs.update-password', $emailSendingConfig->id) }}"

                                method="POST"
                            >
                                @csrf
                                @method('PUT')

                                {{-- Senha --}}
                                <div class="mb-4">
                                    <label for="password" class="form-label">Senha *</label>
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-input"
                                        placeholder="Digite a nova senha"
                                    >
                                    @error('password')
                                        <p class="form-input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="btn-warning-md align-icon-btn">
                                    <x-lucide-save class="icon-btn" />
                                    <span>Salvar</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection