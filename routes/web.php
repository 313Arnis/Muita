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
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Autentifikācija
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 3. Autentificēto lietotāju kopīgie maršruti
Route::middleware(['auth'])->group(function () {

    // Galvenais ieejas punkts
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kopīgā lietu apskate
    Route::get('/case/{external_id}', [DashboardController::class, 'show'])
        ->name('case.show')
        ->middleware('role:admin,analyst,broker,inspector');

    // --- INSPEKTORA SADAĻA ---
    Route::middleware(['role:inspector,admin'])->prefix('inspector')->name('inspector.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/assigned', [InspectorController::class, 'index'])->name('assigned');
        Route::get('/inspection/{external_id}', [InspectorController::class, 'show'])->name('show');
        Route::get('/decisions', [InspectorController::class, 'decisions'])->name('decisions');
        Route::post('/make-decision', [InspectorController::class, 'makeDecision'])->name('make-decision');
    });

    // Pārbaužu resursu maršruti
    Route::middleware(['role:inspector,admin'])->group(function () {
        Route::resource('inspections', InspectionController::class);
        Route::get('/inspection-decisions', [InspectionController::class, 'decisions'])->name('inspections.decisions.list');
        Route::post('/inspection-make-decision', [InspectionController::class, 'makeDecision'])->name('inspections.make-decision');
    });

    // --- ANALĪTIĶA SADAĻA ---
    Route::middleware(['role:analyst,admin'])->prefix('analyst')->name('analyst.')->group(function () {
        Route::get('/dashboard', [AnalystController::class, 'index'])->name('dashboard');
        Route::get('/risk-analysis', [AnalystController::class, 'riskAnalysis'])->name('risk-analysis');
        Route::get('/monitoring', [AnalystController::class, 'monitoring'])->name('monitoring');
    });

    // --- BROKERA SADAĻA (Saskaņots ar taviem Blade skatiem) ---
    Route::middleware(['role:broker'])->prefix('broker')->name('broker.')->group(function () {
        Route::get('/dashboard', [BrokerController::class, 'index'])->name('dashboard');
        Route::get('/declarations', [BrokerController::class, 'declarations'])->name('declarations');
        
        // Svarīgi: Šie nosaukumi sakrīt ar {{ route('broker.create-declaration') }}
        Route::get('/declarations/create', [BrokerController::class, 'createDeclaration'])->name('create-declaration');
        Route::post('/declarations/store', [BrokerController::class, 'storeDeclaration'])->name('store-declaration');
        
        Route::post('/submit-document', [BrokerController::class, 'submitDocument'])->name('submit-document');
        Route::get('/documents', [BrokerController::class, 'documents'])->name('documents');
    });

    // --- ADMINA SADAĻA ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/configuration', [AdminController::class, 'configuration'])->name('configuration');
        Route::post('/configuration', [AdminController::class, 'updateConfiguration'])->name('update-configuration');
        Route::post('/create-user', [AdminController::class, 'createUser'])->name('create-user');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('update-user');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('delete-user');
    });
});