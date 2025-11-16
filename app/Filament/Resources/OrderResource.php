<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $modelLabel = 'Pedido';

    protected static ?string $pluralModelLabel = 'Pedidos';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Cliente')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('customer_phone')
                            ->label('Teléfono')
                            ->required()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('customer_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Estado del Pedido')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Número de Pedido')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'confirmed' => 'Confirmado',
                                'processing' => 'En Proceso',
                                'shipped' => 'Enviado',
                                'completed' => 'Completado',
                                'cancelled' => 'Cancelado',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('payment_status')
                            ->label('Estado de Pago')
                            ->options([
                                'pending' => 'Pendiente',
                                'paid' => 'Pagado',
                                'failed' => 'Fallido',
                                'refunded' => 'Reembolsado',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Detalles de Envío y Pago')
                    ->schema([
                        Forms\Components\Select::make('shipping_method')
                            ->label('Método de Envío')
                            ->options([
                                'moto' => 'Envío en moto',
                                'pickup' => 'Retiro en persona',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Dirección de Envío')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('payment_method')
                            ->label('Método de Pago')
                            ->options([
                                'mercadopago' => 'Mercado Pago',
                                'transfer' => 'Transferencia',
                                'cash' => 'Efectivo',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Totales')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Costo de Envío')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),

                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Notas')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas del Cliente')
                            ->rows(2)
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Notas Internas')
                            ->rows(2),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('# Pedido')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('ARS')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'primary' => 'processing',
                        'success' => fn ($state) => in_array($state, ['shipped', 'completed']),
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmado',
                        'processing' => 'En Proceso',
                        'shipped' => 'Enviado',
                        'completed' => 'Completado',
                        'cancelled' => 'Cancelado',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pago')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => fn ($state) => in_array($state, ['failed', 'refunded']),
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmado',
                        'processing' => 'En Proceso',
                        'shipped' => 'Enviado',
                        'completed' => 'Completado',
                        'cancelled' => 'Cancelado',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Estado de Pago')
                    ->options([
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Método de Pago')
                    ->options([
                        'mercadopago' => 'Mercado Pago',
                        'transfer' => 'Transferencia',
                        'cash' => 'Efectivo',
                    ])
                    ->native(false),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('mark_confirmed')
                    ->label('Confirmar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => $record->status === 'pending')
                    ->action(fn (Order $record) => $record->markAsConfirmed()),

                Tables\Actions\Action::make('mark_completed')
                    ->label('Completar')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => in_array($record->status, ['confirmed', 'processing', 'shipped']))
                    ->action(fn (Order $record) => $record->markAsCompleted()),

                Tables\Actions\Action::make('mark_cancelled')
                    ->label('Cancelar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => !in_array($record->status, ['completed', 'cancelled']))
                    ->action(fn (Order $record) => $record->markAsCancelled()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información del Pedido')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_number')
                            ->label('Número de Pedido')
                            ->copyable()
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (Order $record) => $record->status_color)
                            ->formatStateUsing(fn (Order $record) => $record->status_label),

                        Infolists\Components\TextEntry::make('payment_status')
                            ->label('Estado de Pago')
                            ->badge(),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Fecha de Pedido')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Información del Cliente')
                    ->schema([
                        Infolists\Components\TextEntry::make('customer_name')
                            ->label('Nombre'),

                        Infolists\Components\TextEntry::make('customer_phone')
                            ->label('Teléfono')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('customer_email')
                            ->label('Email')
                            ->copyable(),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Detalles de Envío y Pago')
                    ->schema([
                        Infolists\Components\TextEntry::make('shipping_method')
                            ->label('Método de Envío')
                            ->formatStateUsing(fn ($state) => match($state) {
                                'moto' => 'Envío en moto',
                                'pickup' => 'Retiro en persona',
                                default => $state,
                            }),

                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Método de Pago')
                            ->formatStateUsing(fn ($state) => match($state) {
                                'mercadopago' => 'Mercado Pago',
                                'transfer' => 'Transferencia',
                                'cash' => 'Efectivo',
                                default => $state,
                            }),

                        Infolists\Components\TextEntry::make('shipping_address')
                            ->label('Dirección de Envío')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Items del Pedido')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('product_name')
                                    ->label('Producto'),

                                Infolists\Components\TextEntry::make('product_size')
                                    ->label('Talle'),

                                Infolists\Components\TextEntry::make('product_color')
                                    ->label('Color'),

                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Cantidad'),

                                Infolists\Components\TextEntry::make('price')
                                    ->label('Precio Unit.')
                                    ->money('ARS'),

                                Infolists\Components\TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('ARS'),
                            ])
                            ->columns(6),
                    ]),

                Infolists\Components\Section::make('Totales')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('ARS'),

                        Infolists\Components\TextEntry::make('shipping_cost')
                            ->label('Costo de Envío')
                            ->money('ARS'),

                        Infolists\Components\TextEntry::make('total')
                            ->label('Total')
                            ->money('ARS')
                            ->weight('bold')
                            ->size('lg'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Notas')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Notas del Cliente')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('admin_notes')
                            ->label('Notas Internas')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
