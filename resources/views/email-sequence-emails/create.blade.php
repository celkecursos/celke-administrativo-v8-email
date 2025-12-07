@extends('layouts.admin')

@section('content')

    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Cadastrar E-mail" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => $sequence->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => 'Cadastrar E-mail'],
    ]" />

    <div class="content-box">
        <x-content-box-header title="Cadastrar E-mail da Sequência" :buttons="[
            [
                'label' => 'Sequências',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'permission' => 'index-email-machine-sequence',
                'class' => 'btn-info-md',
                'icon' => 'lucide-list',
            ],
        ]" />

        <x-alert />

        <form action="{{ route('email-sequence-emails.store', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id]) }}" method="POST">
            @csrf
            @method('POST')

            <input type="hidden" name="email_machine_sequence_id" value="{{ $sequence->id }}">

            <div class="mb-4">
                <label for="sequence_name" class="form-label">Sequência</label>
                <input type="text" id="sequence_name" class="form-input" 
                    value="{{ $sequence->name }}" readonly>
                <p class="form-helper-text">
                    Este e-mail será adicionado à sequência acima
                </p>
            </div>

            <div class="mb-4">
                <label for="title" class="form-label">Título do E-mail *</label>
                <input type="text" name="title" id="title" class="form-input" placeholder="Título do e-mail"
                    value="{{ old('title') }}" required>
                @error('title')
                    <p class="form-input-error">{{ $message }}</p>
                @enderror
                <p class="form-helper-text">
                    Após cadastrar, você será redirecionado para editar o conteúdo completo do e-mail
                </p>
            </div>

            <div class="mb-4">
                <span class="required-field">* Campo obrigatório</span>
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
