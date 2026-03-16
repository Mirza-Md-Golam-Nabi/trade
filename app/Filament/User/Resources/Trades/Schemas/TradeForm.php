<?php
namespace App\Filament\User\Resources\Trades\Schemas;

use App\Enums\Direction;
use App\Enums\MarketType;
use App\Enums\RiskLevel;
use App\Enums\TradeType;
use App\Models\Account;
use App\Models\TradingAsset;
use App\Models\TradingStrategy;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class TradeForm
{
    public static function configure(Schema $schema): Schema
    {
        $user_id = auth()->id();

        return $schema
            ->components([
                Select::make('market_type')
                    ->label('Market Type')
                    ->options(MarketType::class)
                    ->required()
                    ->live(),
                Select::make('account_id')
                    ->label('Account')
                    ->options(function (Get $get) use ($user_id) {
                        return Account::where('user_id', $user_id)
                            ->where('market_type', $get('market_type'))
                            ->pluck('name', 'id');
                    })
                    ->required(),
                Select::make('trading_asset_id')
                    ->label('Trading Asset')
                    ->options(function (Get $get) use ($user_id) {
                        return TradingAsset::where('user_id', $user_id)
                            ->where('market_type', $get('market_type'))
                            ->pluck('symbol', 'id');
                    })
                    ->required(),
                Select::make('trading_strategy_id')
                    ->label('Trading Strategy')
                    ->options(function () use ($user_id) {
                        return TradingStrategy::where('user_id', $user_id)
                            ->pluck('name', 'id');
                    })
                    ->required(),
                Select::make('direction')
                    ->options(Direction::class)
                    ->required(),
                Select::make('risk_level')
                    ->label('Risk Level')
                    ->options(RiskLevel::class)
                    ->required(),
                Select::make('trade_type')
                    ->options(TradeType::class)
                    ->required(),
                TextInput::make('entry_price')
                    ->required()
                    ->numeric()
                    ->placeholder(0.0)
                    ->prefix('৳'),
                TextInput::make('lot_size')
                    ->numeric(),
                TextInput::make('leverage')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('stop_loss')
                    ->numeric(),
                TextInput::make('take_profit')
                    ->numeric(),
                TextInput::make('trade_fee')
                    ->numeric(),
            ]);
    }
}
