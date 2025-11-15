@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-900">Inicio</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('category.show', $product->category->slug) }}" class="text-gray-500 hover:text-gray-900">{{ $product->category->name }}</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Images -->
        <div>
            @if($product->images->count() > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
                <img id="mainImage" src="{{ Storage::url($product->images->first()->path) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
            </div>
            @if($product->images->count() > 1)
            <div class="grid grid-cols-4 gap-2">
                @foreach($product->images as $image)
                <img src="{{ Storage::url($image->path) }}" alt="{{ $product->name }}" class="w-full h-24 object-cover rounded cursor-pointer hover:opacity-75" onclick="document.getElementById('mainImage').src='{{ Storage::url($image->path) }}'">
                @endforeach
            </div>
            @endif
            @else
            <div class="bg-gray-200 rounded-lg h-96 flex items-center justify-center">
                <span class="text-gray-400">Sin imágenes</span>
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
            <p class="mt-2 text-sm text-indigo-600">{{ $product->category->name }}</p>

            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
            </div>

            @if($product->description)
            <div class="mt-6">
                <h2 class="font-semibold text-gray-900 mb-2">Descripción</h2>
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>
            @endif

            <div class="mt-6 grid grid-cols-2 gap-4">
                @if($product->size)
                <div>
                    <p class="text-sm font-medium text-gray-700">Talle</p>
                    <p class="mt-1 text-gray-900">{{ $product->size }}</p>
                </div>
                @endif

                @if($product->color)
                <div>
                    <p class="text-sm font-medium text-gray-700">Color</p>
                    <p class="mt-1 text-gray-900">{{ $product->color }}</p>
                </div>
                @endif

                <div>
                    <p class="text-sm font-medium text-gray-700">Stock disponible</p>
                    <p class="mt-1 text-gray-900">{{ $product->stock }} unidades</p>
                </div>
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-8">
                @csrf
                <div class="flex items-center space-x-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                        <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold transition">
                            Agregar al carrito
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Productos relacionados</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                <a href="{{ route('products.show', $relatedProduct->slug) }}">
                    @if($relatedProduct->primaryImage)
                    <img src="{{ Storage::url($relatedProduct->primaryImage->path) }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-sm">Sin imagen</span>
                    </div>
                    @endif
                </a>
                <div class="p-4">
                    <a href="{{ route('products.show', $relatedProduct->slug) }}">
                        <h3 class="font-semibold text-gray-900 hover:text-indigo-600">{{ $relatedProduct->name }}</h3>
                    </a>
                    <p class="mt-2 text-lg font-bold text-gray-900">${{ number_format($relatedProduct->price, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
