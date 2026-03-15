<?php
namespace App\Filament\User\Resources\TradingStrategies\Pages;

use App\Filament\User\Resources\TradingStrategies\TradingStrategyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTradingStrategies extends ManageRecords
{
    protected static string $resource = TradingStrategyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->createAnother(false)
                ->mutateDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();

                    return $data;
                }),
        ];
    }
}
