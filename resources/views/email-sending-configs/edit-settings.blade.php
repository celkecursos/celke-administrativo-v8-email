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
                            <h3 class="sidebar-card-title">Configurações do Servidor</h3>
                            <p class="form-helper-text">
                                Defina o status e o tipo de envio permitido
                            </p>
                        </div>

                        <div class="p-4">
                            <form
                                action="{{ route('email-sending-configs.update-settings', $emailSendingConfig->id) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PUT')

                                {{-- Quantidade por requisição --}}
                                <div class="mb-4">
                                    <label class="form-label">Quantidade por requisição *</label>
                                    <input
                                        type="number"
                                        name="send_quantity_per_request"
                                        class="form-input"
                                        min="1"
                                        value="{{ old('send_quantity_per_request', $emailSendingConfig->send_quantity_per_request) }}"
                                    >
                                    @error('send_quantity_per_request')
                                        <p class="form-input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Quantidade por hora --}}
                                <div class="mb-4">
                                    <label class="form-label">Quantidade por hora *</label>
                                    <input
                                        type="number"
                                        name="send_quantity_per_hour"
                                        class="form-input"
                                        min="1"
                                        value="{{ old('send_quantity_per_hour', $emailSendingConfig->send_quantity_per_hour) }}"
                                    >
                                    @error('send_quantity_per_hour')
                                        <p class="form-input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Envio Marketing --}}
                                <div class="mb-4">
                                    <label class="form-label">Permitir Envio Marketing *</label>

                                    <input type="radio" name="is_active_marketing" value="1"
                                        {{ old('is_active_marketing', $emailSendingConfig->is_active_marketing) == 1 ? 'checked' : '' }}>
                                    <label class="form-input-checkbox">Sim</label>

                                    <input type="radio" name="is_active_marketing" value="0"
                                        {{ old('is_active_marketing', $emailSendingConfig->is_active_marketing) == 0 ? 'checked' : '' }}>
                                    <label class="form-input-checkbox">Não</label>

                                    @error('is_active_marketing')
                                        <p class="form-input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Envio Transacional --}}
                                <div class="mb-4">
                                    <label class="form-label">Permitir Envio Transacional *</label>

                                    <input type="radio" name="is_active_transactional" value="1"
                                        {{ old('is_active_transactional', $emailSendingConfig->is_active_transactional) == 1 ? 'checked' : '' }}>
                                    <label class="form-input-checkbox">Sim</label>

                                    <input type="radio" name="is_active_transactional" value="0"
                                        {{ old('is_active_transactional', $emailSendingConfig->is_active_transactional) == 0 ? 'checked' : '' }}>
                                    <label class="form-input-checkbox">Não</label>

                                    @error('is_active_transactional')
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