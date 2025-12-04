@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Visualizar Tag" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Tags', 'url' => route('email-tags.index')],
        ['label' => 'Visualizar Tag'],
    ]" />

    <div class="content-box">

        <x-content-box-header title="Visualizar Tag" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('email-tags.index'),
                'permission' => 'index-email-tag',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
            [
                'label' => 'Editar',
                'url' => route('email-tags.edit', ['emailTag' => $emailTag->id]),
                'permission' => 'edit-email-tag',
                'class' => 'btn-warning-md',
                'icon' => 'lucide-pencil',
            ],
            [
                'label' => 'Apagar',
                'url' => route('email-tags.destroy', ['emailTag' => $emailTag->id]),
                'permission' => 'destroy-email-tag',
                'class' => 'btn-danger-md',
                'icon' => 'lucide-trash',
                'method' => 'delete',
                'confirm' => true,
                'id' => $emailTag->id,
            ],
        ]" />

        <x-alert />

        <div class="detail-box">

            <div class="mb-1">
                <span class="title-detail-content">ID: </span>
                <span class="detail-content">{{ $emailTag->id }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Nome: </span>
                <span class="detail-content">{{ $emailTag->name }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Status: </span>
                <span class="detail-content">
                    <span class="{{ $emailTag->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                        {{ $emailTag->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Cadastrado: </span>
                <span class="detail-content">{{ \Carbon\Carbon::parse($emailTag->created_at)->format('d/m/Y H:i:s') }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Editado: </span>
                <span class="detail-content">{{ \Carbon\Carbon::parse($emailTag->updated_at)->format('d/m/Y H:i:s') }}</span>
            </div>

        </div>

    </div>
@endsection
