@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Editar Tag" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Tags', 'url' => route('email-tags.index')],
        ['label' => 'Editar Tag'],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Editar" :buttons="[
            [
                'label' => 'Listar',
                'url' => route('email-tags.index'),
                'permission' => 'index-email-tag',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
            [
                'label' => 'Visualizar',
                'url' => route('email-tags.show', ['emailTag' => $emailTag->id]),
                'permission' => 'show-email-tag',
                'class' => 'btn-primary-md',
                'icon' => 'lucide-eye',
            ],
        ]" />

        <x-alert />

        <form action="{{ route('email-tags.update', ['emailTag' => $emailTag->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="form-label">Nome *</label>
                <input type="text" name="name" id="name" class="form-input"
                    placeholder="Digite o nome da tag (ex: tag-teste)" value="{{ old('name', $emailTag->name) }}" required>
                <span class="text-xs text-gray-500">Apenas letras minúsculas, números e hífens, sem espaços ou
                    acentos</span>
                @error('name')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="form-label mb-4">Situação *</label>
                <input type="radio" name="is_active" value="1" id="is_active_true"
                    {{ old('is_active', $emailTag->is_active) == '1' ? 'checked' : '' }}>
                <label for="is_active_true" class="form-input-checkbox">Ativo</label>
                <input type="radio" name="is_active" value="0" id="is_active_false"
                    {{ old('is_active', $emailTag->is_active) == '0' ? 'checked' : '' }}>
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
