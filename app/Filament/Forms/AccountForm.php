<?php
namespace App\Filament\Forms;

use App\Enums\CommonStatus;
use App\Enums\MarketType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class AccountForm
{
    public static function fields(?MarketType $market_type = null): array
    {
        $type = $market_type ?? MarketType::Stock;

        return [
            TextInput::make('name')
                ->label('Brokerage House Name')
                ->required(),
            Select::make('market_type')
                ->label('Market Type')
                ->options(MarketType::class)
                ->default($type)
                ->required(),
            TextInput::make('balance')
                ->label('Initial Balance')
                ->required()
                ->numeric()
                ->placeholder(0.0),
            TextInput::make('trade_fee')
                ->label('Trade Fee')
                ->required()
                ->numeric()
                ->placeholder(0.0)
                ->helperText('Trade Fee in percentage'),
            Select::make('currency_id')
                ->label('Currency')
                ->relationship('currency', 'name')
                ->required(),
            TextInput::make('notes')
                ->label('Notes'),
            Select::make('status')
                ->label('Status')
                ->options(CommonStatus::class)
                ->default(CommonStatus::Active)
                ->required(),
        ];
    }
}
