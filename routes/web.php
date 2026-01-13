<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

// Admin Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin routes (accessible by all authenticated users)
    Volt::route('admin/categories', 'pages.admin.categories.index')
        ->name('admin.categories');

    Volt::route('admin/products', 'pages.admin.products.index')
        ->name('admin.products');

    // POS routes (accessible by all authenticated users)
    Volt::route('pos', 'pages.pos.index')
        ->name('pos.index');

    // Stock Movements (accessible by all authenticated users)
    Volt::route('admin/stock-movements', 'pages.admin.stock-movements.index')
        ->name('admin.stock-movements');

    // User Management (superadmin only)
    Volt::route('admin/users', 'pages.admin.users.index')
        ->middleware('superadmin')
        ->name('admin.users');

    // Dedicated PDF Print Route (A4 Invoice)
    Route::get('/print/struk/{id}', function ($id) {
        $sale = \App\Models\Sale::with(['items', 'user'])->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('print.invoice-a4', compact('sale'));

        // Set Paper Size to A4
        $pdf->setPaper('a4', 'portrait');

        // Stream the PDF
        return $pdf->stream('Invoice-' . $sale->invoice_number . '.pdf');
    })->name('print.receipt');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
