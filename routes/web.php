<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

// Semua route harus dalam middleware web
Route::middleware(['web'])->group(function () {

    // Halaman Welcome (Tampilan Utama)
    Route::get('/', function () {
        return view('welcome');  
    });

    // Halaman Login & Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');  
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');  

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');  
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');  
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Gunakan middleware auth untuk routes yang memerlukan autentikasi
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Program Routes
        Route::get('/programs', [ProgramController::class, 'index'])->name('program.index');
        Route::get('/program/create', [ProgramController::class, 'create'])->name('program.create');
        Route::post('/program', [ProgramController::class, 'store'])->name('program.store');
        Route::get('/program/{id}', [ProgramController::class, 'show'])->name('program.show');
        Route::get('/program/{id}/edit', [ProgramController::class, 'edit'])->name('program.edit');
        Route::put('/program/{id}', [ProgramController::class, 'update'])->name('program.update');
        Route::delete('/program/{id}', [ProgramController::class, 'destroy'])->name('program.destroy');
    });

    // Halaman Link Invite untuk Peserta
    // Route::get('/invite/{token}', [InviteController::class, 'show'])->name('invite.show');
});
