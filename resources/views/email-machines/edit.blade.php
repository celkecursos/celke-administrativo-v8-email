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
                <label for="name" class="form-label">Nome</label>
                <input type="text" name="name" id="name" class="form-input" placeholder="Nome da máquina"
                    value="{{ old('name', $emailMachine->name) }}" required>
                @error('name')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="name" class="form-label">Máquina Ativa</label>
                <input type="checkbox" id="is_active" name="is_active" value="1" class="form-input-checkbox"
                    {{ old('is_active', $emailMachine->is_active) ? 'checked' : '' }}>
                @error('is_active')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-warning-md align-icon-btn">
                <!-- Ícone pencil-square (Heroicons) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                <span>Salvar</span>
            </button>

        </form>

    </div>
@endsection
