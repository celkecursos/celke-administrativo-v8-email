@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Editar Gatilho de Automação" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Ações Automatizadas', 'url' => route('email-automation-actions.index')],
        ['label' => $emailAutomationTrigger->automationAction->name ?? 'Ação',
         'url'   => route('email-automation-actions.show', $emailAutomationTrigger->automationAction)],
        ['label' => 'Gatilho #' . $emailAutomationTrigger->id,
         'url'   => route('email-automation-triggers.show', $emailAutomationTrigger)],
        ['label' => 'Editar'],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Editar Gatilho" :buttons="[
            [
                'label' => 'Visualizar',
                'url' => route('email-automation-triggers.show', $emailAutomationTrigger),
                'class' => 'btn-primary-md',
                'icon'  => 'lucide-eye',
                'permission' => 'show-email-automation-trigger',
            ],
        ]" />

        <x-alert />

        <form action="{{ route('email-automation-triggers.update', $emailAutomationTrigger) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Status -->
            <div class="mb-4">
                <label class="form-label mb-4">Situação *</label>
                <input type="radio" name="is_active" value="1" id="is_active_true"
                    {{ old('is_active', $emailAutomationTrigger->is_active) == '1' ? 'checked' : '' }}>
                <label for="is_active_true" class="form-input-checkbox">Ativo</label>

                <input type="radio" name="is_active" value="0" id="is_active_false"
                    {{ old('is_active', $emailAutomationTrigger->is_active) == '0' ? 'checked' : '' }}>
                <label for="is_active_false" class="form-input-checkbox">Inativo</label>
                @error('is_active')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo de Filtro -->
            <div class="mb-4">
                <label for="email_filter_type_id" class="form-label">Tipo de Filtro *</label>
                <select name="email_filter_type_id" id="email_filter_type_id" class="form-select" required>
                    <option value="">Selecione o tipo de filtro...</option>
                    @foreach(\App\Models\EmailFilterType::orderBy('name')->get() as $type)
                        <option value="{{ $type->id }}"
                            {{ old('email_filter_type_id', $emailAutomationTrigger->email_filter_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('email_filter_type_id')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo de Ação -->
            <div class="mb-4">
                <label for="email_action_type_id" class="form-label">Tipo de Ação *</label>
                <select name="email_action_type_id" id="email_action_type_id" class="form-select" required>
                    <option value="">Selecione o tipo de ação...</option>
                    @foreach(\App\Models\EmailActionType::orderBy('name')->get() as $type)
                        <option value="{{ $type->id }}"
                            {{ old('email_action_type_id', $emailAutomationTrigger->email_action_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('email_action_type_id')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <span class="required-field">* Campo obrigatório</span>
            </div>

            <div class="mt-4 flex items-center space-x-2">
                <button type="submit" class="btn-warning-md align-icon-btn">
                    <x-lucide-save class="icon-btn" />
                    <span>Salvar Alterações</span>
                </button>

                <a href="{{ route('email-automation-triggers.show', $emailAutomationTrigger) }}"
                class="btn-secondary-md align-icon-btn">
                    <x-lucide-x class="icon-btn" />
                    <span>Cancelar</span>
                </a>
            </div>
        </form>
    </div>
@endsection