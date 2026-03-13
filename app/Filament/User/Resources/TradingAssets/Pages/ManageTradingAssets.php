<?php
namespace App\Filament\User\Resources\TradingAssets\Pages;

use App\Filament\User\Resources\TradingAssets\TradingAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTradingAssets extends ManageRecords
{
    protected static string $resource = TradingAssetResource::class;

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
