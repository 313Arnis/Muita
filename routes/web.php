<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customs\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\AnalystController;
use App\Http\Controllers\BrokerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InspectionController;

// 1. Publiski pieejamie maršruti
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// 2. Autentifikācija
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Autentificēto lietotāju kopīgie maršruti
Route::middleware(['auth'])->group(function () {

    // Šis kalpo kā galvenais ieejas punkts, kas pārvirza uz lomu skatiem
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.main');

    // Lietas apskate (pieejama visām lomām)
    Route::get('/case/{external_id}', [DashboardController::class, 'show'])
        ->name('case.show')
        ->middleware('role:admin,analyst,broker,inspector');

    // --- INSPEKTORA SADAĻA ---
    Route::middleware(['role:inspector,admin'])->group(function () {
        Route::get('/inspector/dashboard', [DashboardController::class, 'index'])->name('inspector.dashboard');
        // Pievienojam arī pārbaužu kontrolieri
        Route::resource('inspections', InspectionController::class);
    });

    // --- ANALĪTIĶA SADAĻA ---
    Route::middleware(['role:analyst,admin'])->group(function () {
        Route::get('/analyst/dashboard', [AnalystController::class, 'index'])->name('analyst.dashboard');
        Route::get('/analyst/risk-analysis', [AnalystController::class, 'riskAnalysis'])->name('analyst.risk-analysis');
        Route::get('/analyst/monitoring', [AnalystController::class, 'monitoring'])->name('analyst.monitoring');
    });

    // --- BROKERA SADAĻA ---
    Route::middleware(['role:broker'])->group(function () {
        Route::get('/broker/dashboard', [BrokerController::class, 'index'])->name('broker.dashboard');
        Route::get('/broker/documents', [BrokerController::class, 'documents'])->name('broker.documents');
        Route::get('/broker/declarations', [BrokerController::class, 'declarations'])->name('broker.declarations');
        Route::post('/broker/submit-document', [BrokerController::class, 'submitDocument'])->name('broker.submit-document');
    });

    // --- ADMINA SADAĻA ---
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/configuration', [AdminController::class, 'configuration'])->name('admin.configuration');
        Route::post('/admin/create-user', [AdminController::class, 'createUser'])->name('admin.create-user');
    });
});
