@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar Compra</h1>

    <form action="{{ route('cart.whatsapp') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Customer Info -->
            <div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Datos del cliente</h2>

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                            <input type="text" id="name" name="name" required class="w-full border-gray-300 rounded-md shadow-sm @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                            <input type="tel" id="phone" name="phone" required class="w-full border-gray-300 rounded-md shadow-sm @error('phone') border-red-500 @enderror" value="{{ old('phone') }}">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Método de envío</h2>

                    <div class="space-y-3">
                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="shipping_method" value="moto" class="mt-1" required {{ old('shipping_method') == 'moto' ? 'checked' : '' }} onclick="document.getElementById('addressField').style.display='block'">
                            <div class="ml-3">
                                <div class="font-medium text-gray-900">Envío en moto</div>
                                <div class="text-sm text-gray-500">Te lo llevamos a tu domicilio</div>
                            </div>
                        </label>

                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="shipping_method" value="pickup" class="mt-1" required {{ old('shipping_method') == 'pickup' ? 'checked' : '' }} onclick="document.getElementById('addressField').style.display='none'">
                            <div class="ml-3">
                                <div class="font-medium text-gray-900">Retiro en persona</div>
                                <div class="text-sm text-gray-500">Retiras en nuestro local</div>
                            </div>
                        </label>
                    </div>

                    <div id="addressField" class="mt-4" style="display: {{ old('shipping_method') == 'moto' ? 'block' : 'none' }}">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección de envío</label>
                        <textarea id="address" name="address" rows="2" class="w-full border-gray-300 rounded-md shadow-sm @error('address') border-red-500 @enderror" placeholder="Calle, número, piso, depto, ciudad">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Método de pago</h2>

                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="payment_method" value="mercadopago" required {{ old('payment_method') == 'mercadopago' ? 'checked' : '' }}>
                            <span class="ml-3 font-medium text-gray-900">Mercado Pago</span>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="payment_method" value="transfer" required {{ old('payment_method') == 'transfer' ? 'checked' : '' }}>
                            <span class="ml-3 font-medium text-gray-900">Transferencia bancaria</span>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="payment_method" value="cash" required {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                            <span class="ml-3 font-medium text-gray-900">Efectivo</span>
                        </label>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas adicionales (opcional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Instrucciones especiales, preferencias, etc.">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-20">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen del pedido</h2>

                    <div class="space-y-3 mb-6">
                        @foreach($cart as $item)
                        <div class="flex items-start">
                            @if($item['image'])
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded"></div>
                            @endif

                            <div class="ml-3 flex-1">
                                <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-500">Cantidad: {{ $item['quantity'] }}</p>
                                @if($item['size'])
                                <p class="text-sm text-gray-500">Talle: {{ $item['size'] }}</p>
                                @endif
                            </div>

                            <div class="text-right">
                                <p class="font-medium text-gray-900">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between text-xl font-bold">
                            <span>Total</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Enviar pedido por WhatsApp
                    </button>

                    <p class="mt-4 text-xs text-gray-500 text-center">
                        Al hacer clic, serás redirigido a WhatsApp con los detalles de tu pedido
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
