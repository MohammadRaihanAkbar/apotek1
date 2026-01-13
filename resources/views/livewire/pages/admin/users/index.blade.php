<?php

use function Livewire\Volt\{state, with, usesPagination};
use App\Models\User;
use Illuminate\Support\Facades\Hash;

usesPagination();

state([
    'name' => '',
    'email' => '',
    'password' => '',
    'role' => 'admin',
    'is_active' => true,
    'editingUserId' => null,
    'search' => ''
]);

with(fn() => [
    'users' => User::where('name', 'like', '%' . $this->search . '%')
        ->orWhere('email', 'like', '%' . $this->search . '%')
        ->latest()
        ->paginate(10),
    'name' => $this->name,
    'email' => $this->email,
    'password' => $this->password,
    'role' => $this->role,
    'is_active' => $this->is_active,
    'editingUserId' => $this->editingUserId,
    'search' => $this->search,
]);

$resetFields = function () {
    $this->name = '';
    $this->email = '';
    $this->password = '';
    $this->role = 'admin';
    $this->is_active = true;
    $this->editingUserId = null;
};

$save = function () {
    $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email,' . $this->editingUserId,
        'role' => 'required|in:superadmin,admin',
        'is_active' => 'boolean',
    ];

    if (!$this->editingUserId || $this->password) {
        $rules['password'] = 'required|min:6';
    }

    $this->validate($rules);

    $data = [
        'name' => $this->name,
        'email' => $this->email,
        'role' => $this->role,
        'is_active' => $this->is_active,
    ];

    if ($this->password) {
        $data['password'] = Hash::make($this->password);
    }

    if ($this->editingUserId) {
        User::find($this->editingUserId)->update($data);
        session()->flash('message', 'User berhasil diperbarui!');
    } else {
        $data['email_verified_at'] = now();
        User::create($data);
        session()->flash('message', 'User berhasil ditambahkan!');
    }

    $this->resetFields();
};

$edit = function ($id) {
    $user = User::findOrFail($id);
    $this->editingUserId = $id;
    $this->name = $user->name;
    $this->email = $user->email;
    $this->role = $user->role;
    $this->is_active = $user->is_active;
    $this->password = '';
};

$delete = function ($id) {
    if (auth()->id() == $id) {
        session()->flash('error', 'Tidak bisa menghapus akun sendiri!');
        return;
    }

    User::find($id)->delete();
    session()->flash('message', 'User berhasil dihapus!');
};

?>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        User Management
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Manajemen User</h2>

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-8">
                <!-- Form Section -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-inner border border-gray-200">
                    <h3 class="text-lg font-medium mb-6 flex items-center">
                        <span
                            class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">1</span>
                        {{ $editingUserId ? 'Edit User' : 'Tambah User Baru' }}
                    </h3>

                    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                            <input type="text" wire:model="name"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" wire:model="email"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Password {{ $editingUserId ? '(Kosongkan jika tidak diubah)' : '' }}
                            </label>
                            <input type="password" wire:model="password"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                            <select wire:model="role"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="admin">Admin</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                            @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <span class="ml-2 text-sm text-gray-700 font-bold">User Aktif</span>
                            </label>
                        </div>

                        <div class="md:col-span-2 flex justify-end space-x-3 mt-4">
                            @if($editingUserId)
                                <button type="button" wire:click="resetFields"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-150">Batal</button>
                            @endif
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow-lg transition duration-150">
                                {{ $editingUserId ? 'Update User' : 'Simpan User' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table Section -->
                <div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 space-y-2 md:space-y-0">
                        <h3 class="text-lg font-medium flex items-center">
                            <span
                                class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">2</span>
                            Daftar User
                        </h3>
                        <div class="relative">
                            <input type="text" wire:model.live="search" placeholder="Cari nama atau email..."
                                class="pl-10 pr-4 py-2 border rounded-full w-full md:w-80 focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
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
                                        User</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr wire:key="user-{{ $user->id }}" class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 text-xs font-semibold rounded-full 
                                                        {{ $user->role === 'superadmin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-3 py-1 text-xs font-semibold rounded-full 
                                                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-medium">
                                            <button wire:click="edit({{ $user->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold">Edit</button>
                                            @if(auth()->id() != $user->id)
                                                <button onclick="confirm('Hapus user ini?') || event.stopImmediatePropagation()"
                                                    wire:click="delete({{ $user->id }})"
                                                    class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Data user belum
                                            tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>