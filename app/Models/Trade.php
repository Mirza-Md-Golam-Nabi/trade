<?php
namespace App\Models;

use App\Enums\Direction;
use App\Enums\RiskLevel;
use App\Enums\TradeStatus;
use App\Enums\TradeType;
use App\Enums\YesNo;
use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'trading_asset_id',
        'trading_strategy_id',
        'direction',
        'risk_level',
        'trade_type',
        'entry_price',
        'lot_size',
        'leverage',
        'exit_price',
        'stop_loss',
        'take_profit',
        'profit_loss',
        'risk_percent',
        'notes',
        'status',
        'opened_at',
        'closed_at',
        'is_open',
        'is_closed',
    ];

    public function casts(): array
    {
        return [
            'trade_type' => TradeType::class,
            'status'     => TradeStatus::class,
            'is_open'    => YesNo::class,
            'is_closed'  => YesNo::class,
            'direction'  => Direction::class,
            'risk_level' => RiskLevel::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function tradingAsset(): BelongsTo
    {
        return $this->belongsTo(TradingAsset::class);
    }

    public function tradingStrategy(): BelongsTo
    {
        return $this->belongsTo(TradingStrategy::class);
    }
}
