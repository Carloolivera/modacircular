@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Productos</h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="font-semibold text-gray-900 mb-4">Filtros</h2>

                <form method="GET" action="{{ route('products.index') }}">
                    <!-- Categories -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                        <select name="category" class="w-full border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                            <option value="">Todas</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->active_products_count }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                        <select name="sort" class="w-full border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más nuevos</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar productos..." class="w-full border-gray-300 rounded-md shadow-sm">
                        <button type="submit" class="mt-2 w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Buscar
                        </button>
                    </div>

                    @if(request()->hasAny(['category', 'sort', 'search']))
                    <a href="{{ route('products.index') }}" class="mt-4 block text-center text-sm text-indigo-600 hover:text-indigo-800">
                        Limpiar filtros
                    </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
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

            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-gray-500">No se encontraron productos</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
