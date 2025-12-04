<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Celke</title>

    <script>
        // Executar logo no início, antes de carregar o CSS e evitar o piscar na tela
        (function() {

            // Verificar se o usuário já definiu um tema na localStorage
            const theme = localStorage.getItem('theme');

            // Verificar se o sistema do usuário está configurado para o tema escuro
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            // Se o usuário escolheu o tema 'dark' ou se não há tema definido e o sistema prefere o modo escuro, aplica o tema escuro
            if (theme === 'dark' || (!theme && prefersDark)) {
                // Adicionar a classe 'dark' ao elemento raiz (html), ativando o modo escuro no site
                document.documentElement.classList.add('dark');
            } else {
                // Caso contrário, remove a classe 'dark' e aplica o tema claro
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-dashboard">

    {{-- Exibir o loading --}}
    <div id="loadingScreen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <button id="toggleSidebar" class="menu-button">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="user-container">
                <div class="relative dropdown-button-border">
                    <!-- Ícone moon (Heroicons) -->
                    <button id="themeToggle" class="dropdown-button">
                        <svg id="iconMoon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                        <!-- Ícone sun (Heroicons) -->
                        <svg id="iconSun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                    </button>
                </div>
                <div class="relative">
                    <!-- Dropdown -->
                    <button id="userDropdownButton" class="dropdown-button">
                        <div class="flex items-center space-x-2">
                            <!-- Avatar placeholder ou ícone de usuário -->
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <!-- Nome do usuário -->
                            <div class="hidden sm:flex flex-col items-start">
                                <span
                                    class="text-sm font-medium">{{ explode(' ', Auth::user()->name ?? 'Usuário')[0] }}</span>
                                <span
                                    class="text-xs text-gray-500 user-email-truncate dark:text-gray-400">{{ Auth::user()->email ?? '' }}</span>
                            </div>
                        </div>
                        <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Conteúdo do Dropdown -->
                    <div id="dropdownContent" class="dropdown-content hidden">
                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ explode(' ', Auth::user()->name ?? 'Usuário')[0] }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 user-email-truncate">
                                {{ Auth::user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            Perfil
                        </a>
                        <a href="{{ route('logout') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            Sair
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-container">
                <button id="closeSidebar" class="sidebar-close-button">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="sidebar-header">
                    <span class="sidebar-title">
                        <span><img src="{{ asset('images/logo-define-500x500_v3.png') }}" alt="Logo"
                                class="logo-adm"></span>
                        <span class="pt-3">Celke</span>
                    </span>
                </div>
                <nav class="sidebar-nav">

                    @can('dashboard')
                        <!-- Ícone home (Heroicons) -->
                        <a @class([
                            'sidebar-link',
                            'active' => isset($menu) && $menu == 'dashboard',
                        ]) href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    @endcan

                    <!-- Dropdown Máquinas -->
                    <div x-data="{ open: {{ in_array($menu ?? '', ['email-machine']) ? 'true' : 'false' }} }" class="relative">
                        <button @click="open = !open"
                            class="sidebar-link w-full flex justify-between items-center cursor-pointer">
                            <span class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
                                </svg>

                                <span>Máquinas</span>
                            </span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open" x-transition class="ml-0 mt-1 space-y-1">

                            @can('index-email-machine')
                                <a @class([
                                    'sidebar-link item-dropdown',
                                    'active' => isset($menu) && $menu == 'users',
                                ]) href="{{ route('email-machines.index') }}">
                                    <!-- Ícone user-group (Heroicons) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                                    </svg>

                                    <span>Máquinas</span>
                                </a>
                            @endcan


                        </div>
                    </div>

                    <!-- Dropdown Usuários -->
                    <div x-data="{ open: {{ in_array($menu ?? '', ['users']) ? 'true' : 'false' }} }" class="relative">
                        <button @click="open = !open"
                            class="sidebar-link w-full flex justify-between items-center cursor-pointer">
                            <span class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                                <span>Usuários</span>
                            </span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open" x-transition class="ml-0 mt-1 space-y-1">
                            @can('index-user')
                                <a @class([
                                    'sidebar-link item-dropdown',
                                    'active' => isset($menu) && $menu == 'users',
                                ]) href="{{ route('users.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                    <span>Usuários</span>
                                </a>
                            @endcan
                        </div>
                    </div>

                    <!-- Dropdown Gestão de Contatos -->
                    <div x-data="{ open: {{ in_array($menu ?? '', ['email-tags']) ? 'true' : 'false' }} }" class="relative">
                        <button @click="open = !open"
                            class="sidebar-link w-full flex justify-between items-center cursor-pointer">
                            <span class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span>Gestão de Contatos</span>
                            </span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open" x-transition class="ml-0 mt-1 space-y-1">
                            @can('index-email-tag')
                                <a @class([
                                    'sidebar-link item-dropdown',
                                    'active' => isset($menu) && $menu == 'email-tags',
                                ]) href="{{ route('email-tags.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                    </svg>
                                    <span>Tag</span>
                                </a>
                            @endcan
                        </div>
                    </div>

                    <a href="{{ route('logout') }}" class="sidebar-link">
                        <!-- Ícone arrow-right-circle (Heroicons) -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>Sair</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Conteúdo Principal -->
        <main class="main-content">
            @yield('content')
        </main>

    </div>

    <script>
        window.addEventListener('load', function() {
            const loading = document.getElementById('loadingScreen');
            if (loading) {
                loading.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loading = document.getElementById('loadingScreen');

            if (form && loading) {
                form.addEventListener('submit', function() {
                    loading.style.display = 'flex';
                });
            }
        });
    </script>

</body>

</html>
