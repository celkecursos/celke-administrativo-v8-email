@extends('layouts.admin')

@section('content')

    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Sequências" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => 'Cadastrar Sequência'],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Cadastrar Sequência" :buttons="[
            [
                'label' => 'Sequências',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'permission' => 'index-email-machine-sequence',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
        ]" />

        <x-alert />

        <form action="{{ route('email-machine-sequences.store', ['emailMachine' => $emailMachine->id]) }}" method="POST">
            @csrf
            @method('POST')

            <input type="hidden" name="email_machine_id" value="{{ $emailMachine->id }}">

            <div class="mb-4">
                <label for="name" class="form-label">Nome*</label>
                <input type="text" name="name" id="name" class="form-input" placeholder="Nome da sequência"
                    value="{{ old('name') }}" required>
                @error('name')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="form-label mb-4">Situação *</label>
                <input type="radio" name="is_active" value="1" id="is_active_true"
                    {{ old('is_active') == '1' ? 'checked' : '' }}>
                <label for="is_active_true" class="form-input-checkbox">Ativo</label>
                <input type="radio" name="is_active" value="0" id="is_active_false"
                    {{ old('is_active') == '0' ? 'checked' : '' }}>
                <label for="is_active_false" class="form-input-checkbox">Inativo</label>
                @error('is_active')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="btn-success-md align-icon-btn">
                    <x-lucide-plus-circle class="icon-btn" />
                    <span>Cadastrar</span>
                </button>
            </div>

        </form>

    </div>
@endsection
