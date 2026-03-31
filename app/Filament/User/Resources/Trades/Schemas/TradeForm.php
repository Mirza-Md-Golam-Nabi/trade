<?php
namespace App\Filament\User\Resources\Trades\Schemas;

use App\Enums\Direction;
use App\Enums\MarketType;
use App\Enums\RiskLevel;
use App\Enums\TradeType;
use App\Filament\Forms\AccountForm;
use App\Filament\Forms\TradingAssetForm;
use App\Filament\Forms\TradingStrategyForm;
use App\Models\Account;
use App\Models\TradingAsset;
use App\Models\TradingStrategy;
use App\Services\TradeCalculationService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class TradeForm
{
    /**
     * Recalculate all derived fields and push them into the form state.
     * Called from afterStateUpdated on every source field.
     */
    private static function recalculate(Get $get, Set $set): void
    {
        $calc = TradeCalculationService::fromForm([
            'entry_price' => $get('entry_price'),
            'stop_loss'   => $get('stop_loss'),
            'lot_size'    => $get('lot_size'),
            'take_profit' => $get('take_profit'),
            'leverage'    => $get('leverage'),
            'account_id'  => $get('account_id'),
            'trade_fee'   => $get('trade_fee'),
        ]);

        $set('risk_amount', $calc->riskAmount());
        $set('risk_percent', $calc->riskPercent());
        $set('rrr', $calc->rrr());
        $set('capital_usage', $calc->capitalUsage());
        $set('margin', $calc->margin());
        $set('trade_fee', $calc->tradeFee());
    }

    public static function configure(Schema $schema): Schema
    {
        $user_id = auth()->id();

        return $schema
            ->columns(3)
            ->components([
                // ── Left: form fields (span 2 cols) ─────────────────────────
                Grid::make(2)
                    ->columnSpan(2)
                    ->schema([
                        Select::make('market_type')
                            ->label('Class')
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
                            ->required()
                            ->createOptionForm(function (Get $get) {
                                return AccountForm::fields($get('market_type'));
                            })
                            ->createOptionUsing(function (array $data) use ($user_id) {
                                $data['user_id'] = $user_id;
                                $account         = Account::create($data);
                                return $account->getKey();
                            })
                            ->live()
                            ->afterStateUpdated(fn(Get $get, Set $set) => static::recalculate($get, $set)),

                        Select::make('trading_asset_id')
                            ->label('Asset')
                            ->options(function (Get $get) use ($user_id) {
                                return TradingAsset::where('market_type', $get('market_type'))
                                    ->where(function (Builder $query) use ($user_id) {
                                        $query->where('user_id', $user_id)
                                            ->orWhereNull('user_id');
                                    })
                                    ->pluck('symbol', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->createOptionForm(function (Get $get) {
                                return TradingAssetForm::fields($get('market_type'));
                            })
                            ->createOptionUsing(function (array $data) use ($user_id) {
                                $data['user_id'] = $user_id;
                                $asset           = TradingAsset::create($data);
                                return $asset->getKey();
                            }),

                        Select::make('trading_strategy_id')
                            ->label('Strategy')
                            ->options(function () use ($user_id) {
                                return TradingStrategy::where('user_id', $user_id)
                                    ->orWhereNull('user_id')
                                    ->pluck('name', 'id');
                            })
                            ->required()
                            ->createOptionForm(TradingStrategyForm::fields())
                            ->createOptionUsing(function (array $data) use ($user_id) {
                                $data['user_id'] = $user_id;
                                $asset           = TradingStrategy::create($data);
                                return $asset->getKey();
                            }),

                        Select::make('direction')
                            ->options(Direction::class)
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(Set $set, ?Direction $state) =>
                                $set('trade_type', $state?->defaultTradeType()?->value)
                            ),

                        Select::make('risk_level')
                            ->label('Risk Level')
                            ->options(RiskLevel::class),

                        Select::make('trade_type')
                            ->options(TradeType::class)
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(Set $set, ?TradeType $state) =>
                                $set('direction', $state?->defaultDirection()?->value)
                            ),

                        TextInput::make('entry_price')
                            ->required()
                            ->numeric()
                            ->placeholder(0.0)
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Get $get, Set $set) => static::recalculate($get, $set)),

                        TextInput::make('lot_size')
                            ->label('Lot Size')
                            ->numeric()
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Get $get, Set $set) => static::recalculate($get, $set)),

                        TextInput::make('leverage')
                            ->required()
                            ->numeric()
                            ->default(1.0)
                            ->minValue(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Get $get, Set $set) => static::recalculate($get, $set)),

                        TextInput::make('stop_loss')
                            ->label('Stop Loss')
                            ->numeric()
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Get $get, Set $set) => static::recalculate($get, $set)),

                        TextInput::make('take_profit')
                            ->label('Take Profit')
                            ->numeric()
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Get $get, Set $set) => static::recalculate($get, $set)),

                        DateTimePicker::make('opened_at')
                            ->label('Opened At')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpan(2),
                    ]),

                // ── Right: calculation panel (span 1 col) ───────────────────
                Section::make('Trade Calculations')
                    ->columnSpan(1)
                    ->schema([
                        // Not saved to DB
                        TextInput::make('risk_amount')
                            ->label('Risk Amount')
                            ->numeric()
                            ->suffix('৳')
                            ->disabled()
                            ->dehydrated(false),

                        // Saved to DB
                        TextInput::make('risk_percent')
                            ->label('Risk %')
                            ->numeric()
                            ->suffix('%')
                            ->disabled()
                            ->dehydrated(true),

                        // Not saved to DB
                        TextInput::make('rrr')
                            ->label('Risk Reward Ratio')
                            ->numeric()
                            ->suffix('R')
                            ->disabled()
                            ->dehydrated(false),

                        // Saved to DB
                        TextInput::make('capital_usage')
                            ->label('Capital Usage %')
                            ->numeric()
                            ->suffix('%')
                            ->disabled()
                            ->dehydrated(true),

                        // Saved to DB
                        TextInput::make('trade_fee')
                            ->label('Trade Fee')
                            ->numeric()
                            ->suffix('৳')
                            ->disabled()
                            ->dehydrated(true),
                    ]),
            ]);
    }
}
