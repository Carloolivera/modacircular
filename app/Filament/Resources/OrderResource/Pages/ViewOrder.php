<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Order;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('mark_confirmed')
                ->label('Confirmar Pedido')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Order $record) => $record->status === 'pending')
                ->action(function (Order $record) {
                    $record->markAsConfirmed();
                    $this->refreshFormData(['status', 'confirmed_at']);
                }),

            Actions\Action::make('mark_completed')
                ->label('Completar Pedido')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Order $record) => in_array($record->status, ['confirmed', 'processing', 'shipped']))
                ->action(function (Order $record) {
                    $record->markAsCompleted();
                    $this->refreshFormData(['status', 'completed_at', 'payment_status']);
                }),

            Actions\Action::make('mark_cancelled')
                ->label('Cancelar Pedido')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (Order $record) => !in_array($record->status, ['completed', 'cancelled']))
                ->action(function (Order $record) {
                    $record->markAsCancelled();
                    $this->refreshFormData(['status', 'cancelled_at']);
                }),
        ];
    }
}
