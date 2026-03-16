<?php

namespace App\Filament\User\Resources\Trades\Pages;

use App\Enums\TradeStatus;
use App\Enums\YesNo;
use App\Filament\User\Resources\Trades\TradeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTrade extends CreateRecord
{
    protected static string $resource = TradeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['status'] = TradeStatus::Open;
        $data['opened_at'] = now();
        $data['open_close'] = YesNo::Yes;

        return $data;
    }
}
