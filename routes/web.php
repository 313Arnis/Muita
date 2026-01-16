<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customs\DashboardController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/case/{external_id}', [DashboardController::class, 'show'])->name('case.show');