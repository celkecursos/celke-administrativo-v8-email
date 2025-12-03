<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailMachines\EmailMachineController;
use App\Http\Controllers\EmailMachines\EmailMachineSequenceController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('permission:dashboard');

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
        });
    });

});
