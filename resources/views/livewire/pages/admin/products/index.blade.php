<?php

use function Livewire\Volt\{state, with, usesPagination};
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;

usesPagination();
usesPagination();
use function Livewire\Volt\{uses};
uses(WithFileUploads::class);

state([
    'kode_obat' => '',
    'name' => '',
    'category_id' => '',
    'stock' => 0,
    'price' => 0,
    'indikasi_umum' => '',
    'komposisi' => '',
    'dosis' => '',
    'efek_samping' => '',
    'no_registrasi' => '',
    'is_active' => true,
    'image' => null, // New image file
    'existing_image' => null, // Current image path
    'editingProductId' => null,
    'search' => ''
]);

with(fn () => [
    'products' => Product::with('category')
        ->where('name', 'like', '%' . $this->search . '%')
        ->orWhere('kode_obat', 'like', '%' . $this->search . '%')
        ->latest()
        ->paginate(10),
    'categories' => Category::all(),
    'editingProductId' => $this->editingProductId,
    'name' => $this->name,
    'kode_obat' => $this->kode_obat,
    'category_id' => $this->category_id,
    'stock' => $this->stock,
    'price' => $this->price,
    'indikasi_umum' => $this->indikasi_umum,
    'komposisi' => $this->komposisi,
    'dosis' => $this->dosis,
    'efek_samping' => $this->efek_samping,
    'no_registrasi' => $this->no_registrasi,
    'is_active' => $this->is_active,
    'image' => $this->image,
    'existing_image' => $this->existing_image,
    'search' => $this->search,
]);

$resetFields = function () {
    $this->kode_obat = '';
    $this->name = '';
    $this->category_id = '';
    $this->stock = 0;
    $this->price = 0;
    $this->indikasi_umum = '';
    $this->komposisi = '';
    $this->dosis = '';
    $this->efek_samping = '';
    $this->no_registrasi = '';
    $this->is_active = true;
    $this->image = null;
    $this->existing_image = null;
    $this->editingProductId = null;
};

$save = function () {
    $this->validate([
        'kode_obat' => 'required|unique:products,kode_obat,' . $this->editingProductId,
        'name' => 'required|min:3',
        'category_id' => 'required|exists:categories,id',
        'stock' => 'required|integer',
        'price' => 'required|numeric',
        'image' => 'nullable|image|max:2048', // 2MB max
    ]);

    $data = [
        'kode_obat' => $this->kode_obat,
        'name' => $this->name,
        'category_id' => $this->category_id,
        'stock' => $this->stock,
        'price' => $this->price,
        'indikasi_umum' => $this->indikasi_umum,
        'komposisi' => $this->komposisi,
        'dosis' => $this->dosis,
        'efek_samping' => $this->efek_samping,
        'no_registrasi' => $this->no_registrasi,
        'is_active' => $this->is_active,
    ];

    if ($this->image) {
        $imagePath = $this->image->store('products', 'public');
        $data['image'] = $imagePath;
    }

    if ($this->editingProductId) {
        Product::find($this->editingProductId)->update($data);
        session()->flash('message', 'Produk berhasil diperbarui!');
    } else {
        Product::create($data);
        session()->flash('message', 'Produk berhasil ditambahkan!');
    }

    $this->resetFields();
};

$edit = function ($id) {
    $product = Product::findOrFail($id);
    $this->editingProductId = $id;
    $this->kode_obat = $product->kode_obat;
    $this->name = $product->name;
    $this->category_id = $product->category_id;
    $this->stock = $product->stock;
    $this->price = $product->price;
    $this->no_registrasi = $product->no_registrasi;
    $this->is_active = $product->is_active;
    $this->existing_image = $product->image;
    $this->image = null;
};

$delete = function ($id) {
    Product::find($id)->delete();
    session()->flash('message', 'Produk berhasil dihapus!');
};

?>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manajemen Produk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-6 text-gray-800">Manajemen Produk (Obat)</h2>

                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="space-y-8">
                    <!-- Form Section -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner border border-gray-200">
                        <h3 class="text-lg font-medium mb-6 flex items-center">
                            <span
                                class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">1</span>
                            {{ $editingProductId ? 'Edit Produk' : 'Tambah Produk Baru' }}
                        </h3>

                        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Dasar -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kode Obat</label>
                                <input type="text" wire:model="kode_obat"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                @error('kode_obat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Obat</label>
                                <input type="text" wire:model="name"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                                <select wire:model="category_id"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Stok</label>
                                <input type="number" wire:model="stock"
                                    class="w-full border-gray-300 rounded-lg shadow-sm">
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga</label>
                                <input type="number" wire:model="price"
                                    class="w-full border-gray-300 rounded-lg shadow-sm">
                            </div>

                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">No. Registrasi (BPOM)</label>
                                    <input type="text" wire:model="no_registrasi"
                                        class="w-full border-gray-300 rounded-lg shadow-sm">
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Foto Produk</label>
                                    <div class="flex items-center space-x-6">
                                        <div class="shrink-0">
                                            @if ($image)
                                                <img class="h-24 w-24 object-cover rounded-xl border-2 border-blue-500" src="{{ $image->temporaryUrl() }}">
                                            @elseif ($existing_image)
                                                <img class="h-24 w-24 object-cover rounded-xl border-2 border-gray-200" src="{{ Storage::url($existing_image) }}">
                                            @else
                                                <div class="h-24 w-24 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <label class="block flex-1">
                                            <span class="sr-only">Pilih foto</span>
                                            <input type="file" wire:model="image" class="block w-full text-sm text-slate-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100
                                            "/>
                                            <div wire:loading wire:target="image" class="text-xs text-blue-600 mt-1">Mengunggah...</div>
                                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, JPEG sampai 2MB.</p>
                                        </label>
                                    </div>
                                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                            <!-- Detail Medis -->
                            <div class="md:col-span-3 border-t pt-4 mt-2">
                                <h4 class="text-md font-semibold text-gray-600 mb-4">Informasi Medis Detil</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Indikasi Umum</label>
                                        <textarea wire:model="indikasi_umum" rows="2"
                                            class="w-full border-gray-300 rounded-lg shadow-sm resize-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Komposisi</label>
                                        <textarea wire:model="komposisi" rows="2"
                                            class="w-full border-gray-300 rounded-lg shadow-sm resize-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Dosis</label>
                                        <textarea wire:model="dosis" rows="2"
                                            class="w-full border-gray-300 rounded-lg shadow-sm resize-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Efek Samping</label>
                                        <textarea wire:model="efek_samping" rows="2"
                                            class="w-full border-gray-300 rounded-lg shadow-sm resize-none"></textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="md:col-span-3 flex justify-end space-x-3 mt-4">
                                @if($editingProductId)
                                    <button type="button" wire:click="resetFields"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-150">Batal</button>
                                @endif
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow-lg transition duration-150">
                                    {{ $editingProductId ? 'Update Produk' : 'Simpan Produk' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Table Section -->
                    <div>
                        <div
                            class="flex flex-col md:flex-row md:items-center justify-between mb-4 space-y-2 md:space-y-0">
                            <h3 class="text-lg font-medium flex items-center">
                                <span
                                    class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">2</span>
                                Daftar Produk
                            </h3>
                            <div class="relative">
                                <input type="text" wire:model.live="search" placeholder="Cari nama atau kode obat..."
                                    class="pl-10 pr-4 py-2 border rounded-full w-full md:w-80 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                                <div class="absolute left-3 top-2.5 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-hidden border border-gray-200 rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                         <th
                                             class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                             Produk</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kategori</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Harga & Stok</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($products as $product)
                                        <tr wire:key="prod-{{ $product->id }}" class="hover:bg-gray-50 transition duration-150">
                                             <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-gray-100 mr-4">
                                                        @if($product->image)
                                                            <img class="h-full w-full object-cover" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                                        @else
                                                            <div class="h-full w-full flex items-center justify-center bg-blue-50 text-blue-400">
                                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                                                        <div class="text-xs text-blue-600 font-mono">{{ $product->kode_obat }}</div>
                                                        <div class="text-xs text-gray-500 truncate w-40">{{ $product->no_registrasi }}</div>
                                                    </div>
                                                </div>
                                             </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 font-semibold">Rp
                                                    {{ number_format($product->price, 0, ',', '.') }}</div>
                                                <div
                                                    class="text-xs {{ $product->stock < 10 ? 'text-red-500 font-bold' : 'text-gray-500' }}">
                                                    Stok: {{ $product->stock }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-center text-sm font-medium">
                                                <button wire:click="edit({{ $product->id }})"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold">Edit</button>
                                                <button
                                                    onclick="confirm('Hapus produk ini?') || event.stopImmediatePropagation()"
                                                    wire:click="delete({{ $product->id }})"
                                                    class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Data produk
                                                belum tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
