<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.reports';

    protected static ?string $navigationLabel = 'Reportes';

    protected static ?string $title = 'Reportes de Ventas';

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public $dateFrom;
    public $dateTo;
    public $reportData = null;

    public function mount(): void
    {
        // Por defecto, mostrar el mes actual
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');

        $this->form->fill([
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
        ]);

        $this->generateReport();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date_from')
                    ->label('Desde')
                    ->required()
                    ->default(now()->startOfMonth()),

                Forms\Components\DatePicker::make('date_to')
                    ->label('Hasta')
                    ->required()
                    ->default(now()),
            ])
            ->statePath('data')
            ->columns(2);
    }

    public function generateReport(): void
    {
        $dateFrom = $this->data['date_from'] ?? $this->dateFrom;
        $dateTo = $this->data['date_to'] ?? $this->dateTo;

        // Total de pedidos
        $totalOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo])->count();

        // Total de ventas
        $totalSales = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereIn('status', ['completed', 'shipped', 'processing', 'confirmed'])
            ->sum('total');

        // Pedidos por estado
        $ordersByStatus = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                $label = match($item->status) {
                    'pending' => 'Pendientes',
                    'confirmed' => 'Confirmados',
                    'processing' => 'En Proceso',
                    'shipped' => 'Enviados',
                    'completed' => 'Completados',
                    'cancelled' => 'Cancelados',
                    default => $item->status,
                };
                return [$label => $item->count];
            })
            ->toArray();

        // Método de pago más usado
        $paymentMethods = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function ($item) {
                $label = match($item->payment_method) {
                    'mercadopago' => 'Mercado Pago',
                    'transfer' => 'Transferencia',
                    'cash' => 'Efectivo',
                    default => $item->payment_method,
                };
                return [$label => $item->count];
            })
            ->toArray();

        // Productos más vendidos
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->whereIn('orders.status', ['completed', 'shipped', 'processing', 'confirmed'])
            ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_sales'))
            ->groupBy('order_items.product_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();

        // Ventas por día
        $salesByDay = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereIn('status', ['completed', 'shipped', 'processing', 'confirmed'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->total];
            })
            ->toArray();

        $this->reportData = [
            'total_orders' => $totalOrders,
            'total_sales' => $totalSales,
            'average_ticket' => $totalOrders > 0 ? $totalSales / $totalOrders : 0,
            'orders_by_status' => $ordersByStatus,
            'payment_methods' => $paymentMethods,
            'top_products' => $topProducts,
            'sales_by_day' => $salesByDay,
        ];
    }
}
