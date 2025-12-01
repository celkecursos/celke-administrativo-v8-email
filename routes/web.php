<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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

});
