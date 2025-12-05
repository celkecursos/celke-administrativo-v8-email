@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Máquinas" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => 'Máquina'],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Cadastrar" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('email-machines.index'),
                'permission' => 'index-email-machine',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
        ]" />

        <x-alert />

        <form action="{{ route('email-machines.store') }}" method="POST">
            @csrf
            @method('POST')

            <div class="mb-4">
                <label for="name" class="form-label">Nome *</label>
                <input type="text" name="name" id="name" class="form-input" placeholder="Nome do curso"
                    value="{{ old('name') }}" required>
                @error('name')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="name" class="form-label">Máquina Ativa</label>
                <input type="checkbox" id="is_active" name="is_active" value="1" class="form-input-checkbox"
                    {{ old('is_active') ? 'checked' : '' }}>
                @error('is_active')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <span class="required-field">* Campo obrigatório</span>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-success-md align-icon-btn">
                    <!-- Ícone plus-circle (Heroicons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Cadastrar</span>
                </button>
            </div>

        </form>

    </div>
@endsection
