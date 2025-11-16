<x-filament-panels::page>
    <form wire:submit="generateReport">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit">
                Generar Reporte
            </x-filament::button>
        </div>
    </form>

    @if($reportData)
        <div class="mt-6 space-y-6">
            {{-- Resumen General --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-filament::card>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600">{{ $reportData['total_orders'] }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total de Pedidos</div>
                    </div>
                </x-filament::card>

                <x-filament::card>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-success-600">${{ number_format($reportData['total_sales'], 2) }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total de Ventas</div>
                    </div>
                </x-filament::card>

                <x-filament::card>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-info-600">${{ number_format($reportData['average_ticket'], 2) }}</div>
                        <div class="text-sm text-gray-600 mt-1">Ticket Promedio</div>
                    </div>
                </x-filament::card>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Pedidos por Estado --}}
                <x-filament::card>
                    <h3 class="text-lg font-semibold mb-4">Pedidos por Estado</h3>
                    @if(count($reportData['orders_by_status']) > 0)
                        <div class="space-y-2">
                            @foreach($reportData['orders_by_status'] as $status => $count)
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span class="font-medium">{{ $status }}</span>
                                    <span class="text-gray-600">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay datos</p>
                    @endif
                </x-filament::card>

                {{-- Métodos de Pago --}}
                <x-filament::card>
                    <h3 class="text-lg font-semibold mb-4">Métodos de Pago</h3>
                    @if(count($reportData['payment_methods']) > 0)
                        <div class="space-y-2">
                            @foreach($reportData['payment_methods'] as $method => $count)
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span class="font-medium">{{ $method }}</span>
                                    <span class="text-gray-600">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay datos</p>
                    @endif
                </x-filament::card>
            </div>

            {{-- Productos Más Vendidos --}}
            <x-filament::card>
                <h3 class="text-lg font-semibold mb-4">Top 10 Productos Más Vendidos</h3>
                @if(count($reportData['top_products']) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2 px-4">Producto</th>
                                    <th class="text-right py-2 px-4">Cantidad</th>
                                    <th class="text-right py-2 px-4">Ventas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData['top_products'] as $product)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $product->product_name }}</td>
                                        <td class="text-right py-2 px-4">{{ $product->total_quantity }}</td>
                                        <td class="text-right py-2 px-4">${{ number_format($product->total_sales, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No hay datos</p>
                @endif
            </x-filament::card>

            {{-- Ventas por Día --}}
            <x-filament::card>
                <h3 class="text-lg font-semibold mb-4">Ventas por Día</h3>
                @if(count($reportData['sales_by_day']) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2 px-4">Fecha</th>
                                    <th class="text-right py-2 px-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData['sales_by_day'] as $date => $total)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                        <td class="text-right py-2 px-4">${{ number_format($total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No hay datos</p>
                @endif
            </x-filament::card>
        </div>
    @endif
</x-filament-panels::page>
