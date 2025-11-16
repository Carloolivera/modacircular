<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Estadísticas del mes actual
        $currentMonth = now()->startOfMonth();

        // Total de pedidos
        $totalOrders = Order::count();
        $monthOrders = Order::where('created_at', '>=', $currentMonth)->count();

        // Total de ventas
        $totalSales = Order::whereIn('status', ['completed', 'shipped', 'processing', 'confirmed'])->sum('total');
        $monthSales = Order::whereIn('status', ['completed', 'shipped', 'processing', 'confirmed'])
            ->where('created_at', '>=', $currentMonth)
            ->sum('total');

        // Pedidos pendientes
        $pendingOrders = Order::where('status', 'pending')->count();

        // Ticket promedio
        $averageTicket = Order::whereIn('status', ['completed', 'shipped', 'processing', 'confirmed'])
            ->avg('total');

        return [
            Stat::make('Pedidos del Mes', $monthOrders)
                ->description('Total: ' . $totalOrders . ' pedidos')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),

            Stat::make('Ventas del Mes', '$' . number_format($monthSales, 2))
                ->description('Total histórico: $' . number_format($totalSales, 2))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Pedidos Pendientes', $pendingOrders)
                ->description('Requieren atención')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($pendingOrders > 0 ? 'warning' : 'success'),

            Stat::make('Ticket Promedio', '$' . number_format($averageTicket ?? 0, 2))
                ->description('Promedio por pedido')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}
