<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailAutomationActionController;
use App\Http\Controllers\EmailMachines\EmailMachineController;
use App\Http\Controllers\EmailMachines\EmailMachineSequenceController;
use App\Http\Controllers\EmailMachines\EmailSequenceEmailController;
use App\Http\Controllers\EmailSendingConfigController;
use App\Http\Controllers\EmailTagController;
use App\Http\Controllers\EmailUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailAutomationTriggerController;
use App\Http\Controllers\GenerateCSV\GenerateCSVUserController;
use Illuminate\Support\Facades\Route;

// Página inicial como redirecionamento para o login
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Tela de login
Route::get('/login', [AuthController::class, 'index'])->name('login');

// Processar os dados do login
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Grupo de rotas restritas
Route::group(['middleware' => 'auth'], function () {
    // Página inicial do administrativo
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('permission:dashboard-leademail');

    // Perfil
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show')->middleware('permission:show-profile');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('permission:edit-profile');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update')->middleware('permission:edit-profile');
        Route::get('/edit-image', [ProfileController::class, 'editImage'])->name('profile.edit_image')->middleware('permission:edit-profile');
        Route::put('/image', [ProfileController::class, 'updateImage'])->name('profile.update_image')->middleware('permission:edit-profile');
        Route::get('/edit-password', [ProfileController::class, 'editPassword'])->name('profile.edit_password')->middleware('permission:edit-password-profile');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update_password')->middleware('permission:edit-password-profile');
    });

    // Máquinas
    Route::prefix('machines')->group(function () {
        Route::get('/', [EmailMachineController::class, 'index'])->name('email-machines.index')->middleware('permission:index-email-machine');
        Route::get('/create', [EmailMachineController::class, 'create'])->name('email-machines.create')->middleware('permission:create-email-machine');
        Route::get('/{emailMachine}', [EmailMachineController::class, 'show'])->name('email-machines.show')->middleware('permission:show-email-machine');
        Route::post('/', [EmailMachineController::class, 'store'])->name('email-machines.store')->middleware('permission:create-email-machine');
        Route::get('/{emailMachine}/edit', [EmailMachineController::class, 'edit'])->name('email-machines.edit')->middleware('permission:edit-email-machine');
        Route::put('/{emailMachine}', [EmailMachineController::class, 'update'])->name('email-machines.update')->middleware('permission:edit-email-machine');
        Route::delete('/{emailMachine}', [EmailMachineController::class, 'destroy'])->name('email-machines.destroy')->middleware('permission:destroy-role');

        // Sequências da máquina
        Route::prefix('{emailMachine}/sequences')->group(function () {
            Route::get('/', [EmailMachineSequenceController::class, 'index'])->name('email-machine-sequences.index')->middleware('permission:index-email-machine-sequence');
            Route::get('/create', [EmailMachineSequenceController::class, 'create'])->name('email-machine-sequences.create')->middleware('permission:create-email-machine-sequence');
            Route::post('/', [EmailMachineSequenceController::class, 'store'])->name('email-machine-sequences.store')->middleware('permission:create-email-machine-sequence');
            Route::get('/{sequence}', [EmailMachineSequenceController::class, 'show'])->name('email-machine-sequences.show')->middleware('permission:show-email-machine-sequence');
            Route::get('/{sequence}/edit', [EmailMachineSequenceController::class, 'edit'])->name('email-machine-sequences.edit')->middleware('permission:edit-email-machine-sequence');
            Route::put('/{sequence}', [EmailMachineSequenceController::class, 'update'])->name('email-machine-sequences.update')->middleware('permission:edit-email-machine-sequence');
            Route::delete('/{sequence}', [EmailMachineSequenceController::class, 'destroy'])->name('email-machine-sequences.destroy')->middleware('permission:destroy-email-machine-sequence');

            // E-mails da sequência
            Route::prefix('{sequence}/emails')->group(function () {
                Route::get('/order', [EmailSequenceEmailController::class, 'order'])->name('email-sequence-emails.order')->middleware('permission:edit-email-sequence-email');
                Route::put('/order', [EmailSequenceEmailController::class, 'updateOrder'])->name('email-sequence-emails.update-order')->middleware('permission:edit-email-sequence-email');
                Route::patch('/{email}/move-up', [EmailSequenceEmailController::class, 'moveUp'])->name('email-sequence-emails.move-up')->middleware('permission:edit-email-sequence-email');
                Route::patch('/{email}/move-down', [EmailSequenceEmailController::class, 'moveDown'])->name('email-sequence-emails.move-down')->middleware('permission:edit-email-sequence-email');
                Route::get('/create', [EmailSequenceEmailController::class, 'create'])->name('email-sequence-emails.create')->middleware('permission:create-email-sequence-email');
                Route::post('/', [EmailSequenceEmailController::class, 'store'])->name('email-sequence-emails.store')->middleware('permission:create-email-sequence-email');
                Route::patch('/{email}/toggle-status', [EmailSequenceEmailController::class, 'toggleStatus'])->name('email-sequence-emails.toggle-status')->middleware('permission:edit-email-sequence-email');
                Route::get('/{email}', [EmailSequenceEmailController::class, 'show'])->name('email-sequence-emails.show')->middleware('permission:show-email-sequence-email');
                Route::get('/{email}/dates', [EmailSequenceEmailController::class, 'showDates'])->name('email-sequence-emails.show-dates')->middleware('permission:show-email-sequence-email');
                Route::get('/{email}/config', [EmailSequenceEmailController::class, 'showConfig'])->name('email-sequence-emails.show-config')->middleware('permission:show-email-sequence-email');
                Route::get('/{email}/users', [EmailSequenceEmailController::class, 'showUsers'])->name('email-sequence-emails.show-users')->middleware('permission:show-email-sequence-email');
                Route::get('/{email}/edit', [EmailSequenceEmailController::class, 'edit'])->name('email-sequence-emails.edit')->middleware('permission:edit-email-sequence-email');
                Route::put('/{email}', [EmailSequenceEmailController::class, 'update'])->name('email-sequence-emails.update')->middleware('permission:edit-email-sequence-email');
                Route::get('/{email}/edit-dates', [EmailSequenceEmailController::class, 'editDates'])->name('email-sequence-emails.edit-dates')->middleware('permission:edit-email-sequence-email');
                Route::put('/{email}/delivery-delay', [EmailSequenceEmailController::class, 'updateDeliveryDelay'])->name('email-sequence-emails.update-delivery-delay')->middleware('permission:edit-email-sequence-email');
                Route::put('/{email}/fixed-shipping-date', [EmailSequenceEmailController::class, 'updateFixedShippingDate'])->name('email-sequence-emails.update-fixed-shipping-date')->middleware('permission:edit-email-sequence-email');
                Route::put('/{email}/submission-window', [EmailSequenceEmailController::class, 'updateSubmissionWindow'])->name('email-sequence-emails.update-submission-window')->middleware('permission:edit-email-sequence-email');
                Route::get('/{email}/edit-config', [EmailSequenceEmailController::class, 'editConfig'])->name('email-sequence-emails.edit-config')->middleware('permission:edit-email-sequence-email');
                Route::put('/{email}/config', [EmailSequenceEmailController::class, 'updateConfig'])->name('email-sequence-emails.update-config')->middleware('permission:edit-email-sequence-email');
                Route::delete('/{email}', [EmailSequenceEmailController::class, 'destroy'])->name('email-sequence-emails.destroy')->middleware('permission:destroy-email-sequence-email');
            });
        });
    });

    // Usuários
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index')->middleware('permission:index-user');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:show-user');
    });

    // Gerar CSV dos usuários
    Route::get('/generate-csv/users', [GenerateCSVUserController::class, 'generateCSVUsers'])->name('users.generate-csv-users')->middleware('permission:generate-csv-users');

    // E-mails do usuário
    Route::prefix('email-users')->group(function () {
        Route::patch('/{emailUser}/toggle-status', [EmailUserController::class, 'toggleStatus'])->name('email-users.toggle-status')->middleware('permission:edit-email-user');
        Route::delete('/{emailUser}', [EmailUserController::class, 'destroy'])->name('email-users.destroy')->middleware('permission:destroy-email-user');
    });

    // Tags
    Route::prefix('email-tags')->group(function () {
        Route::get('/', [EmailTagController::class, 'index'])->name('email-tags.index')->middleware('permission:index-email-tag');
        Route::get('/create', [EmailTagController::class, 'create'])->name('email-tags.create')->middleware('permission:create-email-tag');
        Route::post('/', [EmailTagController::class, 'store'])->name('email-tags.store')->middleware('permission:create-email-tag');
        Route::get('/{emailTag}', [EmailTagController::class, 'show'])->name('email-tags.show')->middleware('permission:show-email-tag');
        Route::get('/{emailTag}/edit', [EmailTagController::class, 'edit'])->name('email-tags.edit')->middleware('permission:edit-email-tag');
        Route::put('/{emailTag}', [EmailTagController::class, 'update'])->name('email-tags.update')->middleware('permission:edit-email-tag');
        Route::delete('/{emailTag}', [EmailTagController::class, 'destroy'])->name('email-tags.destroy')->middleware('permission:destroy-email-tag');
    });

    // Ações Automatizadas
    Route::prefix('email-automation-actions')->group(function () {
        Route::get('/', [EmailAutomationActionController::class, 'index'])->name('email-automation-actions.index')->middleware('permission:index-email-automation-action');
        Route::get('/create', [EmailAutomationActionController::class, 'create'])->name('email-automation-actions.create')->middleware('permission:create-email-automation-action');
        Route::post('/', [EmailAutomationActionController::class, 'store'])->name('email-automation-actions.store')->middleware('permission:create-email-automation-action');
        Route::get('/{emailAutomationAction}', [EmailAutomationActionController::class, 'show'])->name('email-automation-actions.show')->middleware('permission:show-email-automation-action');
        Route::get('/{emailAutomationAction}/edit', [EmailAutomationActionController::class, 'edit'])->name('email-automation-actions.edit')->middleware('permission:edit-email-automation-action');
        Route::put('/{emailAutomationAction}', [EmailAutomationActionController::class, 'update'])->name('email-automation-actions.update')->middleware('permission:edit-email-automation-action');
        Route::delete('/{emailAutomationAction}', [EmailAutomationActionController::class, 'destroy'])->name('email-automation-actions.destroy')->middleware('permission:destroy-email-automation-action');
    });

    //Gatilhos de automação de e-mails (Triggers)
    Route::prefix('email-automation-triggers')->group(function () {
        Route::get('/{emailAutomationTrigger}', [EmailAutomationTriggerController::class, 'show'])
            ->name('email-automation-triggers.show')
            ->middleware('permission:show-email-automation-trigger');

        Route::get('/{emailAutomationTrigger}/edit', [EmailAutomationTriggerController::class, 'edit'])
            ->name('email-automation-triggers.edit')
            ->middleware('permission:update-email-automation-trigger');

        Route::put('/{emailAutomationTrigger}', [EmailAutomationTriggerController::class, 'update'])
            ->name('email-automation-triggers.update')
            ->middleware('permission:update-email-automation-trigger');

        Route::delete('/{emailAutomationTrigger}', [EmailAutomationTriggerController::class, 'destroy'])
            ->name('email-automation-triggers.destroy')
            ->middleware('permission:destroy-email-automation-trigger');
    });

    // Servidores de E-mail
    Route::prefix('email-sending-configs')->group(function () {
        // 📄 Listagem
        Route::get('/', [EmailSendingConfigController::class, 'index'])->name('email-sending-configs.index')->middleware('permission:index-email-sending-config');
        // ➕ Criar
        Route::get('/create', [EmailSendingConfigController::class, 'create'])->name('email-sending-configs.create')->middleware('permission:create-email-sending-config');
        Route::post('/', [EmailSendingConfigController::class, 'store'])->name('email-sending-configs.store')->middleware('permission:create-email-sending-config');
        // 👁 Visualizar
        Route::get('/{emailSendingConfig}', [EmailSendingConfigController::class, 'show'])->name('email-sending-configs.show')->middleware('permission:show-email-sending-config');
        /*
        |--------------------------------------------------------------------------
        | ✏️ EDIÇÕES
        |--------------------------------------------------------------------------
        */
        // 🔹 Credenciais (form principal)
        Route::get('/{emailSendingConfig}/edit', [EmailSendingConfigController::class, 'edit'])->name('email-sending-configs.edit')->middleware('permission:edit-email-sending-config');
        Route::put('/{emailSendingConfig}/update-credentials', [EmailSendingConfigController::class, 'update'])->name('email-sending-configs.update-credentials')->middleware('permission:edit-email-sending-config');
        // 🔹 Senha
        Route::get('/{emailSendingConfig}/edit-password', [EmailSendingConfigController::class, 'editPassword'])->name('email-sending-configs.edit-password')->middleware('permission:edit-email-sending-config');
        Route::put('/{emailSendingConfig}/update-password', [EmailSendingConfigController::class, 'updatePassword'])->name('email-sending-configs.update-password')->middleware('permission:edit-email-sending-config');
        // 🔹 Remetente
        Route::get('/{emailSendingConfig}/edit-sender', [EmailSendingConfigController::class, 'editSender'])->name('email-sending-configs.edit-sender')->middleware('permission:edit-email-sending-config');
        Route::put('/{emailSendingConfig}/update-sender', [EmailSendingConfigController::class, 'updateSender'])->name('email-sending-configs.update-sender')->middleware('permission:edit-email-sending-config');
        // 🔹 Configurações
        Route::get('/{emailSendingConfig}/edit-settings', [EmailSendingConfigController::class, 'editSettings'])->name('email-sending-configs.edit-settings')->middleware('permission:edit-email-sending-config');
        Route::put('/{emailSendingConfig}/update-settings', [EmailSendingConfigController::class, 'updateSettings'])->name('email-sending-configs.update-settings')->middleware('permission:edit-email-sending-config');
        // 🗑 Remover
        Route::delete('/{emailSendingConfig}', [EmailSendingConfigController::class, 'destroy'])->name('email-sending-configs.destroy')->middleware('permission:destroy-email-sending-config');
    });
});
