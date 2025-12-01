@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Configurações" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Perfil', 'url' => route('profile.show')],
        ['label' => 'Editar Foto'],
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

                <!-- Seção Editar Foto -->
                <div class="profile-section">
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Foto do Perfil</h3>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <!-- Preview da foto atual -->
                            <div class="mb-6 text-center">
                                <div class="relative inline-block">
                                    @if ($user->image)
                                        <img id="preview-image" src="{{ $user->image_url }}" alt="{{ $user->name }}"
                                            class="w-32 h-32 rounded-full object-cover shadow-lg ring-4 ring-gray-200 dark:ring-gray-600">
                                    @else
                                        <div id="preview-image"
                                            class="w-32 h-32 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-gray-600 dark:to-gray-700 
                                                                       rounded-full flex items-center justify-center shadow-lg ring-4 ring-gray-200 dark:ring-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <form action="{{ route('profile.update_image') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="image" class="form-label">Escolher Nova Foto *</label>
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="form-input" onchange="previewImageUpload()" required>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Formatos aceitos: JPG ou PNG. Tamanho máximo: 2MB.
                                    </p>
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
