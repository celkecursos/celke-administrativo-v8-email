@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Configurações" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Perfil', 'url' => route('profile.show')],
        ['label' => 'Configurações'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Meu Perfil" :buttons="[
            [
                'label' => 'Ver Perfil',
                'url' => route('profile.show'),
                'permission' => 'edit-profile',
                'class' => 'btn-primary-md align-icon-btn',
                'icon' => 'lucide-eye', // Ícone equivalente ao olho do Heroicons
            ],
        ]" />

        <!-- Layout Principal com Menu Lateral e Conteúdo -->
        <div class="form-group-grid-container">

            <!-- Menu Lateral de Configurações -->
            <x-form-sidebar-menu :items="[
                [
                    'label' => 'Editar',
                    'url' => route('profile.edit'),
                    'permission' => 'edit-profile',
                    'icon' => 'lucide-user',
                    'active' => request()->routeIs('profile.edit'),
                ],
                [
                    'label' => 'Editar Senha',
                    'url' => route('profile.edit_password'),
                    'permission' => 'edit-password-profile',
                    'icon' => 'lucide-lock',
                    'active' => request()->routeIs('profile.edit_password'),
                ],
                [
                    'label' => 'Editar Foto',
                    'url' => route('profile.edit_image'),
                    'permission' => 'edit-profile-image',
                    'icon' => 'lucide-image',
                    'active' => request()->routeIs('profile.edit_image'),
                ],
            ]" />

            <!-- Área de Conteúdo Principal -->
            <div class="profile-content-container">

                <!-- Seção Editar Perfil -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Editar Perfil</h3>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="name" class="form-label">Nome *</label>
                                    <input type="text" name="name" id="name" class="form-input"
                                        placeholder="Nome completo do usuário" value="{{ old('name', $user->name) }}"
                                        required>
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label">E-mail *</label>
                                    <input type="email" name="email" id="email" class="form-input"
                                        placeholder="Melhor e-mail do usuário" value="{{ old('email', $user->email) }}"
                                        required>
                                </div>

                                <div class="mb-4">
                                    <label for="cpf" class="form-label">CPF *</label>
                                    <input type="text" name="cpf" id="cpf" class="form-input"
                                        placeholder="CPF do usuário" value="{{ old('cpf', $user->cpf) }}">
                                </div>

                                <div class="mb-4">
                                    <label for="alias" class="form-label">Apelido</label>
                                    <input type="text" name="alias" id="alias" class="form-input"
                                        placeholder="Apelido do usuário" value="{{ old('alias', $user->alias) }}">
                                </div>

                                <div class="mb-4">
                                    <span class="required-field">* Campo obrigatório</span>
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
