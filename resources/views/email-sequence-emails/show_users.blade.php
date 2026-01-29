@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Visualizar E-mail - Usuários" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        [
            'label' => $emailMachine->name,
            'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
        ],
        [
            'label' => $sequence->name,
            'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
        ],
        ['label' => 'Usuários'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Visualizar E-mail da Sequência" :buttons="[
            [
                'label' => 'Sequências',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'permission' => 'index-email-machine-sequence',
                'class' => 'btn-info-md align-icon-btn',
                'icon' => 'lucide-list',
            ],
        ]" />

        <!-- Layout Principal com Menu Lateral e Conteúdo -->
        <div class="form-group-grid-container">

            <!-- Menu Lateral de Visualização -->
            <x-form-sidebar-menu :items="[
                [
                    'label' => 'Conteúdo',
                    'url' => route('email-sequence-emails.show', [
                        'emailMachine' => $emailMachine->id,
                        'sequence' => $sequence->id,
                        'email' => $email->id,
                    ]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-file-text',
                    'active' => request()->routeIs('email-sequence-emails.show'),
                ],
                [
                    'label' => 'Datas',
                    'url' => route('email-sequence-emails.show-dates', [
                        'emailMachine' => $emailMachine->id,
                        'sequence' => $sequence->id,
                        'email' => $email->id,
                    ]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-calendar-clock',
                    'active' => request()->routeIs('email-sequence-emails.show-dates'),
                ],
                [
                    'label' => 'Configuração',
                    'url' => route('email-sequence-emails.show-config', [
                        'emailMachine' => $emailMachine->id,
                        'sequence' => $sequence->id,
                        'email' => $email->id,
                    ]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-settings',
                    'active' => request()->routeIs('email-sequence-emails.show-config'),
                ],
                [
                    'label' => 'Usuários',
                    'url' => route('email-sequence-emails.show-users', [
                        'emailMachine' => $emailMachine->id,
                        'sequence' => $sequence->id,
                        'email' => $email->id,
                    ]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-users',
                    'active' => request()->routeIs('email-sequence-emails.show-users'),
                ],
                [
                    'label' => 'E-mails Enviados',
                    'url' => route('email-sequence-emails.show-sent', [
                        'emailMachine' => $emailMachine->id,
                        'sequence' => $sequence->id,
                        'email' => $email->id,
                    ]),
                    'permission' => 'show-email-sequence-email',
                    'icon' => 'lucide-send',
                    'active' => request()->routeIs('email-sequence-emails.show-sent'),
                ],
            ]" />

            <!-- Área de Conteúdo Principal -->
            <div class="profile-content-container">

                <!-- Seção Usuários Vinculados -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Usuários Programados</h3>
                                    <p class="form-helper-text">
                                        Lista de usuários que receberão este e-mail
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            @forelse($emailUsers as $emailUser)
                                <div class="mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="title-detail-content">Usuário: </span>
                                                <span class="detail-content">{{ $emailUser->user->name }}</span>
                                            </div>
                                            <span
                                                class="{{ $emailUser->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                                {{ $emailUser->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </div>

                                        <div>
                                            <span class="title-detail-content">E-mail: </span>
                                            <span class="detail-content"><a
                                                    href='{{ route('users.show', $emailUser->user->id) }}'>{{ $emailUser->user->email }}</a></span>
                                        </div>

                                        @if ($emailUser->scheduled_send_date)
                                            <div>
                                                <span class="title-detail-content">Envio Agendado: </span>
                                                <span class="detail-content">
                                                    {{ \Carbon\Carbon::parse($emailUser->scheduled_send_date)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($emailUser->sent_date)
                                            <div>
                                                <span class="title-detail-content">Data de Envio: </span>
                                                <span class="detail-content">
                                                    {{ \Carbon\Carbon::parse($emailUser->sent_date)->tz('America/Sao_Paulo')->format('d/m/Y H:i:s') }}
                                                </span>
                                            </div>
                                        @endif

                                        <div>
                                            <span class="title-detail-content">Status de Envio: </span>
                                            @if ($emailUser->sent_date)
                                                <span class="badge badge-success">Enviado</span>
                                            @else
                                                <span class="badge badge-warning">Pendente</span>
                                            @endif
                                        </div>

                                        <!-- Botões de Ação -->
                                        <span class="title-detail-content">Alterar Status do E-mail: </span>
                                        <div class="flex items-center space-x-2 pt-2">
                                            @can('edit-email-user')
                                                <form action="{{ route('email-users.toggle-status', $emailUser->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-warning-md align-icon-btn"
                                                        title="{{ $emailUser->is_active ? 'Desativar' : 'Ativar' }}">
                                                        @if ($emailUser->is_active)
                                                            <x-lucide-toggle-right class="icon-btn" />
                                                        @else
                                                            <x-lucide-toggle-left class="icon-btn" />
                                                        @endif
                                                        <span>{{ $emailUser->is_active ? 'Desativar' : 'Ativar' }}</span>
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('destroy-email-user')
                                                <form id="delete-form-{{ $emailUser->id }}"
                                                    action="{{ route('email-users.destroy', $emailUser->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete({{ $emailUser->id }})"
                                                        class="btn-danger-md align-icon-btn" title="Apagar">
                                                        <x-lucide-trash class="icon-btn" />
                                                        <span>Apagar</span>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>

                                        <!-- Novos botões para alterar status do usuário (abaixo) -->
                                        @can('edit-email-user')
                                            <span class="title-detail-content">Alterar Status do Usuário: </span>
                                            <div class="flex items-center space-x-2 pt-2">
                                                @foreach (\App\Models\UserStatus::all() as $userStatus)
                                                    <form
                                                        action="{{ route('email-sequence-emails.update-user-status', [
                                                            'emailMachine' => $emailMachine->id,
                                                            'sequence' => $sequence->id,
                                                            'email' => $email->id,
                                                            'user' => $emailUser->user->id,
                                                            'status' => $userStatus->id,
                                                        ]) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn-warning-md align-icon-btn"
                                                            title="Alterar para {{ $userStatus->name }}">
                                                            <x-lucide-edit class="icon-btn" />
                                                            <span>{{ $userStatus->name }}</span>
                                                        </button>
                                                    </form>
                                                @endforeach
                                            </div>
                                            <p class="form-helper-text">
                                                Clicar no botão Inativo, Spam ou Descadastrado será alterado o status do usuário e removido os e-mail programados para o envio.
                                            </p>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <div class="alert-warning">
                                    <x-lucide-alert-circle class="w-5 h-5 inline-block mr-2" />
                                    Nenhum usuário programado para receber este e-mail.
                                </div>
                            @endforelse

                            <!-- Paginação -->
                            @if ($emailUsers->hasPages())
                                <div class="mt-6">
                                    {{ $emailUsers->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
