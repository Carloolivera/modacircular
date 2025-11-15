@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrito de Compras</h1>

    @if(empty($cart) || count($cart) === 0)
    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <h2 class="mt-4 text-lg font-medium text-gray-900">Tu carrito está vacío</h2>
        <p class="mt-2 text-gray-500">Agrega productos para continuar con tu compra</p>
        <div class="mt-6">
            <a href="{{ route('products.index') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                Ver Productos
            </a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                @foreach($cart as $item)
                <div class="p-6 border-b {{ $loop->last ? '' : 'border-gray-200' }}">
                    <div class="flex items-center">
                        @if($item['image'])
                        <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded">
                        @else
                        <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                            <span class="text-gray-400 text-xs">Sin imagen</span>
                        </div>
                        @endif

                        <div class="ml-6 flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item['name'] }}</h3>
                            @if($item['size'])
                            <p class="text-sm text-gray-500">Talle: {{ $item['size'] }}</p>
                            @endif
                            @if($item['color'])
                            <p class="text-sm text-gray-500">Color: {{ $item['color'] }}</p>
                            @endif
                            <p class="mt-1 font-bold text-gray-900">${{ number_format($item['price'], 2) }}</p>
                        </div>

                        <div class="ml-6 flex items-center space-x-4">
                            <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border-gray-300 rounded-md shadow-sm text-center" onchange="this.form.submit()">
                            </form>

                            <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 flex justify-between">
                <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                    ← Continuar comprando
                </a>
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                        Vaciar carrito
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-20">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen del pedido</h2>

                <div class="space-y-2 mb-4">
                    @foreach($cart as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                        <span class="text-gray-900">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <a href="{{ route('cart.checkout') }}" class="mt-6 block w-full bg-indigo-600 text-white text-center px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                    Finalizar compra
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
