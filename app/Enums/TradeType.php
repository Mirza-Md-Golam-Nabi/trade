<?php
namespace App\Enums;

use App\Enums\Direction;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TradeType: string implements HasLabel, HasColor {
    case Buy  = 'buy';
    case Sell = 'sell';

    public function getLabel(): string
    {
        return match ($this) {
            self::Buy  => 'Buy',
            self::Sell => 'Sell',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Buy  => 'success',
            self::Sell => 'primary',
        };
    }

    public function defaultDirection(): Direction
    {
        return match ($this) {
            self::Buy  => Direction::Long,
            self::Sell => Direction::Short,
        };
    }
}
