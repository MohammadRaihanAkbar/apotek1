<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4 space-y-8">
        <!-- Stats Section -->
        <livewire:dashboard.stats />

        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl shadow-xl overflow-hidden p-8 text-white relative">
            <div class="relative z-10">
                <h3 class="text-3xl font-bold mb-2">Selamat Datang Kembali, {{ auth()->user()->name }}!</h3>
                <p class="text-blue-100 text-lg mb-6">Kelola sistem Apotek All dengan lebih mudah melalui kontrol panel di bawah ini.</p>
                <div class="flex gap-4">
                    <a href="{{ route('admin.products') }}" wire:navigate class="bg-white text-blue-700 px-6 py-3 rounded-xl font-bold hover:bg-blue-50 transition-colors shadow-lg">
                        Tambah Produk Baru
                    </a>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-white opacity-5 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-2xl">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 dark:text-white">Akses Cepat Kategori</h4>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">Kelola pengelompokan obat berdasarkan jenis, manfaat, atau aturan pakai untuk memudahkan pencarian oleh pelanggan.</p>
                <a href="{{ route('admin.categories') }}" wire:navigate class="text-blue-600 dark:text-blue-400 font-bold hover:underline flex items-center">
                    Klik untuk Kelola Kategori
                    <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4 4H3" />
                    </svg>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-2xl">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 dark:text-white">Akses Cepat Produk</h4>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">Update informasi stok obat, harga terbaru, dan detail medis secara real-time untuk akurasi data inventaris apotek Anda.</p>
                <a href="{{ route('admin.products') }}" wire:navigate class="text-purple-600 dark:text-purple-400 font-bold hover:underline flex items-center">
                    Klik untuk Kelola Produk
                    <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4 4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>