@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold sm:text-5xl md:text-6xl">
                Moda Circular
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Ropa de calidad a precios accesibles. Encuentra tu estilo perfecto.
            </p>
            <div class="mt-10">
                <a href="{{ route('products.index') }}" class="inline-block bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Ver Productos
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Categories -->
@if($categories->count() > 0)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Categorías</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach($categories as $category)
        <a href="{{ route('category.show', $category->slug) }}" class="group">
            <div class="bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md transition">
                <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600">
                    {{ $category->name }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $category->active_products_count }} productos
                </p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Productos Destacados</h2>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                Ver todos →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                <a href="{{ route('products.show', $product->slug) }}">
                    @if($product->primaryImage)
                    <img src="{{ Storage::url($product->primaryImage->path) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                    @else
                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">Sin imagen</span>
                    </div>
                    @endif
                </a>

                <div class="p-4">
                    <a href="{{ route('category.show', $product->category->slug) }}" class="text-xs text-indigo-600 hover:text-indigo-800">
                        {{ $product->category->name }}
                    </a>
                    <a href="{{ route('products.show', $product->slug) }}">
                        <h3 class="mt-1 font-semibold text-gray-900 hover:text-indigo-600">
                            {{ $product->name }}
                        </h3>
                    </a>
                    <div class="mt-2 flex items-center justify-between">
                        <p class="text-xl font-bold text-gray-900">
                            ${{ number_format($product->price, 2) }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Stock: {{ $product->stock }}
                        </p>
                    </div>
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            Agregar al carrito
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@else
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center">
        <p class="text-gray-500">No hay productos destacados disponibles</p>
    </div>
</div>
@endif

<!-- CTA Section -->
<div class="bg-indigo-700">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
            <span class="block">¿Listo para comprar?</span>
            <span class="block text-indigo-200">Explora nuestro catálogo completo.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
            <div class="inline-flex rounded-md shadow">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                    Ver todos los productos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
