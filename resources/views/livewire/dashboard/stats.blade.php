<?php

use function Livewire\Volt\{state};
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;



state([
    'categoryCount' => fn() => Category::count(),
    'productCount' => fn() => Product::count(),
    'lowStockCount' => fn() => Product::where('stock', '<', 10)->count(),
    'totalValue' => fn() => Product::sum(DB::raw('price * stock')),
]);

?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Categories Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Kategori
                </p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $categoryCount }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.categories') }}" wire:navigate
                class="hover:text-blue-600 font-semibold inline-flex items-center">
                Lihat Detail
                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Products Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-purple-50 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Produk
                </p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $productCount }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('admin.products') }}" wire:navigate
                class="hover:text-purple-600 font-semibold inline-flex items-center">
                Lihat Detail
                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Low Stock Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/50 text-red-600 dark:text-red-400">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok Menipis
                </p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lowStockCount }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <p class="text-xs italic">Segera restok produk di bawah 10 unit.</p>
        </div>
    </div>

    <!-- Total Value Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/50 text-green-600 dark:text-green-400">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aset Inventoris
                </p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp
                    {{ number_format($totalValue, 0, ',', '.') }}
                </p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <p class="text-xs font-semibold">Estimasi nilai total stok saat ini.</p>
        </div>
    </div>
</div>