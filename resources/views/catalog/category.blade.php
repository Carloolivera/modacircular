@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h1>
    @if($category->description)
    <p class="text-gray-600 mb-8">{{ $category->description }}</p>
    @endif

    @if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
            <a href="{{ route('products.show', $product->slug) }}">
                @if($product->primaryImage)
                <img src="{{ Storage::url($product->primaryImage->path) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover" loading="lazy">
                @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">Sin imagen</span>
                </div>
                @endif
            </a>

            <div class="p-4">
                <a href="{{ route('products.show', $product->slug) }}">
                    <h3 class="font-semibold text-gray-900 hover:text-indigo-600">{{ $product->name }}</h3>
                </a>
                <div class="mt-2 flex items-center justify-between">
                    <p class="text-xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                    <p class="text-sm text-gray-500">Stock: {{ $product->stock }}</p>
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
        <p class="text-gray-500">No hay productos disponibles en esta categoría</p>
    </div>
    @endif
</div>
@endsection
