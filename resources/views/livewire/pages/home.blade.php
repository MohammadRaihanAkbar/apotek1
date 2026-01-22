<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Category;

new #[Layout('layouts.landing')] class extends Component {
    public $search = '';
    // Konfigurasi Nomor WhatsApp
    public $whatsappNumber = '6289505404960';

    public $selectedCategory = null;
    public $selectedProduct = null;
    public $showModal = false;

    public function with()
    {
        $query = Product::with('category');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        // Filter active and not expired
        $query->where('is_active', true)->notExpired();

        return [
            'products' => $query->latest()->get(),
            'categories' => Category::all(),
        ];
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId === $this->selectedCategory ? null : $categoryId;
    }

    public function openModal($productId)
    {
        $this->selectedProduct = Product::find($productId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedProduct = null;
    }
}; ?>
<div>
    {{-- Navbar / Top Bar --}}
    <div
        class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 dark:bg-zinc-900/80 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-primary-600 text-white font-bold shadow-lg shadow-primary-600/20">
                        A
                    </div>
                    <div class="leading-tight">
                        <p class="text-sm font-bold text-slate-800 dark:text-white">Apotek Ananda Jadimulya</p>
                        <p class="text-[10px] sm:text-xs text-primary-600 font-medium">Sehat & Terpercaya</p>
                    </div>
                </div>

                {{-- Nav --}}
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600 dark:text-zinc-400">
                    <a href="#" wire:click.prevent="$set('selectedCategory', null)"
                        class="hover:text-primary-600 transition-colors {{ is_null($selectedCategory) ? 'text-primary-600' : '' }}">Home</a>
                    <a href="#etalase" class="hover:text-primary-600 transition-colors">Product</a>
                    <a href="#tentang" class="hover:text-primary-600 transition-colors">About</a>
                </nav>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition dark:bg-white/10 dark:text-white dark:hover:bg-white/20">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="rounded-full px-4 py-2 text-xs font-semibold text-slate-600 hover:text-primary-600 transition dark:text-zinc-400">
                            Login
                        </a>
                        <a href="{{ route('login') }}"
                            class="rounded-full bg-primary-600 px-5 py-2.5 text-xs font-semibold text-white shadow-lg shadow-primary-600/30 hover:bg-primary-700 hover:scale-105 transition-all active:scale-95">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="pt-24 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        {{-- Hero --}}
        <section class="grid lg:grid-cols-2 gap-12 items-center mb-16">
            <div class="space-y-6">
                <div
                    class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600 border border-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Selamat Datang di Website Apotek Ananda Jadimulya
                </div>

                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-[1.1]">
                    Solusi Sehat <br>
                    <span class="text-primary-600">Keluarga Anda</span>
                </h1>

                <p class="text-lg text-slate-600 dark:text-zinc-400 max-w-lg leading-relaxed">
                    Temukan obat dan kebutuhan kesehatan Anda dengan mudah. Harga transparan, stok update, dan
                    pelayanan
                    ramah.
                </p>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button wire:click="$set('selectedCategory', null)"
                        class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-semibold shadow-xl shadow-slate-900/20 hover:bg-slate-800 transition hover:-translate-y-1 dark:bg-white dark:text-slate-900">
                        Lihat Semua Produk
                    </button>
                    <a href="#tentang"
                        class="px-6 py-3 bg-white text-slate-700 border border-slate-200 rounded-2xl font-semibold hover:bg-slate-50 transition hover:-translate-y-1 dark:bg-white/5 dark:text-white dark:border-white/10">
                        Tentang Kami
                    </a>
                </div>
            </div>

            <div class="relative">
                <div
                    class="absolute -inset-4 bg-gradient-to-r from-primary-100 to-blue-50 rounded-[2.5rem] -z-10 blur-2xl opacity-60 dark:opacity-20">
                </div>
                <div
                    class="aspect-[4/3] rounded-[2rem] bg-slate-200 border border-slate-100 shadow-2xl overflow-hidden relative dark:bg-zinc-900 dark:border-white/10 group">
                    {{-- Foto Apotek --}}
                    <img src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?auto=format&fit=crop&q=80&w=1200"
                        alt="Foto Apotek" class="w-full h-full object-cover">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent flex items-end p-8">
                        <h3 class="text-3xl font-bold text-white drop-shadow-md">Apotek Ananda Jadimulya</h3>
                    </div>
                </div>
            </div>
        </section>

        {{-- Search & Filter --}}
        <section id="etalase" class="mb-12 scroll-mt-28">
            <div
                class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 dark:bg-zinc-900 dark:border-white/5">
                <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    {{-- Search Input --}}
                    <div class="relative w-full md:max-w-md group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-primary-500 transition-colors"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl leading-5 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm transition duration-200 dark:bg-zinc-800 dark:border-zinc-700 dark:text-white text-slate-900"
                            placeholder="Cari nama obat atau produk...">
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-center text-xl font-bold text-slate-900 dark:text-white mb-6">
                        Tersedia produk berdasarkan <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-lg">kondisi
                            kesehatanmu</span>
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button wire:click="$set('selectedCategory', null)"
                            class="flex items-center gap-3 p-4 rounded-2xl border border-slate-100 hover:border-primary-200 hover:shadow-md transition-all bg-white dark:bg-zinc-800 dark:border-white/5 {{ is_null($selectedCategory) ? 'ring-2 ring-primary-500 ring-offset-2' : '' }}">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </div>
                            <span class="font-semibold text-sm text-slate-700 dark:text-zinc-200">Semua</span>
                        </button>

                        @foreach($categories as $cat)
                            <button wire:click="selectCategory({{ $cat->id }})"
                                class="flex items-center gap-3 p-4 rounded-2xl border border-slate-100 hover:border-primary-200 hover:shadow-md transition-all bg-white dark:bg-zinc-800 dark:border-white/5 {{ $selectedCategory == $cat->id ? 'ring-2 ring-primary-500 ring-offset-2' : '' }}">
                                <div
                                    class="w-10 h-10 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                                    {{-- Placeholder Icon --}}
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <span
                                    class="font-semibold text-sm text-slate-700 dark:text-zinc-200 text-left line-clamp-2">{{ $cat->name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
        </section>

        {{-- Product Carousel --}}
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6 px-2">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Etalase Obat</h2>
                <div class="flex gap-2 text-primary-600">
                    <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                    <span class="text-xs font-medium self-center">Geser untuk melihat lainnya</span>
                </div>
            </div>

            <div
                class="flex gap-6 overflow-x-auto pb-8 -mx-4 px-4 sm:mx-0 sm:px-0 snap-x snap-mandatory scrollbar-hide">
                @forelse($products as $product)
                    <div wire:key="product-{{ $product->id }}"
                        class="snap-center shrink-0 w-[240px] sm:w-[280px] group bg-white rounded-3xl border border-slate-100 p-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 dark:bg-zinc-900 dark:border-white/5 relative flex flex-col">
                        {{-- Image --}}
                        <div class="aspect-[4/3] rounded-2xl bg-slate-50 mb-4 overflow-hidden relative dark:bg-zinc-800">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 flex flex-col">
                            <div class="mb-2">
                                <span
                                    class="inline-block px-2 py-1 rounded-lg bg-primary-50 text-[10px] font-bold text-primary-700 mb-2 dark:bg-primary-900/30 dark:text-primary-300 uppercase tracking-wider">
                                    {{ $product->category->name ?? 'Umum' }}
                                </span>
                                <h3
                                    class="font-bold text-slate-900 line-clamp-2 dark:text-white leading-tight min-h-[2.5rem]">
                                    {{ $product->name }}
                                </h3>
                            </div>

                            <div
                                class="mt-auto flex items-center justify-between pt-4 border-t border-slate-50 dark:border-white/5">
                                <span class="font-bold text-lg text-primary-600 dark:text-primary-400">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <button wire:click="openModal({{ $product->id }})"
                                    class="w-9 h-9 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-primary-600 hover:text-white transition-all shadow-sm hover:shadow-primary-600/30 dark:bg-white/10 dark:text-white"
                                    aria-label="Detail">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full py-20 text-center">
                        <div
                            class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 dark:bg-zinc-900">
                            <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Tidak ada produk ditemukan
                        </h3>
                        <p class="text-slate-500">Coba kata kunci lain atau ubah kategori.</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Detail Modal --}}
        @if($showModal && $selectedProduct)
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeModal">
                </div>

                {{-- Modal Panel --}}
                <div class="relative w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row dark:bg-zinc-900 transition-all transform scale-100 max-h-[90vh]">

                    {{-- Close Button --}}
                    <button wire:click="closeModal"
                        class="absolute top-6 right-6 z-20 w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-red-50 hover:text-red-600 transition-all dark:bg-white/10 dark:text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    {{-- Left: Sticky Image & Action Section --}}
                    <div class="w-full md:w-2/5 bg-slate-50 p-8 flex flex-col items-center justify-center relative dark:bg-zinc-950/50 border-r border-slate-100 dark:border-white/5">
                        <div class="w-full aspect-square rounded-3xl bg-white dark:bg-zinc-800 shadow-sm overflow-hidden p-6 flex items-center justify-center mb-8">
                            @if($selectedProduct->image)
                                <img src="{{ Storage::url($selectedProduct->image) }}" alt="{{ $selectedProduct->name }}"
                                    class="max-h-full max-w-full object-contain drop-shadow-2xl">
                            @else
                                <div class="flex flex-col items-center text-slate-300">
                                    <svg class="w-20 h-20 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-xs font-medium italic">Foto belum tersedia</span>
                                </div>
                            @endif
                        </div>

                        <div class="text-center w-full">
                            <h2 class="text-2xl font-extrabold text-slate-900 mb-2 dark:text-white leading-tight">
                                {{ $selectedProduct->name }}
                            </h2>
                            <p class="text-3xl font-black text-primary-600 mb-8 dark:text-primary-400">
                                Rp {{ number_format($selectedProduct->price, 0, ',', '.') }}
                            </p>

                            <a href="https://wa.me/{{ $whatsappNumber }}?text=Halo%20saya%20tertarik%20dengan%20produk%20{{ urlencode($selectedProduct->name) }}"
                                target="_blank"
                                class="flex w-full items-center justify-center gap-3 rounded-2xl bg-green-600 px-8 py-4 text-base font-bold text-white shadow-xl shadow-green-600/20 hover:bg-green-700 hover:-translate-y-1 transition-all active:scale-95">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c1.024.558 2.05.842 3.283.842 6.37 0 6.37-7.995 0-7.995zm3.325 9.073c-.097.23-1.042 1.354-2.128 1.157-1.121-.202-3.896-2.587-4.48-5.32-.239-1.117.844-1.637 1.103-1.637.214 0 .346.06.496.06.33 0 .736-.312.836-.576.223-.585.735-1.99.735-2.158 0-.17-.077-.28-.155-.386-.233-.314-.648-.445-.968-.445-.635 0-1.28.257-1.742.662-.439.385-.929 1.166-.929 2.544 0 1.942 1.764 4.802 4.978 6.223 2.16 1.107 2.924 1.118 3.526 1.054.604-.064 1.482-.601 1.706-1.238.224-.637.224-1.218.155-1.332-.068-.114-.255-.18-.535-.32l-.001-.001z" />
                                </svg>
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>

                    {{-- Right: Comprehensive Content Section --}}
                    <div class="w-full md:w-3/5 p-8 md:p-12 overflow-y-auto bg-white dark:bg-zinc-900 scrollbar-thin">
                        <div class="space-y-8 pb-4">
                            
                            {{-- Section: Deskripsi --}}
                            <div class="section-card border border-primary-100 bg-primary-50/30 rounded-3xl p-6 dark:border-primary-900/30 dark:bg-primary-900/10">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-primary-600 text-white flex items-center justify-center shadow-lg shadow-primary-600/20">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-zinc-100">Deskripsi & Manfaat</h3>
                                </div>
                                <div class="prose prose-sm text-slate-600 dark:text-zinc-300 break-words">
                                    <p class="leading-relaxed">{{ $selectedProduct->indikasi_umum ?: ($selectedProduct->description ?: 'Informasi indikasi belum ditambahkan.') }}</p>
                                </div>
                            </div>

                            {{-- Section: Dosis --}}
                            <div class="section-card border border-slate-100 rounded-3xl p-6 dark:border-white/5 dark:bg-white/5">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/20">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-zinc-100">Dosis & Aturan Pakai</h3>
                                </div>
                                <div class="prose prose-sm text-slate-600 dark:text-zinc-300 break-words">
                                    <p class="leading-relaxed whitespace-pre-line">{{ $selectedProduct->dosis ?: 'Gunakan sesuai petunjuk dokter atau informasi pada kemasan.' }}</p>
                                </div>
                            </div>

                            {{-- Section: Komposisi & Efek Samping --}}
                            <div class="grid grid-cols-1 gap-6">
                                <div class="section-card border border-slate-100 rounded-3xl p-6 dark:border-white/5 dark:bg-white/5">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-500/20">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-800 dark:text-zinc-100">Efek Samping</h3>
                                    </div>
                                    <div class="prose prose-sm text-slate-600 dark:text-zinc-300 break-words">
                                        <p class="leading-relaxed">{{ $selectedProduct->efek_samping ?: 'Tidak tertera efek samping khusus.' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Detail Teknis --}}
                            <div class="section-card border border-slate-100 rounded-3xl p-6 dark:border-white/5 dark:bg-white/5">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-zinc-100">Detail Produk</h3>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                                    <div class="space-y-1 overflow-hidden">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Komposisi</p>
                                        <p class="text-sm font-semibold text-slate-700 dark:text-zinc-200 break-words">{{ $selectedProduct->komposisi ?: '-' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kode Obat</p>
                                        <p class="text-sm font-semibold text-slate-700 dark:text-zinc-200">{{ $selectedProduct->kode_obat ?: '-' }}</p>
                                    </div>
                                    <div class="space-y-1 overflow-hidden">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No. Registrasi (BPOM)</p>
                                        <p class="text-sm font-semibold text-slate-700 dark:text-zinc-200 break-words">{{ $selectedProduct->no_registrasi ?: '-' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kategori</p>
                                        <p class="text-sm font-semibold text-slate-700 dark:text-zinc-200">{{ $selectedProduct->category->name ?? 'Umum' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Stok Tersedia</p>
                                        <p class="text-sm font-semibold text-slate-700 dark:text-zinc-200">{{ $selectedProduct->stock }} Unit</p>
                                    </div>
                                    @if($selectedProduct->expired_date)
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kadaluarsa</p>
                                        <p class="text-sm font-semibold text-red-600 dark:text-red-400">{{ \Carbon\Carbon::parse($selectedProduct->expired_date)->format('d M Y') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </main>

    <footer id="tentang" class="bg-white border-t border-slate-200 pt-16 pb-8 dark:bg-zinc-950 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                {{-- Brand Section --}}
                <div class="col-span-1 lg:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-primary-600 text-white font-bold shadow-lg shadow-primary-600/20">
                            A
                        </div>
                        <div class="leading-tight">
                            <p class="text-sm font-bold text-slate-800 dark:text-white">Apotek Ananda Jadimulya</p>
                            <p class="text-[10px] sm:text-xs text-primary-600 font-medium">Sehat & Terpercaya</p>
                        </div>
                    </div>
                    <p class="text-slate-500 dark:text-zinc-400 text-sm leading-relaxed mb-6">
                        Melayani kebutuhan kesehatan Anda dengan produk terjamin asli, harga transparan, dan pelayanan
                        farmasi profesional.
                    </p>
                    <div class="flex gap-4">
                        {{-- Social Media Icons --}}
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-primary-50 hover:text-primary-600 transition-all dark:bg-white/5 dark:text-zinc-400 dark:hover:bg-primary-900/30 dark:hover:text-primary-400">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-primary-50 hover:text-primary-600 transition-all dark:bg-white/5 dark:text-zinc-400 dark:hover:bg-primary-900/30 dark:hover:text-primary-400">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="col-span-1">
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-6">Navigasi</h3>
                    <ul class="space-y-4">
                        <li><a href="#"
                                class="text-slate-500 hover:text-primary-600 transition-colors text-sm dark:text-zinc-400 dark:hover:text-primary-400">Beranda</a>
                        </li>
                        <li><a href="#etalase"
                                class="text-slate-500 hover:text-primary-600 transition-colors text-sm dark:text-zinc-400 dark:hover:text-primary-400">Etalase
                                Obat</a></li>
                        <li><a href="#tentang"
                                class="text-slate-500 hover:text-primary-600 transition-colors text-sm dark:text-zinc-400 dark:hover:text-primary-400">Tentang
                                Kami</a></li>
                    </ul>
                </div>

                {{-- Contact Info --}}
                <div class="col-span-1">
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-6">Hubungi Kami</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center shrink-0 text-primary-600 dark:bg-primary-900/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">WhatsApp / Telepon</p>
                                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank"
                                    class="text-sm text-slate-500 hover:text-primary-600 dark:text-zinc-400">
                                    +{{ $whatsappNumber }}
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center shrink-0 text-primary-600 dark:bg-primary-900/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">Email</p>
                                <a href="mailto:info@apotekkita.com"
                                    class="text-sm text-slate-500 hover:text-primary-600 dark:text-zinc-400">
                                    info@apotekkita.com
                                </a>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center shrink-0 text-primary-600 dark:bg-primary-900/20">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">Alamat</p>
                                <p class="text-sm text-slate-500 dark:text-zinc-400">
                                    Jl. Sunan Gn. Jati, RT.002/RW.01, Jadimulya, Kec. Gunungjati, Kabupaten Cirebon, Jawa Barat 45151
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Map Section --}}
                <div class="col-span-1 lg:col-span-1">
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-6">Lokasi Kami</h3>
                    <div
                        class="rounded-2xl overflow-hidden shadow-md border border-slate-100 dark:border-white/10 h-48 w-full bg-slate-100 relative group">
                        <iframe
                            src="https://maps.google.com/maps?q=Apotek+Ananda+Jadi+Mulya&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="grayscale group-hover:grayscale-0 transition-all duration-500">
                        </iframe>
                        <div class="absolute bottom-2 right-2">
                            <a href="https://www.google.com/maps/place/Apotek+Ananda+Jadi+Mulya/@-6.6893366,108.2617206,11z/data=!4m10!1m2!2m1!1sapotek+ananda!3m6!1s0x2e6ee38b0443f4ad:0xa43fce26892cce8a!8m2!3d-6.6893366!4d108.5501117!15sCg1hcG90ZWsgYW5hbmRhWg8iDWFwb3RlayBhbmFuZGGSAQhwaGFybWFjeZoBI0NoWkRTVWhOTUc5blMwVkpRMEZuVFVSSmNFMTJkVXgzRUFF4AEA-gEECAAQHw!16s%2Fg%2F11s50hd25b?hl=en&entry=ttu&g_ep=EgoyMDI2MDEyMC4wIKXMDSoASAFQAw%3D%3D"
                                target="_blank"
                                class="bg-white text-xs px-2 py-1 rounded shadow text-slate-700 hover:text-primary-600 font-medium">Buka
                                di Maps</a>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="border-t border-slate-200 dark:border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-slate-500 text-sm dark:text-zinc-400 text-center md:text-left">
                    © {{ date('Y') }} <span class="font-bold text-slate-700 dark:text-white">Apotek Ananda
                        Jadimulya</span>.
                    Melayani dengan hati.
                </p>
                <div class="flex gap-6 text-sm font-medium text-slate-500 dark:text-zinc-400">
                    <a href="#" class="hover:text-primary-600 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-primary-600 transition-colors">Term of Service</a>
                </div>
            </div>
        </div>
    </footer>
</div>