@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Editar E-mail - Configuração" :items="[
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
        ['label' => 'Configuração'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Editar E-mail da Sequência" :buttons="[
            [
                'label' => 'Sequências',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'permission' => 'index-email-machine-sequence',
                'class' => 'btn-info-md align-icon-btn',
                'icon' => 'lucide-list',
            ],
            [
                'label' => 'Visualizar',
                'url' => route('email-sequence-emails.show-config', [
                    'emailMachine' => $emailMachine->id,
                    'sequence' => $sequence->id,
                    'email' => $email->id,
                ]),
                'permission' => 'edit-email-sequence-email',
                'class' => 'btn-primary align-icon-btn',
                'icon' => 'lucide-eye',
            ],
        ]" />

        <!-- Layout Principal com Menu Lateral e Conteúdo -->
        <div class="form-group-grid-container">

            <!-- Menu Lateral de Configurações -->
            <x-form-sidebar-menu :items="[
                [
                    'label' => 'Conteúdo',
                    'url' => route('email-sequence-emails.edit', [
                        'emailMachine' => $emailMachine->id,
                        'sequence' => $sequence->id,
                        'email' => $email->id,
                    ]),
                    'permission' => 'edit-email-sequence-email',
                    'icon' => 'lucide-file-text',
                    'active' => request()->routeIs('email-sequence-emails.edit'),
                ],
                [
                    'label' => 'Datas',
                    'url' => route('email-sequence-emails.edit-dates', [
                        'emailMachine' => $emailMachine->id,
                        'sequence' => $sequence->id,
                        'email' => $email->id,
                    ]),
                    'permission' => 'edit-email-sequence-email',
                    'icon' => 'lucide-calendar-clock',
                    'active' => request()->routeIs('email-sequence-emails.edit-dates'),
                ],
                [
                    'label' => 'Configuração',
                    'url' => route('email-sequence-emails.edit-config', [
                        'emailMachine' => $emailMachine->id,
                        'sequence' => $sequence->id,
                        'email' => $email->id,
                    ]),
                    'permission' => 'edit-email-sequence-email',
                    'icon' => 'lucide-settings',
                    'active' => request()->routeIs('email-sequence-emails.edit-config'),
                ],
            ]" />

            <!-- Área de Conteúdo Principal -->
            <div class="profile-content-container">

                <!-- Seção Configuração -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Configuração do E-mail</h3>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <form
                                action="{{ route('email-sequence-emails.update-config', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="email_machine_sequence_id" value="{{ $sequence->id }}">

                                <div class="mb-4">
                                    <label class="form-label">Situação *</label>
                                    <input type="radio" name="is_active" value="1" id="is_active_true"
                                        {{ old('is_active', $email->is_active) == '1' ? 'checked' : '' }}>
                                    <label for="is_active_true" class="form-input-checkbox">Ativo</label>
                                    <input type="radio" name="is_active" value="0" id="is_active_false"
                                        {{ old('is_active', $email->is_active) == '0' ? 'checked' : '' }}>
                                    <label for="is_active_false" class="form-input-checkbox">Inativo</label>
                                    <p class="form-helper-text">
                                        Define se este e-mail está ativo e pode ser enviado
                                    </p>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Pular este e-mail</label>
                                    <input type="radio" name="skip_email" value="1" id="skip_email_true"
                                        {{ old('skip_email', $email->skip_email) == '1' ? 'checked' : '' }}>
                                    <label for="skip_email_true" class="form-input-checkbox">Sim</label>
                                    <input type="radio" name="skip_email" value="0" id="skip_email_false"
                                        {{ old('skip_email', $email->skip_email) == '0' ? 'checked' : '' }}>
                                    <label for="skip_email_false" class="form-input-checkbox">Não</label>
                                    <p class="form-helper-text">
                                        Quando marcado, este e-mail será ignorado no fluxo da sequência
                                    </p>
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
