<?php

use function Livewire\Volt\{state, with, usesPagination, computed};
use App\Models\Product;
use App\Services\ExpiredProductService;

usesPagination();

state([
    'filter' => 'all', // all, expired, near_expiry
    'search' => '',
    'nearExpiryDays' => 30,
]);

with(fn() => [
    'summary' => (new ExpiredProductService())->getExpirySummary(),
]);

$products = computed(function () {
    $query = Product::with('category')
        ->whereNotNull('expired_date');

    // Apply filter
    if ($this->filter === 'expired') {
        $query->expired();
    } elseif ($this->filter === 'near_expiry') {
        $query->nearExpiry($this->nearExpiryDays);
    }

    // Apply search
    if ($this->search) {
        $query->where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('kode_obat', 'like', '%' . $this->search . '%');
        });
    }

    return $query->orderBy('expired_date', 'asc')->paginate(15);
});

$setFilter = function ($filter) {
    $this->filter = $filter;
    $this->resetPage();
};

$deactivateProduct = function ($id) {
    $product = Product::findOrFail($id);
    $product->update(['is_active' => false]);
    session()->flash('message', "Produk '{$product->name}' telah dinonaktifkan.");
};

?>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Produk Kadaluarsa
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-200">Monitor Produk Kadaluarsa</h2>

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 dark:bg-red-800 mr-4">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-red-600 dark:text-red-300 font-medium">Kadaluarsa</p>
                            <p class="text-2xl font-bold text-red-700 dark:text-red-200">{{ $summary['expired'] }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-800 mr-4">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-yellow-600 dark:text-yellow-300 font-medium">Hampir Kadaluarsa (7
                                hari)</p>
                            <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-200">
                                {{ $summary['near_expiry_7_days'] }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-800 mr-4">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-orange-600 dark:text-orange-300 font-medium">Hampir Kadaluarsa (30
                                hari)</p>
                            <p class="text-2xl font-bold text-orange-700 dark:text-orange-200">
                                {{ $summary['near_expiry_30_days'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-800 mr-4">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600 dark:text-blue-300 font-medium">Total Dengan Exp. Date</p>
                            <p class="text-2xl font-bold text-blue-700 dark:text-blue-200">
                                {{ $summary['total_with_expiry_date'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div class="flex flex-wrap gap-2">
                    <button wire:click="setFilter('all')"
                        class="px-4 py-2 rounded-lg font-medium transition {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Semua
                    </button>
                    <button wire:click="setFilter('expired')"
                        class="px-4 py-2 rounded-lg font-medium transition {{ $filter === 'expired' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Kadaluarsa
                    </button>
                    <button wire:click="setFilter('near_expiry')"
                        class="px-4 py-2 rounded-lg font-medium transition {{ $filter === 'near_expiry' ? 'bg-yellow-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Hampir Kadaluarsa
                    </button>
                </div>

                <div class="relative">
                    <input type="text" wire:model.live="search" placeholder="Cari nama atau kode obat..."
                        class="pl-10 pr-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-full w-full md:w-80 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Produk</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kategori</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal Kadaluarsa</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($this->products as $product)
                            <tr wire:key="exp-{{ $product->id }}"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-600 mr-3">
                                            @if($product->image)
                                                <img class="h-full w-full object-cover"
                                                    src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-gray-400">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                {{ $product->name }}</div>
                                            <div class="text-xs text-blue-600 dark:text-blue-400 font-mono">
                                                {{ $product->kode_obat }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200">
                                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $product->expired_date->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        @if($product->days_until_expiry < 0)
                                            {{ abs($product->days_until_expiry) }} hari yang lalu
                                        @elseif($product->days_until_expiry === 0)
                                            Hari ini
                                        @else
                                            {{ $product->days_until_expiry }} hari lagi
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($product->expiry_status === 'expired')
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200">
                                            KADALUARSA
                                        </span>
                                    @elseif($product->expiry_status === 'near_expiry')
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200">
                                            HAMPIR KADALUARSA
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200">
                                            AMAN
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->is_active)
                                        <button onclick="confirm('Nonaktifkan produk ini?') || event.stopImmediatePropagation()"
                                            wire:click="deactivateProduct({{ $product->id }})"
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-bold text-sm">
                                            Nonaktifkan
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                    @if($filter === 'expired')
                                        Tidak ada produk yang kadaluarsa.
                                    @elseif($filter === 'near_expiry')
                                        Tidak ada produk yang hampir kadaluarsa.
                                    @else
                                        Tidak ada produk dengan tanggal kadaluarsa.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $this->products->links() }}
            </div>
        </div>
    </div>
</div>