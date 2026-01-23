@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Usuários" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Usuários', 'url' => route('users.index')],
        ['label' => 'Usuário'],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Importar Usuários" :buttons="[]" />

        <x-alert />

        <form action="{{ route('blocked-emails-imports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="mb-4">
                <label for="file" class="form-label">Conteúdo de E-mail *</label>
                <select name="email_sequence_email_id" id="email_sequence_email_id" class="form-select" required>
                    <option value="">Selecione</option>
                    @foreach ($emailSequenceEmails as $emailSequenceEmail)
                        <option value="{{ $emailSequenceEmail->id }}"
                            {{ old('email_sequence_email_id') == $emailSequenceEmail->id ? 'selected' : '' }}>
                            {{ $emailSequenceEmail->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="file" class="form-label">Status para o Usuário *</label>
                <select name="user_status_id" id="user_status_id" class="form-select" required>
                    <option value="">Selecione</option>
                    @foreach ($userStatuses as $userStatus)
                        <option value="{{ $userStatus->id }}"
                            {{ old('user_status_id') == $userStatus->id ? 'selected' : '' }}>
                            {{ $userStatus->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="form-label mb-4">Atribuir o Status para os Usuários Existentes *</label>
                <input type="radio" name="is_active" value="1" id="is_active_true"
                    {{ old('is_active') == '1' ? 'checked' : '' }}>
                <label for="is_active_true" class="form-input-checkbox">Sim</label>
                <input type="radio" name="is_active" value="0" id="is_active_false"
                    {{ old('is_active') == '0' ? 'checked' : '' }}>
                <label for="is_active_false" class="form-input-checkbox">Não</label>
                @error('is_active')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="file" class="form-label">Arquivo CSV *</label>
                <input type="file" name="file" id="file" class="form-input" required>
                {{-- <input type="file" name="file" id="file" class="form-input" accept=".csv" required> --}}
                <span class="form-helper-text">Colunas: name; email</span>
            </div>

            <div class="mb-4">
                <span class="required-field">* Campo obrigatório</span>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn-success-md align-icon-btn">
                    <x-lucide-upload class="icon-btn" />
                    <span>Importar</span>
                </button>
            </div>

        </form>
    </div>
@endsection
