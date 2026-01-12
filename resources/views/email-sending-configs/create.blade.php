@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Cadastrar Servidor de E-mail" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Servidores de E-mail', 'url' => route('email-sending-configs.index')],
        ['label' => 'Cadastrar Servidor de E-mail'],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Cadastrar Servidor de E-mail" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('email-sending-configs.index'),
                'permission' => 'index-email-sending-config',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
        ]" />

        <x-alert />

        <form action="{{ route('email-sending-configs.store') }}" method="POST">
            @csrf

            <!-- Provedor -->
            <div class="mb-4">
                <label for="provider" class="form-label">Provedor *</label>
                <input
                    type="text"
                    name="provider"
                    id="provider"
                    class="form-input"
                    value="{{ old('provider') }}"
                    placeholder="Nome do Provedor"
                    required
                >
                @error('provider')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nome do remetente -->
            <div class="mb-4">
                <label for="from_name" class="form-label">Nome do Remetente *</label>
                <input
                    type="text"
                    name="from_name"
                    id="from_name"
                    class="form-input"
                    value="{{ old('from_name') }}"
                    placeholder="Nome do Remetente"
                    required
                >
                @error('from_name')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- E-mail do remetente -->
            <div class="mb-4">
                <label for="from_email" class="form-label">E-mail do Remetente *</label>
                <input
                    type="email"
                    name="from_email"
                    id="from_email"
                    class="form-input"
                    value="{{ old('from_email') }}"
                    placeholder="E-mail do Remetente"
                    required
                >
                @error('from_email')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantidade por requisição -->
            <div class="mb-4">
                <label for="send_quantity_per_request" class="form-label">
                    Quantidade por Requisição *
                </label>
                <input
                    type="number"
                    name="send_quantity_per_request"
                    id="send_quantity_per_request"
                    class="form-input"
                    value="{{ old('send_quantity_per_request') }}"
                    placeholder="Quantidade por Requisição"
                    required
                >
                @error('send_quantity_per_request')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantidade por hora -->
            <div class="mb-4">
                <label for="send_quantity_per_hour" class="form-label">
                    Quantidade por Hora *
                </label>
                <input
                    type="number"
                    name="send_quantity_per_hour"
                    id="send_quantity_per_hour"
                    class="form-input"
                    value="{{ old('send_quantity_per_hour') }}"
                    placeholder="Quantidade por Hora"
                    required
                >
                @error('send_quantity_per_hour')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Marketing -->
            <div class="mb-4">
                <label class="form-label">Envio Marketing *</label>
                <input
                    type="radio"
                    name="is_active_marketing"
                    value="1"
                    id="marketing_active"
                    {{ old('is_active_marketing') == '1' ? 'checked' : '' }}
                >
                <label for="marketing_active" class="form-input-checkbox">Ativo</label>

                <input
                    type="radio"
                    name="is_active_marketing"
                    value="0"
                    id="marketing_inactive"
                    {{ old('is_active_marketing') == '0' ? 'checked' : '' }}
                >
                <label for="marketing_inactive" class="form-input-checkbox">Inativo</label>

                @error('is_active_marketing')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Transacional -->
            <div class="mb-4">
                <label class="form-label">Envio Transacional *</label>
                <input
                    type="radio"
                    name="is_active_transactional"
                    value="1"
                    id="transactional_active"
                    {{ old('is_active_transactional') == '1' ? 'checked' : '' }}
                >
                <label for="transactional_active" class="form-input-checkbox">Ativo</label>

                <input
                    type="radio"
                    name="is_active_transactional"
                    value="0"
                    id="transactional_inactive"
                    {{ old('is_active_transactional') == '0' ? 'checked' : '' }}
                >
                <label for="transactional_inactive" class="form-input-checkbox">Inativo</label>

                @error('is_active_transactional')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <span class="required-field">* Campo obrigatório</span>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn-success-md align-icon-btn">
                    <x-lucide-plus-circle class="icon-btn" />
                    <span>Cadastrar</span>
                </button>
            </div>

        </form>
    </div>
@endsection