@extends('layouts.admin')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <x-breadcrumb title="Visualizar Gatilho de Automação" :items="[
    ['label' => 'Dashboard', 'url' => route('dashboard.index')],
    ['label' => 'Ações Automatizadas', 'url' => route('email-automation-actions.index')],
    ['label' => $emailAutomationTrigger->automationAction->name ?? 'Ação sem nome',
     'url'   => route('email-automation-actions.show', $emailAutomationTrigger->automationAction)
    ],
    ['label' => 'Gatilho #' . $emailAutomationTrigger->id],
    ]" />

    <div class="content-box">

        <x-content-box-header title="Detalhes do Gatilho" :buttons="[
            [
                'label' => 'Voltar',
                'url'   => route('email-automation-actions.show', $emailAutomationTrigger->automationAction),
                'class' => 'btn-info-md',
                'icon'  => 'lucide-arrow-left',
            ],
            [
                'label' => 'Editar',
                'url'   => route('email-automation-triggers.edit', $emailAutomationTrigger),
                'class' => 'btn-warning-md',
                'icon'  => 'lucide-pencil',
                'permission' => 'update-email-automation-trigger',
            ],
            [
                'label' => 'Apagar',
                'url' => route('email-automation-triggers.destroy', $emailAutomationTrigger),
                'permission' => 'destroy-email-automation-trigger',
                'class' => 'btn-danger-md',
                'icon' => 'lucide-trash',
                'method' => 'delete',
                'confirm' => true,
                'id'         => $emailAutomationTrigger->id,
            ],
        ]" />

        <x-alert />

        <div class="detail-box">

            <div class="mb-1">
                <span class="title-detail-content">ID: </span>
                <span class="detail-content">{{ $emailAutomationTrigger->id }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Ação Automatizada: </span>
                <span class="detail-content">
                    <a href="{{ route('email-automation-actions.show', $emailAutomationTrigger->automationAction) }}">
                        {{ $emailAutomationTrigger->automationAction->name ?? 'N/D' }}
                    </a>
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Tipo de Filtro: </span>
                <span class="detail-content">{{ $emailAutomationTrigger->filterType->name ?? 'N/D' }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Tipo de Ação: </span>
                <span class="detail-content">{{ $emailAutomationTrigger->actionType->name ?? 'N/D' }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Status: </span>
                <span class="detail-content">
                    <span class="{{ $emailAutomationTrigger->is_active ? 'badge badge-success' : 'badge badge-danger' }}">
                        {{ $emailAutomationTrigger->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </span>
            </div>
            <!-- ==================== FILTROS ==================== -->
            <div class="mt-4 mb-2"><strong>Filtros Aplicados</strong></div>
            <hr class="my-2">

            <div class="mb-1">
                <span class="title-detail-content">Máquina de Email:</span>
                <span class="detail-content">{{ $emailAutomationTrigger->filterEmailMachine->name ?? '—' }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Sequência da Máquina:</span>
                <span class="detail-content">{{ $emailAutomationTrigger->filterEmailMachineSequence->name ?? '—' }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Email da Sequência:</span>
                <span class="detail-content">{{ $emailAutomationTrigger->filterEmailSequenceEmail->subject ?? '—' }}</span>
            </div>

            <!-- ==================== AÇÕES ==================== -->
            <div class="mt-4 mb-2"><strong>Ações Executadas</strong></div>
            <hr class="my-2">

            <div class="mb-1">
                <span class="title-detail-content">Adicionar Tag:</span>
                <span class="detail-content">{{ $emailAutomationTrigger->actionAddEmailTag->name ?? '—' }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Remover Tag:</span>
                <span class="detail-content">{{ $emailAutomationTrigger->actionRemoveEmailTag->name ?? '—' }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Adicionar em Sequência/Email:</span>
                <span class="detail-content">{{ $emailAutomationTrigger->actionAddEmailSequenceEmail->subject ?? '—' }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Remover de Sequência/Email:</span>
                <span class="detail-content">{{ $emailAutomationTrigger->actionRemoveEmailSequenceEmail->subject ?? '—' }}</span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Remover da Sequência da Máquina:</span>
                <span class="detail-content">{{ $emailAutomationTrigger->actionRemoveEmailMachineSequence->name ?? '—' }}</span>
            </div>

            <!-- ==================== DATAS ==================== -->
            <div class="mt-4"></div>
            <hr class="my-2">
            <div class="mb-1">
                <span class="title-detail-content">Cadastrado em: </span>
                <span class="detail-content">
                    {{ \Carbon\Carbon::parse($emailAutomationTrigger->created_at)->format('d/m/Y H:i:s') }}
                </span>
            </div>

            <div class="mb-1">
                <span class="title-detail-content">Atualizado em: </span>
                <span class="detail-content">
                    {{ \Carbon\Carbon::parse($emailAutomationTrigger->updated_at)->format('d/m/Y H:i:s') }}
                </span>
            </div>

        </div>
    </div>
@endsection