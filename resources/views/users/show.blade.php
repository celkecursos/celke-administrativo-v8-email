@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Usuários" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Usuários', 'url' => route('users.index')],
        ['label' => 'Usuário'],
    ]" />

    <div class="content-box">

        <x-content-box-header title="Detalhes do Usuário" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('users.index'),
                'permission' => 'index-user',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
        ]" />

        <x-alert />

        <div class="content-box-body">
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
@endsection
