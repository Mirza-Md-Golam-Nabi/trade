<?php

namespace App\Filament\User\Resources\Trades\Pages;

use App\Enums\TradeStatus;
use App\Filament\User\Resources\Trades\TradeResource;
use App\Services\TradeService;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTrade extends ViewRecord
{
    protected static string $resource = TradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('close_position')
                ->label('Close Position')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->record->status === TradeStatus::Open)
                ->form([
                    TextInput::make('exit_price')
                        ->label('Exit Price')
                        ->required()
                        ->numeric()
                        ->minValue(0),

                    Textarea::make('notes')
                        ->label('Closing Notes')
                        ->rows(3),
                ])
                ->action(function (array $data) {
                    $service = new TradeService;

                    try {
                        $service->closePosition(
                            $this->record,
                            (float) $data['exit_price'],
                            $data['notes'] ?? null
                        );

                        Notification::make()
                            ->title('Position Closed')
                            ->body("Trade #{$this->record->id} closed. P&L: ".number_format($this->record->fresh()->profit_loss, 2))
                            ->success()
                            ->send();

                        $this->refreshFormData([
                            'status',
                            'exit_price',
                            'profit_loss',
                            'closed_at',
                            'open_close',
                        ]);

                    } catch (Exception $e) {
                        Notification::make()
                            ->title('Failed to Close Position')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Close Trading Position')
                ->modalDescription('Enter the exit price to close this position. The P&L will be calculated and your account balance will be updated.')
                ->modalSubmitActionLabel('Close Position'),
        ];
    }
}
