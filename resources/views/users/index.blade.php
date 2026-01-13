@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Usuários" :items="[['label' => 'Dashboard', 'url' => route('dashboard.index')], ['label' => 'Usuários']]" />

    <div class="content-box">

        <x-content-box-header title="Usuários" />

        <x-alert />

        <!-- Início Formulário de Pesquisa -->
        <form method="GET">

            <div class="form-search mb-4">
                <input type="text" name="name" class="form-input" placeholder="Digite o nome" value="{{ $name }}">

                <input type="email" name="email" class="form-input" placeholder="Digite o e-mail"
                    value="{{ $email }}">

                <input type="date" name="start_date" class="form-input" placeholder="Data de início"
                    value="{{ $start_date }}">

                <input type="date" name="end_date" class="form-input" placeholder="Data de fim"
                    value="{{ $end_date }}">

                <select name="user_status_id" id="user_status_id" class="form-input" required>
                    <option value="">Selecione o Status</option>
                    @foreach ($userStatuses as $userStatus)
                        <option value="{{ $userStatus->id }}"
                            {{ old('user_status_id', $user_status_id) == $userStatus->id ? 'selected' : '' }}>
                            {{ $userStatus->name }}
                        </option>
                    @endforeach
                </select>

                <select name="tagged_or_untagged" class="form-input">
                    <option value="">
                        Selecione com tag ou sem tag
                    </option>
                    <option value="1" {{ old('tagged_or_untagged', $tagged_or_untagged) === '1' ? 'selected' : '' }}>
                        Com TAG
                    </option>
                    <option value="0" {{ old('tagged_or_untagged', $tagged_or_untagged) === '0' ? 'selected' : '' }}>
                        Sem TAG
                    </option>
                </select>

            </div>

            <div class="mb-4">
                <label for="" class="form-label">Tags</label>
                <div class="form-search">
                    @forelse ($emailTags as $emailTag)
                        <div>
                            <input type="checkbox" name="email_tag_id[]" id="email_tag_id{{ $emailTag->id }}"
                                value="{{ $emailTag->id }}"
                                {{ in_array($emailTag->id, old('email_tag_id', $email_tag_id ?? [])) ? 'checked' : '' }}>

                            <label for="email_tag_id{{ $emailTag->id }}" class="form-input-checkbox">
                                {{ $emailTag->name }}
                            </label>
                        </div>
                    @empty
                        <p>Nenhuma tag disponível.</p>
                    @endforelse
                </div>
            </div>

            <div class="flex gap-1">
                <button type="submit" formaction="{{ route('users.index') }}"
                    class="btn-primary-md flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <span>Pesquisar</span>
                </button>

                <button type="submit" formaction="{{ route('users.generate-csv-users') }}"
                    class="btn-success-md flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span>Exportar</span>
                </button>

                <a href="{{ route('users.index') }}" type="submit" class="btn-warning-md flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span>Limpar</span>
                </a>
            </div>
        </form>
        <!-- Fim Formulário de Pesquisa -->

        <div class="table-container mt-6">
            <table class="table">
                <thead>
                    <tr class="table-row-header">
                        <th class="table-header">ID</th>
                        <th class="table-header">Nome</th>
                        <th class="table-header">E-mail</th>
                        <th class="table-header center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Imprimir os registros --}}
                    @forelse ($users as $user)
                        <tr class="table-row-body">
                            <td class="table-body">{{ $user->id }}</td>
                            <td class="table-body">{{ $user->name }}</td>
                            <td class="table-body">{{ $user->email }}</td>
                            <td class="table-actions">
                                <x-table-actions :actions="[
                                    [
                                        'can' => 'show-user',
                                        'type' => 'link',
                                        'url' => route('users.show', $user->id),
                                        'class' => 'btn-primary-md align-icon-btn',
                                        'icon' => 'lucide-eye',
                                        'label' => 'Visualizar',
                                    ],
                                ]" />
                            </td>
                        </tr>

                    @empty
                        <div class="alert-warning">
                            Nenhum registro encontrado!
                        </div>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2 p-3">
                {{ $users->onEachSide(1)->links() }}
            </div>
        </div>

    </div>
@endsection
