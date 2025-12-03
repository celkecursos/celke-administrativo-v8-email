@extends('layouts.admin')

@section('content')

    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Sequências" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => $sequence->name],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Visualizar Sequência" :buttons="[
            [
                'label' => 'Sequências',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'permission' => 'index-email-machine-sequence',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
            [
                'label' => 'Editar',
                'url' => route('email-machine-sequences.edit', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id]),
                'permission' => 'edit-email-machine-sequence',
                'class' => 'btn-warning-md',
                'icon' => 'lucide-pencil',
            ],
        ]" />

        <x-alert />

        <div class="content-box-body">
            <div class="detail-box space-y-4">
                
                <div>
                    <span class="title-detail-content">ID: </span>
                    <span class="detail-content">{{ $sequence->id }}</span>
                </div>

                <div>
                    <span class="title-detail-content">Máquina: </span>
                    <span class="detail-content">{{ $emailMachine->name }}</span>
                </div>

                <div>
                    <span class="title-detail-content">Nome: </span>
                    <span class="detail-content">{{ $sequence->name }}</span>
                </div>

                <div>
                    <span class="title-detail-content">Situação: </span>
                    <span class="{{ $sequence->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                        {{ $sequence->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div>
                    <span class="title-detail-content">Cadastrado: </span>
                    <span class="detail-content">
                        {{ \Carbon\Carbon::parse($sequence->created_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                    </span>
                </div>

                <div>
                    <span class="title-detail-content">Editado: </span>
                    <span class="detail-content">
                        {{ \Carbon\Carbon::parse($sequence->updated_at)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                    </span>
                </div>

            </div>
        </div>

    </div>
@endsection
