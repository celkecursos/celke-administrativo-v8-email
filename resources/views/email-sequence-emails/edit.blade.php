@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Editar E-mail" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => $sequence->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => 'Editar E-mail'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Editar E-mail da Sequência" :buttons="[
            [
                'label' => 'Sequências',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'permission' => 'index-email-machine-sequence',
                'class' => 'btn-info-md align-icon-btn',
                'icon' => 'lucide-arrow-left',
            ],
        ]" />

        <!-- Layout Principal com Menu Lateral e Conteúdo -->
        <div class="form-group-grid-container">

            <!-- Menu Lateral de Configurações -->
            <x-form-sidebar-menu :items="[
                [
                    'label' => 'Conteúdo',
                    'url' => route('email-sequence-emails.edit', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
                    'permission' => 'edit-email-sequence-email',
                    'icon' => 'lucide-file-text',
                    'active' => request()->routeIs('email-sequence-emails.edit'),
                ],
                [
                    'label' => 'Datas',
                    'url' => route('email-sequence-emails.edit-dates', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
                    'permission' => 'edit-email-sequence-email',
                    'icon' => 'lucide-calendar-clock',
                    'active' => request()->routeIs('email-sequence-emails.edit-dates'),
                ],
                [
                    'label' => 'Configuração',
                    'url' => route('email-sequence-emails.edit-config', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]),
                    'permission' => 'edit-email-sequence-email',
                    'icon' => 'lucide-settings',
                    'active' => request()->routeIs('email-sequence-emails.edit-config'),
                ],
            ]" />

            <!-- Área de Conteúdo Principal -->
            <div class="profile-content-container">

                <!-- Seção Conteúdo do E-mail -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Conteúdo do E-mail</h3>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <form action="{{ route('email-sequence-emails.update', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="email_machine_sequence_id" value="{{ $sequence->id }}">

                                <div class="mb-4">
                                    <label for="title" class="form-label">Título do E-mail *</label>
                                    <input type="text" name="title" id="title" class="form-input"
                                        placeholder="Título do e-mail" value="{{ old('title', $email->title) }}"
                                        required>
                                </div>

                                <div class="mb-4">
                                    <label for="content" class="form-label">Conteúdo do E-mail *</label>
                                    <textarea name="content" id="content" rows="10" class="form-input"
                                        placeholder="Digite o conteúdo do e-mail..." required>{{ old('content', $email->content) }}</textarea>
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
