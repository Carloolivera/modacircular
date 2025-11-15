<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Moda Circular') }} - @yield('title', 'Tienda de Ropa')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900">
                            Moda Circular
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('home') ? 'text-gray-900 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-900' }}">
                            Inicio
                        </a>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('products.*') ? 'text-gray-900 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-900' }}">
                            Productos
                        </a>
                    </div>
                </div>

                <!-- Cart -->
                <div class="flex items-center">
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-700 hover:text-gray-900">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                            {{ count(session('cart')) }}
                        </span>
                        @endif
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="sm:hidden" x-show="open" @click.away="open = false" style="display: none;">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 text-base font-medium {{ request()->routeIs('home') ? 'text-indigo-700 bg-indigo-50 border-l-4 border-indigo-500' : 'text-gray-600 hover:bg-gray-50' }}">
                    Inicio
                </a>
                <a href="{{ route('products.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium {{ request()->routeIs('products.*') ? 'text-indigo-700 bg-indigo-50 border-l-4 border-indigo-500' : 'text-gray-600 hover:bg-gray-50' }}">
                    Productos
                </a>
                <a href="{{ route('cart.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-50">
                    Carrito
                    @if(session('cart') && count(session('cart')) > 0)
                        ({{ count(session('cart')) }})
                    @endif
                </a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Moda Circular</h3>
                    <p class="mt-4 text-base text-gray-500">
                        Ropa de calidad a precios accesibles.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Enlaces</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="{{ route('home') }}" class="text-base text-gray-500 hover:text-gray-900">Inicio</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-base text-gray-500 hover:text-gray-900">Productos</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-base text-gray-500 hover:text-gray-900">Carrito</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Contacto</h3>
                    <p class="mt-4 text-base text-gray-500">
                        ¿Preguntas? Contáctanos por WhatsApp
                    </p>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <p class="text-base text-gray-400 text-center">
                    &copy; {{ date('Y') }} Moda Circular. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</body>
</html>
