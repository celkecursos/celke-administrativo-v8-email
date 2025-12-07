@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Máquinas" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => 'Máquina'],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Editar" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('email-machines.index'),
                'permission' => 'index-email-machine',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
            [
                'label' => 'Visualizar',
                'url' => route('email-machines.show', ['emailMachine' => $emailMachine->id]),
                'permission' => 'show-email-machine',
                'class' => 'btn-primary-md',
                'icon' => 'lucide-eye',
            ],
        ]" />

        <x-alert />

        <form action="{{ route('email-machines.update', ['emailMachine' => $emailMachine->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="form-label">Nome *</label>
                <input type="text" name="name" id="name" class="form-input" placeholder="Nome da máquina"
                    value="{{ old('name', $emailMachine->name) }}" required>
                @error('name')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="form-label mb-4">Situação *</label>
                <input type="radio" name="is_active" value="1" id="is_active_true"
                    {{ old('is_active', $emailMachine->is_active) == '1' ? 'checked' : '' }}>
                <label for="is_active_true" class="form-input-checkbox">Ativo</label>
                <input type="radio" name="is_active" value="0" id="is_active_false"
                    {{ old('is_active', $emailMachine->is_active) == '0' ? 'checked' : '' }}>
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
