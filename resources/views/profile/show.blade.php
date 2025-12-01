@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Perfil" :items="[['label' => 'Dashboard', 'url' => route('dashboard.index')], ['label' => 'Perfil']]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Meu Perfil" :buttons="[
            [
                'label' => 'Editar',
                'url' => route('profile.edit'),
                'permission' => 'edit-profile',
                'class' => 'btn-warning-md',
                'icon' => 'lucide-pencil',
            ],
        ]" />

        <!-- Profile Layout -->
        <div class="profile-grid">

            <!-- 1ª Parte - Left Column - Profile Image, Name and About Me -->
            <div class="profile-left">

                <!-- Profile Image and Stats Section -->
                <div class="profile-card">

                    <!-- Profile Image Section -->
                    <div class="profile-image-wrapper">
                        <div class="profile-image-container">

                            @if ($user->image)
                                <img src="{{ $user->image_url }}" alt="{{ $user->name }}" class="profile-image">
                            @else
                                <!-- Default user icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            @endif

                            <!-- Edit image icon -->
                            <a href="{{ route('profile.edit_image') }}" class="profile-image-edit">
                                <!-- Camera icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                            </a>

                        </div>
                    </div>

                    <!-- User Name with Email Verification Badge -->
                    <div class="profile-username-wrapper">
                        <div class="profile-username-content">
                            <h2 class="profile-username">{{ $user->name }}</h2>
                            @if ($user->email_verified_at)
                                <!-- Verified badge -->
                                <div class="badge-success align-icon-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="w-3 h-3 mr-1">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.236 4.53L7.71 10.183a.75.75 0 0 0-1.32.75l2.065 3.645a.75.75 0 0 0 1.267.086l4.134-5.791Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Verificado
                                </div>
                            @endif
                        </div>
                        {{-- <p class="text-sm text-gray-600 dark:text-gray-400">Web Designer & Developer</p> --}}
                    </div>

                    <!-- User Stats -->
                    {{-- <div class="profile-stats-grid">
                        <div class="profile-stat-card">
                            <div class="profile-stat-value">10</div>
                            <div class="profile-stat-label">Posts</div>
                        </div>
                        <div class="profile-stat-card">
                            <div class="profile-stat-value">3.4k</div>
                            <div class="profile-stat-label">Seguidores</div>
                        </div>
                        <div class="profile-stat-card">
                            <div class="profile-stat-value">1k</div>
                            <div class="profile-stat-label">Seguindo</div>
                        </div>
                    </div> --}}

                    <!-- Lessons Viewed Count -->
                    <div class="profile-box">
                        <div class="profile-value">31</div>
                        <div class="profile-label">aulas visualizadas</div>
                    </div>
                </div>


                <!-- About Me Section -->
                <div class="about-me-card">
                    <div class="about-me-card-content">
                        <h3>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            Sobre Mim
                        </h3>
                    </div>

                    <div class="space-y-2">
                        <div class="about-item">
                            <span class="about-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                                Apelido
                            </span>
                            <span class="about-value">{{ $user->alias ?? 'Não informado' }}</span>
                        </div>

                        <div class="about-item">
                            <span class="about-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                Email
                            </span>
                            <span class="about-value truncate ml-2">{{ $user->email }}</span>
                        </div>

                        {{-- <div class="about-item">
                            <span class="about-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                                Contato
                            </span>
                            <span class="about-value">(84) 987654321</span>
                        </div> --}}

                        <div class="about-item">
                            <span class="about-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Z" />
                                </svg>
                                CPF
                            </span>
                            <span class="about-value">{{ $user->cpf_formatted ?? 'Não informado' }}</span>
                        </div>

                        <div class="about-item">
                            <span class="about-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                Localização
                            </span>
                            <span class="about-value">São Paulo, Brasil (BR)</span>
                        </div>

                        <div class="about-item">
                            <span class="about-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3s-4.5 4.03-4.5 9 2.015 9 4.5 9Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                Website
                            </span>
                            <span class="about-blue">www.exemplo.com</span>
                        </div>

                        <div class="flex justify-between items-center py-2">
                            <span class="about-label">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                                </svg>
                                Github
                            </span>
                            <span class="about-blue">github.com</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 2ª Parte - Right Column - Last Lessons and Suggested Lesson -->
            <div class="right-column">

                <!-- Last Lessons Viewed -->
                <div class="right-card">
                    <h3 class="right-card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="icon-blue">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        Cadastrados
                    </h3>


                </div>

                <!-- Suggested Next Lesson -->
                <div class="right-card">
                    <h3 class="right-card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="icon-orange">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m4.5 0a12.078 12.078 0 0 0 2.887-.87C18.888 18.843 20.25 17.688 20.25 16.312V15.25m-2.25 2.062a4.5 4.5 0 1 0-9 0v1.312c0 .317-.21.692-.586.985a12.678 12.678 0 0 1-4.5 0" />
                        </svg>
                        Próxima Aula Sugerida
                    </h3>


                </div>

            </div>
        </div>
    </div>
@endsection
