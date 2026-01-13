<?php

use function Livewire\Volt\{state, with, usesPagination};
use App\Models\Category;

usesPagination();

state([
    'name' => '',
    'description' => '',
    'editingCategoryId' => null,
    'search' => ''
]);

with(fn () => [
    'categories' => Category::where('name', 'like', '%' . $this->search . '%')
        ->latest()
        ->paginate(10),
    'editingCategoryId' => $this->editingCategoryId,
    'name' => $this->name,
    'description' => $this->description,
    'search' => $this->search,
]);

$resetFields = function () {
    $this->name = '';
    $this->description = '';
    $this->editingCategoryId = null;
};

$save = function () {
    $this->validate([
        'name' => 'required|min:3|unique:categories,name,' . $this->editingCategoryId,
        'description' => 'nullable|string',
    ]);

    if ($this->editingCategoryId) {
        $category = Category::find($this->editingCategoryId);
        $category->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        session()->flash('message', 'Kategori berhasil diperbarui!');
    } else {
        Category::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        session()->flash('message', 'Kategori berhasil ditambahkan!');
    }

    $this->resetFields();
};

$edit = function ($id) {
    $category = Category::findOrFail($id);
    $this->editingCategoryId = $id;
    $this->name = $category->name;
    $this->description = $category->description;
};

$delete = function ($id) {
    Category::find($id)->delete();
    session()->flash('message', 'Kategori berhasil dihapus!');
};

?>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Manajemen Kategori
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-6 text-gray-800">Manajemen Kategori</h2>

                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded"
                        role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Form -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                        <h3 class="text-lg font-medium mb-4">
                            {{ $editingCategoryId ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                        </h3>
                        <form wire:submit.prevent="save">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                                <input type="text" wire:model="name"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                @error('name') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                                <textarea wire:model="description"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                    {{ $editingCategoryId ? 'Update' : 'Simpan' }}
                                </button>
                                @if($editingCategoryId)
                                    <button type="button" wire:click="resetFields"
                                        class="text-sm text-gray-600 hover:underline">Batal</button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="md:col-span-2">
                        <div class="mb-4">
                            <input type="text" wire:model.live="search" placeholder="Cari kategori..."
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm">
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Nama</th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Deskripsi</th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr wire:key="cat-{{ $category->id }}">
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap font-medium">
                                                    {{ $category->name }}
                                                </p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-600 whitespace-no-wrap">
                                                    {{ $category->description ?? '-' }}
                                                </p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                                <button wire:click="edit({{ $category->id }})"
                                                    class="text-blue-600 hover:text-blue-900 mr-3 transition duration-150">Edit</button>
                                                <button
                                                    onclick="confirm('Apakah Anda yakin?') || event.stopImmediatePropagation()"
                                                    wire:click="delete({{ $category->id }})"
                                                    class="text-red-600 hover:text-red-900 transition duration-150">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>