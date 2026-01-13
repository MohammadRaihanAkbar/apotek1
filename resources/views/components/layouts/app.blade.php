<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-gray-900">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Apotek All') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased h-full">
    <div class="min-h-full flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <livewire:layout.sidebar />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
            <!-- Top bar for mobile or secondary info -->
            <header
                class="md:hidden bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-4 sticky top-0 z-30">
                <div class="flex items-center">
                    <button type="button" @click="$dispatch('open-sidebar')"
                        class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <div class="font-bold text-blue-600 dark:text-blue-400">Apotek All</div>
                <div class="w-6"></div> <!-- Spacer -->
            </header>

            <!-- Page Heading (Desktop/Optional) -->
            @if (isset($header))
                <header
                    class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 hidden md:block">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Content -->
            <main class="flex-1 overflow-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>