@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Máquinas" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => 'Máquina'],
    ]" />

    <div class="content-box">

        <x-content-box-header title="Detalhes" :buttons="[
            [
                'label' => 'Máquinas',
                'url' => route('email-machines.index'),
                'permission' => 'index-email-machine',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
            [
                'can' => 'index-email-machine-sequence',
                'type' => 'link',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'class' => 'btn-info-md table-md-hidden',
                'icon' => 'lucide-list',
                'label' => 'Sequência',
            ],
            [
                'label' => 'Editar',
                'url' => route('email-machines.edit', ['emailMachine' => $emailMachine->id]),
                'permission' => 'edit-email-machine',
                'class' => 'btn-warning-md',
                'icon' => 'lucide-pencil',
            ],
            [
                'label' => 'Apagar',
                'url' => route('email-machines.destroy', ['emailMachine' => $emailMachine->id]),
                'permission' => 'destroy-email-machine',
                'class' => 'btn-danger-md',
                'icon' => 'lucide-trash',
                'method' => 'delete',
                'confirm' => true,
                'id' => $emailMachine->id,
            ],
        ]" />

        <x-alert />

        <div class="detail-box">

            <div class="mb-1">
                <span class="title-detail-content">ID: </span>
                <span class="detail-content">{{ $emailMachine->id }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Nome: </span>
                <span class="detail-content">{{ $emailMachine->name }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Status: </span>
                <span class="detail-content">
                    <span class="{{ $emailMachine->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                        {{ $emailMachine->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Cadastrado: </span>
                <span
                    class="detail-content">{{ \Carbon\Carbon::parse($emailMachine->created_at)->format('d/m/Y H:i:s') }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Editado: </span>
                <span
                    class="detail-content">{{ \Carbon\Carbon::parse($emailMachine->updated_at)->format('d/m/Y H:i:s') }}</span>
            </div>

        </div>

    </div>
@endsection
