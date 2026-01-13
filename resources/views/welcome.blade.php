<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Apotek Kita</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-primary-50 text-slate-900 antialiased dark:bg-zinc-950 dark:text-zinc-50">
    {{-- Top bar kecil (lebih “brand”, gak AI banget) --}}
    <div class="border-b border-black/5 bg-white/70 backdrop-blur dark:border-white/10 dark:bg-zinc-950/60">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3">
            <p class="text-xs text-slate-600 dark:text-zinc-300">
                Apotek Kita • Informasi obat & etalase produk
            </p>
            <div class="flex items-center gap-3 text-xs text-slate-600 dark:text-zinc-300">
                <span class="hidden sm:inline">WA: 08xx-xxxx-xxxx</span>
                <span class="hidden sm:inline">•</span>
                <span>09.00–21.00</span>
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    <header class="sticky top-0 z-20 border-b border-black/5 bg-white/80 backdrop-blur dark:border-white/10 dark:bg-zinc-950/70">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-primary-600 text-white font-bold">
                    A
                </div>
                <div class="leading-tight">
                    <p class="text-sm font-semibold">Apotek Kita</p>
                    <p class="text-xs text-slate-500 dark:text-zinc-400">Sehat cepat, jelas, rapi</p>
                </div>
            </div>

            <nav class="flex items-center gap-2">
                <a href="#etalase" class="rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-primary-50 dark:text-zinc-200 dark:hover:bg-white/5">
                    Etalase
                </a>
                <a href="#kategori" class="rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-primary-50 dark:text-zinc-200 dark:hover:bg-white/5">
                    Kategori
                </a>
                <a href="#tentang" class="rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-primary-50 dark:text-zinc-200 dark:hover:bg-white/5">
                    Tentang
                </a>

                {{-- Tidak ada tombol login di sini sesuai request.
                     Staff login langsung via /login --}}
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section class="mx-auto max-w-7xl px-6 pt-10 pb-8">
        <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-primary-100 px-3 py-1 text-xs font-semibold text-primary-700 dark:bg-white/10 dark:text-zinc-200">
                    <span class="h-2 w-2 rounded-full bg-primary-600"></span>
                    Etalase obat & informasi produk
                </div>

                <h1 class="mt-4 text-4xl font-bold tracking-tight sm:text-5xl">
                    Cari obat jadi lebih gampang.
                </h1>

                <p class="mt-4 max-w-xl text-base text-slate-600 dark:text-zinc-300">
                    Lihat foto, kategori, dan harga. Customer cukup browsing & search — tanpa login.
                </p>

                {{-- Search bar (nanti Livewire) --}}
                <div class="mt-6">
                    <div class="flex gap-2">
                        <div class="flex w-full items-center gap-2 rounded-2xl border border-black/10 bg-white px-4 py-3 shadow-sm focus-within:ring-2 focus-within:ring-primary-300 dark:border-white/10 dark:bg-zinc-900">
                            <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none">
                                <path d="M10.5 19a8.5 8.5 0 1 1 0-17 8.5 8.5 0 0 1 0 17Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M16.5 16.5 22 22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <input
                                type="text"
                                placeholder="Cari obat… (contoh: Paracetamol)"
                                class="w-full border-0 bg-transparent p-0 text-sm focus:outline-none focus:ring-0"
                            />
                        </div>

                        <button class="rounded-2xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white hover:bg-primary-700">
                            Cari
                        </button>
                    </div>

                    <p class="mt-2 text-xs text-slate-500 dark:text-zinc-400">
                        *Search & filter ini nanti kita hidupkan pakai Livewire (realtime tanpa reload).
                    </p>
                </div>

                {{-- Quick category pills --}}
                <div id="kategori" class="mt-6">
                    <p class="text-xs font-semibold text-slate-600 dark:text-zinc-300">Kategori populer</p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        @php
                            $realCategories = \App\Models\Category::take(8)->get();
                        @endphp

                        @foreach ($realCategories as $c)
                            <button
                                type="button"
                                class="rounded-full border border-black/10 bg-white px-4 py-2 text-sm text-slate-700 hover:border-primary-300 hover:bg-primary-50 dark:border-white/10 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-white/5"
                            >
                                {{ $c->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Panel kanan: info singkat / trust --}}
            <div class="rounded-3xl border border-black/5 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold">Info Apotek</p>
                    <span class="rounded-full bg-primary-100 px-3 py-1 text-xs font-semibold text-primary-700 dark:bg-white/10 dark:text-zinc-200">
                        Open
                    </span>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-primary-50 p-4 dark:bg-zinc-950">
                        <p class="text-xs text-slate-500 dark:text-zinc-400">Layanan</p>
                        <p class="mt-1 text-sm font-semibold">Konsultasi & Etalase</p>
                    </div>
                    <div class="rounded-2xl bg-primary-50 p-4 dark:bg-zinc-950">
                        <p class="text-xs text-slate-500 dark:text-zinc-400">Pembayaran</p>
                        <p class="mt-1 text-sm font-semibold">Tunai / Non-tunai</p>
                    </div>
                    <div class="rounded-2xl bg-primary-50 p-4 dark:bg-zinc-950">
                        <p class="text-xs text-slate-500 dark:text-zinc-400">Catatan</p>
                        <p class="mt-1 text-sm font-semibold">Harga transparan</p>
                    </div>
                    <div class="rounded-2xl bg-primary-50 p-4 dark:bg-zinc-950">
                        <p class="text-xs text-slate-500 dark:text-zinc-400">Ketersediaan</p>
                        <p class="mt-1 text-sm font-semibold">Stok terupdate</p>
                    </div>
                </div>

                <p class="mt-4 text-xs text-slate-500 dark:text-zinc-400">
                    *Data etalase di bawah sudah terhubung ke Admin CRUD kamu.
                </p>
            </div>
        </div>
    </section>

    {{-- Etalase (carousel horizontal) --}}
    <section id="etalase" class="mx-auto max-w-7xl px-6 pb-12">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold">Etalase obat</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-zinc-300">
                    Scroll ke samping untuk lihat obat lain (data asli dari database).
                </p>
            </div>

            <div class="hidden sm:flex gap-2 text-xs text-slate-500 dark:text-zinc-400">
                <span class="rounded-full border border-black/10 bg-white px-3 py-1 dark:border-white/10 dark:bg-zinc-900">Terlaris</span>
                <span class="rounded-full border border-black/10 bg-white px-3 py-1 dark:border-white/10 dark:bg-zinc-900">Baru</span>
            </div>
        </div>

        <div class="mt-6">
            {{-- “Carousel” tanpa library: horizontal scroll + snap --}}
            <div class="flex gap-4 overflow-x-auto pb-3 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden snap-x snap-mandatory">
                @php
                    $realProducts = \App\Models\Product::with('category')->get();
                @endphp

                @foreach ($realProducts as $it)
                    <article class="snap-start w-[260px] shrink-0 rounded-3xl border border-black/5 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-zinc-900">
                        {{-- Foto --}}
                        <div class="aspect-[4/3] w-full overflow-hidden rounded-2xl bg-primary-100 dark:bg-white/10">
                            @if($it->image)
                                <img src="{{ Storage::url($it->image) }}" alt="{{ $it->name }}" class="h-full w-full object-cover transform hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-primary-200 to-primary-50 dark:from-white/10 dark:to-white/5 flex items-center justify-center text-primary-400">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="mt-3">
                            <p class="text-sm font-semibold leading-snug">{{ $it->name }}</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-zinc-400">{{ $it->category->name ?? 'Obat' }}</p>

                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-sm font-bold text-primary-700 dark:text-primary-200">Rp {{ number_format($it->price, 0, ',', '.') }}</p>
                                <button class="rounded-xl border border-black/10 px-3 py-2 text-xs font-semibold hover:bg-primary-50 dark:border-white/10 dark:hover:bg-white/5">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <p class="mt-2 text-xs text-slate-500 dark:text-zinc-400">
                *Etalase ini otomatis terupdate saat kamu menambah obat di halaman Admin.
            </p>
        </div>
    </section>

    {{-- Tentang --}}
    <section id="tentang" class="border-t border-black/5 bg-white py-12 dark:border-white/10 dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-6">
            <h3 class="text-xl font-bold">Tentang apotek</h3>
            <p class="mt-2 max-w-2xl text-sm text-slate-600 dark:text-zinc-300">
                Isi profil apotek di sini: alamat, layanan, jam buka, nomor WA. Customer cukup lihat informasi & katalog.
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-3xl bg-primary-50 p-5 dark:bg-white/5">
                    <p class="text-sm font-semibold">Alamat</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-zinc-300">—</p>
                </div>
                <div class="rounded-3xl bg-primary-50 p-5 dark:bg-white/5">
                    <p class="text-sm font-semibold">Jam buka</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-zinc-300">—</p>
                </div>
                <div class="rounded-3xl bg-primary-50 p-5 dark:bg-white/5">
                    <p class="text-sm font-semibold">Kontak</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-zinc-300">—</p>
                </div>
            </div>

            <p class="mt-8 text-xs text-slate-500 dark:text-zinc-400">
                &copy; {{ date('Y') }} Apotek Kita. All rights reserved.
            </p>
        </div>
    </section>

    <footer class="py-10 text-center text-xs text-slate-500 dark:text-zinc-400">
        © {{ date('Y') }} Apotek Kita
    </footer>
</body>
</html>
