<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ 
    mobileOpen: false, 
    desktopCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
    toggleDesktop() {
        this.desktopCollapsed = !this.desktopCollapsed;
        localStorage.setItem('sidebar_collapsed', this.desktopCollapsed);
    }
}" 
@open-sidebar.window="mobileOpen = true" 
class="relative flex">
    <!-- Mobile sidebar backdrop -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 md:hidden" 
         @click="mobileOpen = false"></div>

    <!-- Sidebar component -->
    <aside 
        :class="{
            'translate-x-0': mobileOpen,
            '-translate-x-full': !mobileOpen,
            'md:w-64': !desktopCollapsed,
            'md:w-20': desktopCollapsed,
            'md:translate-x-0': true
        }" 
        class="fixed inset-y-0 left-0 flex flex-col bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-all duration-300 ease-in-out z-50 md:static shadow-xl md:shadow-none">
        
        <!-- Sidebar header -->
        <div class="flex items-center justify-between h-16 px-4 bg-blue-600 dark:bg-blue-800 text-white shrink-0 overflow-hidden">
            <div class="flex items-center space-x-3 transition-all duration-300" :class="desktopCollapsed ? 'justify-center w-full' : ''">
                <svg class="w-8 h-8 shrink-0 fill-current" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                </svg>
                <span x-show="!desktopCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="text-xl font-bold tracking-wider uppercase whitespace-nowrap">Apotek All</span>
            </div>
            <button @click="mobileOpen = false" class="md:hidden text-white hover:text-blue-100">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Desktop Toggle Button -->
        <button @click="toggleDesktop()" class="hidden md:flex absolute -right-3 top-20 bg-white border border-gray-200 dark:bg-gray-700 dark:border-gray-600 rounded-full p-1 shadow-md z-50 hover:bg-gray-50 dark:hover:bg-gray-600 transition-transform duration-300" :style="desktopCollapsed ? 'transform: rotate(180deg)' : ''">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Navigation links -->
        <div class="flex-1 px-3 py-6 overflow-y-auto space-y-2 custom-scrollbar">
            <template x-if="!desktopCollapsed">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Menu</p>
            </template>
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" wire:navigate
                class="flex items-center justify-center md:justify-start px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                <svg class="w-6 h-6 shrink-0" :class="!desktopCollapsed && 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="!desktopCollapsed" class="whitespace-nowrap">Dashboard</span>
            </a>

            <!-- POS -->
            <a href="{{ route('pos.index') }}" wire:navigate
                class="flex items-center justify-center md:justify-start px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('pos.*') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                <svg class="w-6 h-6 shrink-0" :class="!desktopCollapsed && 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="!desktopCollapsed" class="whitespace-nowrap">POS / Kasir</span>
            </a>

            <!-- Categories -->
            <a href="{{ route('admin.categories') }}" wire:navigate
                class="flex items-center justify-center md:justify-start px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.categories') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                <svg class="w-6 h-6 shrink-0" :class="!desktopCollapsed && 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span x-show="!desktopCollapsed" class="whitespace-nowrap">Kategori</span>
            </a>

            <!-- Products -->
            <a href="{{ route('admin.products') }}" wire:navigate
                class="flex items-center justify-center md:justify-start px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.products') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                <svg class="w-6 h-6 shrink-0" :class="!desktopCollapsed && 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span x-show="!desktopCollapsed" class="whitespace-nowrap">Produk</span>
            </a>

            <!-- Stock Movements -->
            <a href="{{ route('admin.stock-movements') }}" wire:navigate
                class="flex items-center justify-center md:justify-start px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.stock-movements') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                <svg class="w-6 h-6 shrink-0" :class="!desktopCollapsed && 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span x-show="!desktopCollapsed" class="whitespace-nowrap">Stock Movement</span>
            </a>

            <!-- User Management (Superadmin Only) -->
            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.users') }}" wire:navigate
                    class="flex items-center justify-center md:justify-start px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                    <svg class="w-6 h-6 shrink-0" :class="!desktopCollapsed && 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span x-show="!desktopCollapsed" class="whitespace-nowrap">User Management</span>
                </a>
            @endif
        </div>

        <!-- Sidebar footer -->
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
            <div :class="desktopCollapsed ? 'justify-center' : ''" class="flex items-center p-2 mb-4 bg-gray-50 dark:bg-gray-900 rounded-xl overflow-hidden">
                <div class="h-10 w-10 shrink-0 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-700 dark:text-blue-200 font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div x-show="!desktopCollapsed" class="ml-3 min-w-0">
                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                </div>
            </div>
            
            <div :class="desktopCollapsed ? 'flex-col space-y-2' : 'flex space-x-2'" class="flex">
                <a href="{{ route('profile') }}" wire:navigate title="Profile" class="flex-1 flex justify-center py-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </a>
                <button wire:click="logout" title="Keluar" class="flex-1 flex justify-center py-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    <style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>
</div>