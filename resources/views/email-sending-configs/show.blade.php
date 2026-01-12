@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Visualizar Servidor" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Servidores', 'url' => route('email-sending-configs.index')],
        ['label' => 'Visualizar Servidor'],
    ]" />

    <div class="content-box">

        <x-content-box-header title="Visualizar Servidor" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('email-sending-configs.index'),
                'permission' => 'index-email-sending-config',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
            [
                'label' => 'Editar',
                'url' => route('email-sending-configs.edit', ['emailSendingConfig' => $emailSendingConfig->id]),
                'permission' => 'edit-email-sending-config',
                'class' => 'btn-warning-md',
                'icon' => 'lucide-pencil',
            ],
            [
                'label' => 'Apagar',
                'url' => route('email-sending-configs.destroy', ['emailSendingConfig' => $emailSendingConfig->id]),
                'permission' => 'destroy-email-sending-config',
                'class' => 'btn-danger-md',
                'icon' => 'lucide-trash',
                'method' => 'delete',
                'confirm' => true,
                'id' => $emailSendingConfig->id,
            ],
        ]" />

        <x-alert />

        <div class="detail-box">

            <div class="mb-1">
                <span class="title-detail-content">ID: </span>
                <span class="detail-content">{{ $emailSendingConfig->id }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Provedor: </span>
                <span class="detail-content">{{ $emailSendingConfig->provider }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Remetente: </span>
                <span class="detail-content">
                    {{ $emailSendingConfig->from_name }} <br>
                    <small>{{ $emailSendingConfig->from_email }}</small>
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Envios por requisição: </span>
                <span class="detail-content">{{ $emailSendingConfig->send_quantity_per_request }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Envios por hora: </span>
                <span class="detail-content">{{ $emailSendingConfig->send_quantity_per_hour }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Marketing: </span>
                <span class="detail-content">
                    <span class="{{ $emailSendingConfig->is_active_marketing ? 'badge badge-success' : 'badge badge-danger' }}">
                        {{ $emailSendingConfig->is_active_marketing ? 'Ativo' : 'Inativo' }}
                    </span>
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Transacional: </span>
                <span class="detail-content">
                    <span class="{{ $emailSendingConfig->is_active_transactional ? 'badge badge-success' : 'badge badge-danger' }}">
                        {{ $emailSendingConfig->is_active_transactional ? 'Ativo' : 'Inativo' }}
                    </span>
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Cadastrado em: </span>
                <span class="detail-content">
                    {{ \Carbon\Carbon::parse($emailSendingConfig->created_at)->format('d/m/Y H:i:s') }}
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Atualizado em: </span>
                <span class="detail-content">
                    {{ \Carbon\Carbon::parse($emailSendingConfig->updated_at)->format('d/m/Y H:i:s') }}
                </span>
            </div>

        </div>

    </div>
@endsection