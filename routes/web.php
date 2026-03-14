<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    // DataTables AJAX data route (must be before resource route)
    Route::get('layanan-data', [App\Http\Controllers\ServiceController::class, 'data'])->name('layanan.data');

    // Resource route for layanan - Admin only
    Route::resource('layanan', App\Http\Controllers\ServiceController::class)->middleware('admin');

    // Transaction DataTables AJAX data
    Route::get('transaksi/data', [App\Http\Controllers\TransactionController::class, 'data'])->name('transaksi.data');

    // Full resource routes for transactions
    Route::resource('transaksi', App\Http\Controllers\TransactionController::class);

    // Karyawan DataTables AJAX data route (must be before resource route) - Admin only
    Route::get('karyawan/data', [App\Http\Controllers\UserController::class, 'data'])->name('karyawan.data')->middleware('admin');

    // Resource route for karyawan (employee management) - Admin only
    Route::resource('karyawan', App\Http\Controllers\UserController::class)->middleware('admin');

    // Dashboard route
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard');

    // Laporan route - Available for both admin and employees
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/data', [App\Http\Controllers\LaporanController::class, 'data'])->name('laporan.data');
    
    // New Financial Reports
    Route::get('/laporan/cash-flow', [App\Http\Controllers\LaporanController::class, 'cashFlow'])->name('laporan.cash-flow');
    Route::get('/laporan/profit-loss', [App\Http\Controllers\LaporanController::class, 'profitLoss'])->name('laporan.profit-loss');
    Route::get('/laporan/service-revenue', [App\Http\Controllers\LaporanController::class, 'serviceRevenue'])->name('laporan.service-revenue');
    Route::get('/laporan/transaction-report', [App\Http\Controllers\LaporanController::class, 'transactionReport'])->name('laporan.transaction-report');
    
    // PDF Download Routes
    Route::get('/laporan/download/cash-flow', [App\Http\Controllers\LaporanController::class, 'downloadCashFlowPdf'])->name('laporan.download.cash-flow');
    Route::get('/laporan/download/profit-loss', [App\Http\Controllers\LaporanController::class, 'downloadProfitLossPdf'])->name('laporan.download.profit-loss');
    Route::get('/laporan/download/service-revenue', [App\Http\Controllers\LaporanController::class, 'downloadServiceRevenuePdf'])->name('laporan.download.service-revenue');
    Route::get('/laporan/download/salary-slip', [App\Http\Controllers\LaporanController::class, 'downloadSalarySlipPdf'])->name('laporan.download.salary-slip');
    Route::get('/laporan/download/salary-report', [App\Http\Controllers\LaporanController::class, 'downloadSalaryReportPdf'])->name('laporan.download.salary-report');

    // Logout route
    Route::post('/logout', function () {
        \Auth::logout();
        return redirect()->route('login');
    })->name('logout');

    // Admin Chatbot FAQ Management - Admin only
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('chatbot', App\Http\Controllers\Admin\ChatbotFaqController::class)
            ->only(['index', 'store', 'update', 'destroy']);
    });
});

// Chatbot API - Available for guests and authenticated users
Route::post('/chatbot/send', [App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.send');

require __DIR__.'/auth.php';
