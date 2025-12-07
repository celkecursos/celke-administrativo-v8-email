@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Editar E-mail - Datas" :items="[
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
        ['label' => 'Datas'],
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
                'url' => route('email-sequence-emails.show-dates', [
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

                <!-- Seção Atraso de Envio -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Atraso de Envio</h3>
                                    <p class="form-helper-text">Defina o tempo de espera antes de enviar este e-mail</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <form
                                action="{{ route('email-sequence-emails.update-delivery-delay', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="email_machine_sequence_id" value="{{ $sequence->id }}">
                                <!-- Preservar valores de data fixa -->
                                <input type="hidden" name="use_fixed_send_datetime"
                                    value="{{ $email->use_fixed_send_datetime ?? 0 }}">
                                <input type="hidden" name="fixed_send_datetime"
                                    value="{{ $email->fixed_send_datetime ? $email->fixed_send_datetime->format('Y-m-d H:i:s') : '' }}">
                                <!-- Preservar valores de janela de envio -->
                                <input type="hidden" name="send_window_start_hour"
                                    value="{{ $email->send_window_start_hour }}">
                                <input type="hidden" name="send_window_start_minute"
                                    value="{{ $email->send_window_start_minute }}">
                                <input type="hidden" name="send_window_end_hour"
                                    value="{{ $email->send_window_end_hour }}">
                                <input type="hidden" name="send_window_end_minute"
                                    value="{{ $email->send_window_end_minute }}">

                                <div class="three-inputs-per-line">
                                    <div>
                                        <label for="delay_day" class="form-label">Dias</label>
                                        <input type="number" name="delay_day" id="delay_day" class="form-input"
                                            placeholder="0" value="{{ old('delay_day', $email->delay_day) }}"
                                            min="0">
                                    </div>
                                    <div>
                                        <label for="delay_hour" class="form-label">Horas</label>
                                        <input type="number" name="delay_hour" id="delay_hour" class="form-input"
                                            placeholder="0" value="{{ old('delay_hour', $email->delay_hour) }}"
                                            min="0" max="23">
                                    </div>
                                    <div>
                                        <label for="delay_minute" class="form-label">Minutos</label>
                                        <input type="number" name="delay_minute" id="delay_minute" class="form-input"
                                            placeholder="0" value="{{ old('delay_minute', $email->delay_minute) }}"
                                            min="0" max="59">
                                    </div>
                                </div>

                                <button type="submit" class="btn-warning-md align-icon-btn">
                                    <x-lucide-save class="icon-btn" />
                                    <span>Salvar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Seção Data Fixa de Envio -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Data Fixa de Envio</h3>
                                    <p class="form-helper-text">Agendar envio para data e hora específica</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <form
                                action="{{ route('email-sequence-emails.update-fixed-shipping-date', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="email_machine_sequence_id" value="{{ $sequence->id }}">
                                <!-- Preservar valores de atraso -->
                                <input type="hidden" name="delay_day" value="{{ $email->delay_day ?? 0 }}">
                                <input type="hidden" name="delay_hour" value="{{ $email->delay_hour ?? 0 }}">
                                <input type="hidden" name="delay_minute" value="{{ $email->delay_minute ?? 0 }}">

                                <div class="mb-4">
                                    <label class="form-label mb-4">Usar data fixa de envio *</label>
                                    <input type="radio" name="use_fixed_send_datetime" value="1" id="use_fixed_send_datetime_true"
                                        {{ old('use_fixed_send_datetime', $email->use_fixed_send_datetime) == '1' ? 'checked' : '' }}>
                                    <label for="use_fixed_send_datetime_true" class="form-input-checkbox">Sim</label>
                                    <input type="radio" name="use_fixed_send_datetime" value="0" id="use_fixed_send_datetime_false"
                                        {{ old('use_fixed_send_datetime', $email->use_fixed_send_datetime) == '0' ? 'checked' : '' }}>
                                    <label for="use_fixed_send_datetime_false" class="form-input-checkbox">Não</label>
                                    @error('use_fixed_send_datetime')
                                        <p class="form-input-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="fixed_send_datetime" class="form-label">Data e Hora</label>
                                    <input type="datetime-local" name="fixed_send_datetime" id="fixed_send_datetime"
                                        class="form-input"
                                        value="{{ old('fixed_send_datetime', $email->fixed_send_datetime ? $email->fixed_send_datetime->format('Y-m-d\TH:i') : '') }}">
                                </div>

                                <button type="submit" class="btn-warning-md align-icon-btn">
                                    <x-lucide-save class="icon-btn" />
                                    <span>Salvar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Seção Janela de Envio -->
                <div class="profile-section">
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="sidebar-card-title">Janela de Envio</h3>
                                    <p class="form-helper-text">Defina o horário permitido para envio do e-mail</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4">
                            <form
                                action="{{ route('email-sequence-emails.update-submission-window', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id, 'email' => $email->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="email_machine_sequence_id" value="{{ $sequence->id }}">
                                <!-- Preservar valores de atraso -->
                                <input type="hidden" name="delay_day" value="{{ $email->delay_day ?? 0 }}">
                                <input type="hidden" name="delay_hour" value="{{ $email->delay_hour ?? 0 }}">
                                <input type="hidden" name="delay_minute" value="{{ $email->delay_minute ?? 0 }}">
                                <!-- Preservar valores de data fixa -->
                                <input type="hidden" name="use_fixed_send_datetime"
                                    value="{{ $email->use_fixed_send_datetime ?? 0 }}">
                                <input type="hidden" name="fixed_send_datetime"
                                    value="{{ $email->fixed_send_datetime ? $email->fixed_send_datetime->format('Y-m-d H:i:s') : '' }}">

                                <div class="mb-4">
                                    <label class="form-label">Horário Inicial</label>
                                    <div class="two-inputs-per-line">
                                        <div>
                                            <input type="number" name="send_window_start_hour" class="form-input"
                                                placeholder="Hora (0-23)"
                                                value="{{ old('send_window_start_hour', $email->send_window_start_hour) }}"
                                                min="0" max="23">
                                        </div>
                                        <div>
                                            <input type="number" name="send_window_start_minute" class="form-input"
                                                placeholder="Minuto (0-59)"
                                                value="{{ old('send_window_start_minute', $email->send_window_start_minute) }}"
                                                min="0" max="59">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Horário Final</label>
                                    <div class="two-inputs-per-line">
                                        <div>
                                            <input type="number" name="send_window_end_hour" class="form-input"
                                                placeholder="Hora (0-23)"
                                                value="{{ old('send_window_end_hour', $email->send_window_end_hour) }}"
                                                min="0" max="23">
                                        </div>
                                        <div>
                                            <input type="number" name="send_window_end_minute" class="form-input"
                                                placeholder="Minuto (0-59)"
                                                value="{{ old('send_window_end_minute', $email->send_window_end_minute) }}"
                                                min="0" max="59">
                                        </div>
                                    </div>
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
