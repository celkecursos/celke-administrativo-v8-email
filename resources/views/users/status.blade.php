@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Usuários" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Usuários', 'url' => route('users.index')],
        ['label' => 'Usuário'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Detalhes do Usuário" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('users.index'),
                'permission' => 'index-user',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
        ]" />

        <!-- Layout Principal com Menu Lateral e Conteúdo -->
        <div class="form-group-grid-container">

            <!-- Menu Lateral Interno -->
            <x-form-sidebar-menu :items="[
                [
                    'label' => 'Dados do Usuário',
                    'url' => route('users.show', $user->id),
                    'icon' => 'lucide-user',
                ],
                [
                    'label' => 'E-mails Programados',
                    'url' => route('users.scheduled', $user->id),
                    'icon' => 'lucide-mail',
                ],
                [
                    'label' => 'E-mails Enviados',
                    'url' => route('users.sent', $user->id),
                    'icon' => 'lucide-send',
                ],
                [
                    'label' => 'E-mails Não Enviados',
                    'url' => route('users.failed', $user->id),
                    'icon' => 'lucide-alert-triangle',
                ],
                [
                    'label' => 'Editar Status Atual',
                    'url' => '#status',
                    'icon' => 'lucide-settings',
                    'active' => true,  // Item ativo nesta página
                ],
                [
                    'label' => 'Descadastros',
                    'url' => route('users.unsubscribed', $user->id),
                    'icon' => 'lucide-user-x',
                ],
            ]" />

            <!-- Área de Conteúdo Principal -->
            <div class="profile-content-container">

                <!-- Seção: Status Atual (formulário de edição) -->
                <div id="status" class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Editar Status do Usuário</h3>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('users.update-status', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="user_status_id" class="form-label">Status Atual</label>
                                    <select name="user_status_id" id="user_status_id" class="form-input" required>
                                        <option value="">Selecione o Status</option>
                                        @foreach (\App\Models\UserStatus::all() as $userStatus)
                                            <option value="{{ $userStatus->id }}"
                                                    {{ old('user_status_id', $user->user_status_id) == $userStatus->id ? 'selected' : '' }}
                                                    class="dark:bg-[var(--color-zinc-800)] dark:text-white">
                                                {{ $userStatus->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                                </div>
                            </form>
                            {{-- Nota: A lógica de exclusão de email_users (se status 5 ou 6) deve ser implementada no controller --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection