<?php
namespace App\Filament\Forms;

use App\Enums\MarketType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TradingAssetForm
{
    public static function fields(?MarketType $market_type = null): array
    {
        $type = $market_type ?? MarketType::Stock;

        return [
            Select::make('market_type')
                ->label('Trading Type')
                ->options(MarketType::class)
                ->default($type)
                ->required(),
            TextInput::make('symbol')
                ->required(),
            TextInput::make('asset_name')
                ->label('Trading Asset Name'),
        ];
    }
}
