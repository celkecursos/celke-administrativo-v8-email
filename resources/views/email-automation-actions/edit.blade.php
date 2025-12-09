@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Editar Ação Automatizada" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Ações Automatizadas', 'url' => route('email-automation-actions.index')],
        ['label' => 'Editar Ação Automatizada'],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Editar" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('email-automation-actions.index'),
                'permission' => 'index-email-automation-action',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
            [
                'label' => 'Visualizar',
                'url' => route('email-automation-actions.show', ['emailAutomationAction' => $emailAutomationAction->id]),
                'permission' => 'show-email-automation-action',
                'class' => 'btn-primary-md',
                'icon' => 'lucide-eye',
            ],
        ]" />

        <x-alert />

        <form action="{{ route('email-automation-actions.update', ['emailAutomationAction' => $emailAutomationAction->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="form-label">Nome *</label>
                <input type="text" name="name" id="name" class="form-input"
                    placeholder="Digite o nome da ação" value="{{ old('name', $emailAutomationAction->name) }}" required>
                @error('name')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Recursivo -->
            <div class="mb-4">
                <label class="form-label mb-4">Recursivo *</label>
                <input type="radio" name="is_recursive" value="1" id="is_recursive_true"
                    {{ old('is_recursive', $emailAutomationAction->is_recursive) == '1' ? 'checked' : '' }}>
                <label for="is_recursive_true" class="form-input-checkbox">Sim</label>
                <input type="radio" name="is_recursive" value="0" id="is_recursive_false"
                    {{ old('is_recursive', $emailAutomationAction->is_recursive) == '0' ? 'checked' : '' }}>
                <label for="is_recursive_false" class="form-input-checkbox">Não</label>
                @error('is_recursive')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="form-label mb-4">Situação *</label>
                <input type="radio" name="is_active" value="1" id="is_active_true"
                    {{ old('is_active', $emailAutomationAction->is_active) == '1' ? 'checked' : '' }}>
                <label for="is_active_true" class="form-input-checkbox">Ativo</label>
                <input type="radio" name="is_active" value="0" id="is_active_false"
                    {{ old('is_active', $emailAutomationAction->is_active) == '0' ? 'checked' : '' }}>
                <label for="is_active_false" class="form-input-checkbox">Inativo</label>
                @error('is_active')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <span class="required-field">* Campo obrigatório</span>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn-warning-md align-icon-btn">
                    <x-lucide-save class="icon-btn" />
                    <span>Salvar</span>
                </button>
            </div>

        </form>

    </div>
@endsection
