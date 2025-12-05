@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Ordenar E-mails" :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard.index')],
        ['label' => 'Máquinas', 'url' => route('email-machines.index')],
        ['label' => $emailMachine->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => $sequence->name, 'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id])],
        ['label' => 'Ordenar E-mails'],
    ]" />

    <div class="content-box">

        <x-alert />

        <x-content-box-header title="Ordenar E-mails da Sequência" :buttons="[
            [
                'label' => 'Sequências',
                'url' => route('email-machine-sequences.index', ['emailMachine' => $emailMachine->id]),
                'permission' => 'index-email-machine-sequence',
                'class' => 'btn-info-md align-icon-btn',
                'icon' => 'lucide-arrow-left',
            ],
        ]" />

        <div class="content-box-body">
            
            @if($emails->count() > 0)
                <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="flex items-start">
                        <x-lucide-info class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 mt-0.5" />
                        <div class="text-sm text-blue-800 dark:text-blue-300">
                            <p class="font-semibold mb-1">Como ordenar:</p>
                            <p>Use os botões <strong>↑ Subir</strong> e <strong>↓ Descer</strong> para alterar a posição dos e-mails.</p>
                            <p>Clique em <strong>Salvar Ordem</strong> quando terminar.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('email-sequence-emails.update-order', ['emailMachine' => $emailMachine->id, 'sequence' => $sequence->id]) }}" method="POST" id="order-form">
                    @csrf
                    @method('PUT')

                    <div id="emails-list" class="space-y-3">
                        @foreach($emails as $index => $email)
                            <div class="email-order-item p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg" 
                                data-email-id="{{ $email->id }}" data-order="{{ $email->order }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4 flex-1">
                                        <!-- Número da Ordem -->
                                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full font-bold">
                                            <span class="order-number">{{ $email->order }}</span>
                                        </div>

                                        <!-- Informações do E-mail -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <x-lucide-mail class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $email->title }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="{{ $email->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                                                    {{ $email->is_active ? 'Ativo' : 'Inativo' }}
                                                </span>
                                                @if($email->skip_email)
                                                    <span class="badge badge-warning">Pular</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botões de Ordenação -->
                                    <div class="flex items-center space-x-2">
                                        <button type="button" class="btn-primary align-icon-btn move-up" 
                                            {{ $loop->first ? 'disabled' : '' }}
                                            title="Mover para cima">
                                            <x-lucide-arrow-up class="w-4 h-4" />
                                            <span class="hidden md:inline">Subir</span>
                                        </button>
                                        <button type="button" class="btn-primary align-icon-btn move-down" 
                                            {{ $loop->last ? 'disabled' : '' }}
                                            title="Mover para baixo">
                                            <x-lucide-arrow-down class="w-4 h-4" />
                                            <span class="hidden md:inline">Descer</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Input hidden com a ordem -->
                                <input type="hidden" name="orders[{{ $email->id }}]" value="{{ $email->order }}" class="order-input">
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn-success-md align-icon-btn">
                            <x-lucide-save class="icon-btn" />
                            <span>Salvar Ordem</span>
                        </button>
                    </div>
                </form>

            @else
                <div class="alert-warning">
                    <x-lucide-alert-circle class="w-5 h-5 inline-block mr-2" />
                    Nenhum e-mail cadastrado nesta sequência.
                </div>
            @endif

        </div>
    </div>

    @if($emails->count() > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const emailsList = document.getElementById('emails-list');
                const items = emailsList.querySelectorAll('.email-order-item');

                // Função para mover item para cima
                items.forEach(item => {
                    const moveUpBtn = item.querySelector('.move-up');
                    const moveDownBtn = item.querySelector('.move-down');

                    moveUpBtn?.addEventListener('click', function() {
                        const prev = item.previousElementSibling;
                        if (prev && prev.classList.contains('email-order-item')) {
                            emailsList.insertBefore(item, prev);
                            updateOrder();
                        }
                    });

                    moveDownBtn?.addEventListener('click', function() {
                        const next = item.nextElementSibling;
                        if (next && next.classList.contains('email-order-item')) {
                            emailsList.insertBefore(next, item);
                            updateOrder();
                        }
                    });
                });

                // Atualizar a ordem após mover
                function updateOrder() {
                    const items = emailsList.querySelectorAll('.email-order-item');
                    items.forEach((item, index) => {
                        const newOrder = index + 1;
                        const orderNumber = item.querySelector('.order-number');
                        const orderInput = item.querySelector('.order-input');
                        const moveUpBtn = item.querySelector('.move-up');
                        const moveDownBtn = item.querySelector('.move-down');

                        // Atualizar número visual
                        orderNumber.textContent = newOrder;
                        
                        // Atualizar input hidden
                        orderInput.value = newOrder;

                        // Atualizar estado dos botões
                        if (index === 0) {
                            moveUpBtn.disabled = true;
                            moveUpBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            moveUpBtn.disabled = false;
                            moveUpBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }

                        if (index === items.length - 1) {
                            moveDownBtn.disabled = true;
                            moveDownBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            moveDownBtn.disabled = false;
                            moveDownBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    });
                }
            });
        </script>
    @endif
@endsection
