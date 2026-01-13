<?php

use function Livewire\Volt\{state, with, usesPagination};
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

usesPagination();

state([
    'search' => '',
    'filterType' => '',
    'filterProduct' => '',
    'dateFrom' => '',
    'dateTo' => '',
]);

with(fn() => [
    'movements' => StockMovement::with(['product', 'user'])
        ->when($this->search, function ($q) {
            $q->whereHas('product', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('kode_obat', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
        ->when($this->filterProduct, fn($q) => $q->where('product_id', $this->filterProduct))
        ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
        ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
        ->latest()
        ->paginate(20),
    'products' => Product::orderBy('name')->get(),
    'stats' => [
        'total_in' => StockMovement::where('type', 'IN')->sum('qty'),
        'total_out' => StockMovement::where('type', 'OUT')->sum('qty'),
        'total_adjust' => StockMovement::where('type', 'ADJUST')->count(),
    ],
]);

$resetFilters = function () {
    $this->search = '';
    $this->filterType = '';
    $this->filterProduct = '';
    $this->dateFrom = '';
    $this->dateTo = '';
};

?>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Stock Movements
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Riwayat Pergerakan Stok</h2>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <div class="text-sm text-green-700 font-semibold">Total Stok Masuk</div>
                    <div class="text-2xl font-bold text-green-900">{{ number_format($stats['total_in']) }}</div>
                </div>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="text-sm text-red-700 font-semibold">Total Stok Keluar</div>
                    <div class="text-2xl font-bold text-red-900">{{ number_format($stats['total_out']) }}</div>
                </div>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="text-sm text-blue-700 font-semibold">Total Penyesuaian</div>
                    <div class="text-2xl font-bold text-blue-900">{{ number_format($stats['total_adjust']) }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Cari Produk</label>
                        <input type="text" wire:model.live="search" placeholder="Nama atau kode..."
                            class="w-full border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tipe</label>
                        <select wire:model.live="filterType" class="w-full border-gray-300 rounded text-sm">
                            <option value="">Semua</option>
                            <option value="IN">Masuk</option>
                            <option value="OUT">Keluar</option>
                            <option value="ADJUST">Penyesuaian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Produk</label>
                        <select wire:model.live="filterProduct" class="w-full border-gray-300 rounded text-sm">
                            <option value="">Semua Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" wire:model.live="dateFrom" class="w-full border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" wire:model.live="dateTo" class="w-full border-gray-300 rounded text-sm">
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <button wire:click="resetFilters" class="text-sm text-blue-600 hover:text-blue-800 font-bold">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-hidden border border-gray-200 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal/Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Referensi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($movements as $movement)
                                            <tr wire:key="movement-{{ $movement->id }}" class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $movement->created_at->format('d/m/Y') }}</div>
                                                    <div class="text-xs text-gray-500">{{ $movement->created_at->format('H:i') }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-bold text-gray-900">{{ $movement->product->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $movement->product->kode_obat }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-3 py-1 text-xs font-semibold rounded-full 
                                                                {{ $movement->type === 'IN' ? 'bg-green-100 text-green-800' :
                            ($movement->type === 'OUT' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                                        {{ $movement->type === 'IN' ? 'Masuk' : ($movement->type === 'OUT' ? 'Keluar' : 'Penyesuaian') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="text-sm font-bold {{ $movement->type === 'IN' ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $movement->type === 'IN' ? '+' : '-' }}{{ $movement->qty }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">{{ ucfirst($movement->reference_type ?? '-') }}</div>
                                                    @if($movement->reference_id)
                                                        <div class="text-xs text-gray-500">#{{ $movement->reference_id }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $movement->user->name ?? 'System' }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-500 max-w-xs truncate">{{ $movement->note ?? '-' }}</div>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                                    Tidak ada data pergerakan stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>