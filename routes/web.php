<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PeerPermissionController;
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
    // Di dalam middleware auth
    Route::middleware(['auth'])->group(function () {
        // Ubah route dashboard untuk tetap menggunakan dashboard URL
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Program Routes - tetapkan sebagai sub-route di bawah dashboard
        Route::get('/programs', [ProgramController::class, 'index'])->name('program.index');
        Route::get('/program/create', [ProgramController::class, 'create'])->name('program.create');
        Route::post('/program', [ProgramController::class, 'store'])->name('program.store');
        
        // Gunakan slug, bukan ID
        Route::get('/program/{slug}', [ProgramController::class, 'show'])->name('program.show');
        Route::get('/program/{slug}/edit', [ProgramController::class, 'edit'])->name('program.edit');
        Route::put('/program/{slug}', [ProgramController::class, 'update'])->name('program.update');
        Route::delete('/program/{slug}', [ProgramController::class, 'destroy'])->name('program.destroy');
    
        // Invitation Management
        Route::get('/program/{slug}/invitations', [InvitationController::class, 'index'])->name('invitation.index');
        Route::post('/program/{slug}/invitation', [InvitationController::class, 'store'])->name('invitation.store');
        Route::delete('/invitation/{id}', [InvitationController::class, 'destroy'])->name('invitation.destroy');
        
        // Participant Management
        Route::get('/program/{slug}/participants', [ParticipantController::class, 'index'])->name('participant.index');
        Route::get('/program/{slug}/participant/{id}', [ParticipantController::class, 'viewProgress'])->name('participant.progress');
        Route::post('/progress/{id}/mark', [ParticipantController::class, 'markProgress'])->name('progress.mark');
    });

    // Participant Routes (tidak memerlukan login)
    Route::get('/join/{token}', [InvitationController::class, 'showJoinForm'])->name('invitation.join');
    Route::post('/join/{token}', [ParticipantController::class, 'register'])->name('participant.register');
    Route::get('/dashboard', [ParticipantController::class, 'dashboard'])->name('participant.dashboard');
    Route::post('/progress/update', [ParticipantController::class, 'updateProgress'])->name('participant.update-progress');

    // Peer Review Routes
    Route::get('/peers', [PeerPermissionController::class, 'index'])->name('peer.index');
    Route::post('/peer/permission', [PeerPermissionController::class, 'togglePermission'])->name('peer.toggle-permission');
    Route::get('/peer/{id}/progress', [PeerPermissionController::class, 'viewPeerProgress'])->name('peer.progress');
        });
        // Halaman Link Invite untuk Peserta
        // Route::get('/invite/{token}', [InviteController::class, 'show'])->name('invite.show');
