<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TransactionType: string implements HasColor, HasLabel
{
    case TradeOpen = 'trade_open';
    case TradeClose = 'trade_close';
    case ProfitLoss = 'profit_loss';
    case Fee = 'fee';
    case Deposit = 'deposit';
    case Withdrawal = 'withdrawal';

    public function getLabel(): string
    {
        return match ($this) {
            self::TradeOpen => 'Trade Open',
            self::TradeClose => 'Trade Close',
            self::ProfitLoss => 'Profit/Loss',
            self::Fee => 'Fee',
            self::Deposit => 'Deposit',
            self::Withdrawal => 'Withdrawal',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::TradeOpen => 'warning',
            self::TradeClose => 'success',
            self::ProfitLoss => 'info',
            self::Fee => 'danger',
            self::Deposit => 'primary',
            self::Withdrawal => 'gray',
        };
    }
}
