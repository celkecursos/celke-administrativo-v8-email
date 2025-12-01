@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Configurações" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Perfil', 'url' => route('profile.show')],
        ['label' => 'Editar Senha'],
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

                <!-- Seção Editar Senha -->
                <div class="profile-section">
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Alterar Senha</h3>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <form action="{{ route('profile.update_password') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-6">
                                    <div class="form-group">
                                        <label for="current_password" class="form-label">Senha Atual *</label>
                                        <div class="relative">
                                            <input type="password" name="current_password" id="current_password"
                                                class="form-input pr-10" placeholder="Digite sua senha atual" required>
                                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2"
                                                onclick="togglePassword('current_password', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="form-label">Nova Senha *</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="password"
                                                class="form-input pr-10" placeholder="Senha com no mínimo 8 caracteres"
                                                value="{{ old('password') }}" required>
                                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2"
                                                onclick="togglePassword('password', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Requisitos da senha -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 mt-3 text-sm"
                                            id="password-requirements">
                                            <div id="req-number" class="text-gray-500 flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-gray-400"></span> Um número
                                            </div>
                                            <div id="req-uppercase" class="text-gray-500 flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-gray-400"></span> Uma letra maiúscula
                                            </div>
                                            <div id="req-length" class="text-gray-500 flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-gray-400"></span> Use de 8-50
                                                caracteres
                                            </div>
                                            <div id="req-special" class="text-gray-500 flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-gray-400"></span> Um símbolos: #%+:$@&
                                            </div>
                                            <div id="req-lowercase" class="text-gray-500 flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-gray-400"></span> Uma letra minúscula
                                            </div>
                                            <div id="req-latin" class="text-gray-500 flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-gray-400"></span> Apenas alfabeto
                                                latino
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha
                                            *</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation"
                                                id="password_confirmation" class="form-input pr-10"
                                                placeholder="Confirmar a nova senha"
                                                value="{{ old('password_confirmation') }}" required>
                                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2"
                                                onclick="togglePassword('password_confirmation', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <button type="submit" class="btn-warning-md align-icon-btn">                                            
                                            <x-lucide-save class="icon-btn" />
                                            <span>Salvar</span>
                                        </button>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">* Campo obrigatório</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
