<?php

namespace App\Filament\User\Resources\Trades\Pages;

use App\Filament\User\Resources\Trades\TradeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTrade extends ViewRecord
{
    protected static string $resource = TradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
