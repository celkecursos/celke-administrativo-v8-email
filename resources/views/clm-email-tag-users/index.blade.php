@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Usuários" :items="[['label' => 'Dashboard', 'url' => route('dashboard.index')], ['label' => 'Usuários']]" />

    <div class="content-box">

        <x-content-box-header title="Usuários" />

        <x-alert />

        <!-- Início Formulário de Pesquisa -->
        <form class="form-search">

            <select name="course_id" id="course_id" class="form-input" required>
                <option value="">Selecione o Curso</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $course_id) == $course->id ? 'selected' : '' }}>
                        {{ $course->name }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-1">
                <button type="submit" class="btn-primary-md flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <span>Pesquisar</span>
                </button>
                <a href="{{ route('clms-email-tag-users.index') }}" type="submit"
                    class="btn-warning-md flex items-center space-x-1">
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

        <div class="alert-warning">
            Pesquise primeiro os usuários do curso antes de atribuir a tag aos usuários.
        </div>

        <!-- Início Formulário atribuir TAG -->
        <form class="form-search mt-4" action="{{ route('clms-email-tag-users.store') }}" method="POST">
            @csrf

            <input type="hidden" name="course_id" value="{{ $course_id ?? '' }}">

            @forelse ($emailTags as $emailTag)
                <div>
                    <input type="radio" name="email_tag_id" id="{{ $emailTag->id }}" value="{{ $emailTag->id }}">
                    <label for="{{ $emailTag->id }}" class="form-input-checkbox">{{ $emailTag->name }}</label>
                </div>
            @empty
                <p>Nenhuma tag disponível.</p>
            @endforelse

            <div class="flex gap-1">
                <button type="submit" class="btn-success-md flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                    <span>Atribuir TAG</span>
                </button>
            </div>
        </form>
        <!-- Fim Formulário atribuir TAG -->

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
